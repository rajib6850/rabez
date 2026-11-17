<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Media;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\User;
use App\Services\ImageOptimizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $revenueToday = Order::whereDate('created_at', today())->sum('total');
        $openOrdersCount = Order::whereIn('status', ['Pending', 'Printing'])->count();
        $printQueueCount = OrderItem::whereHas('order', function ($q) {
            $q->whereIn('status', ['Pending', 'Printing']);
        })->where(function ($q) {
            $q->where(function ($sub) {
                $sub->whereNotNull('custom_name')->where('custom_name', '!=', '');
            })->orWhere(function ($sub) {
                $sub->whereNotNull('custom_number')->where('custom_number', '!=', '');
            });
        })->count();
        $codWithCourier = Order::where('status', 'Shipped')->sum('total');
        $productsCount = Product::count();

        $recentOrders = Order::with(['items', 'handler:id,name,role,avatar'])->latest()->take(10)->get();
        $allOrders = Order::with(['items', 'handler:id,name,role,avatar'])->latest()->get();
        $products = Product::with('variants')->latest()->get();
        $categories = Category::withCount('products')->orderBy('sort_order')->get();
        $settings = Setting::pluck('value', 'key')->toArray();
        $coupons = Coupon::latest()->get();

        $customers = Order::select('customer_name', 'customer_phone', 'customer_address')
            ->selectRaw('count(*) as total_orders')
            ->selectRaw('sum(total) as total_spent')
            ->selectRaw('max(created_at) as last_order_date')
            ->selectRaw("sum(case when status = 'Delivered' then 1 else 0 end) as delivered_orders")
            ->selectRaw("sum(case when status = 'Cancelled' then 1 else 0 end) as cancelled_orders")
            ->groupBy('customer_phone')
            ->orderByDesc('last_order_date')
            ->get()
            ->map(function ($c) {
                $total = max(1, (int)$c->total_orders);
                $delivered = (int)$c->delivered_orders;
                $cancelled = (int)$c->cancelled_orders;
                $ratio = round((($delivered ?: ($total - $cancelled)) / $total) * 100);
                return [
                    'name' => $c->customer_name,
                    'phone' => $c->customer_phone,
                    'address' => $c->customer_address,
                    'total_orders' => (int)$c->total_orders,
                    'total_spent' => (float)$c->total_spent,
                    'last_order' => $c->last_order_date ? \Carbon\Carbon::parse($c->last_order_date)->diffForHumans() : 'Recently',
                    'delivery_ratio' => min(100, max(25, $ratio ?: 92)),
                    'parcels' => ($delivered ?: $total) . " of " . $total,
                    'status' => $ratio >= 60 ? 'Safe for COD' : 'High return risk',
                ];
            });

        $actor = Auth::user();
        $users = ($actor && $actor->isMainAdmin()) ? User::latest()->get() : collect();
        $teamHandlers = User::whereIn('role', ['admin', 'manager', 'staff'])
            ->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '!=', 'inactive');
            })
            ->orderByRaw("CASE LOWER(role) WHEN 'admin' THEN 1 WHEN 'manager' THEN 2 ELSE 3 END")
            ->orderBy('name')
            ->get(['id', 'name', 'role', 'avatar']);
        $media = Media::latest()->get();
        $mediaCount = $media->count();
        $vapidPublicKey = '';
        try {
            $vapidPublicKey = app(\App\Services\WebPushService::class)->getPublicKey();
        } catch (\Throwable $e) {
        }
        unset($settings['vapid_private_pem']);

        return view('admin.dashboard', compact(
            'revenueToday',
            'openOrdersCount',
            'printQueueCount',
            'codWithCourier',
            'productsCount',
            'recentOrders',
            'allOrders',
            'products',
            'categories',
            'customers',
            'users',
            'teamHandlers',
            'media',
            'mediaCount',
            'coupons',
            'settings',
            'vapidPublicKey'
        ));
    }

    private function findOrderByIdentifier($id): Order
    {
        return Order::with(['items', 'handler:id,name,role,avatar'])
            ->where('id', $id)
            ->orWhere('order_number', $id)
            ->firstOrFail();
    }

    private function requireMainAdmin(): ?\Illuminate\Http\JsonResponse
    {
        if (!Auth::user()?->isMainAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Only Main Admin can perform this action.',
            ], 403);
        }

        return null;
    }

    /**
     * Verify whether the current admin/staff user may modify an order.
     * Atomically claims unassigned orders when $claimIfUnassigned is true.
     */
    private function ensureOrderHandlingAccess(Order $order, bool $claimIfUnassigned = true): ?\Illuminate\Http\JsonResponse
    {
        /** @var User $actor */
        $actor = Auth::user();

        if ($order->isLockedFor($actor)) {
            $order->loadMissing(['items', 'handler:id,name,role,avatar']);
            return response()->json([
                'success' => false,
                'locked' => true,
                'handled_by_user_id' => $order->handled_by_user_id,
                'handled_by_name' => $order->handled_by_name,
                'message' => "Order #{$order->order_number} is currently being handled by " . ($order->handled_by_name ?: 'another staff member') . ". Only they or Main Admin can modify it.",
                'order' => $order,
            ], 423);
        }

        if ($claimIfUnassigned && $order->handled_by_user_id === null) {
            $now = now();
            $claimed = Order::where('id', $order->id)
                ->whereNull('handled_by_user_id')
                ->update([
                    'handled_by_user_id' => $actor->id,
                    'handled_by_name' => $actor->name,
                    'handled_at' => $now,
                ]);

            if ($claimed === 0) {
                $fresh = Order::with(['items', 'handler:id,name,role,avatar'])->findOrFail($order->id);
                if ($fresh->isLockedFor($actor)) {
                    return response()->json([
                        'success' => false,
                        'locked' => true,
                        'handled_by_user_id' => $fresh->handled_by_user_id,
                        'handled_by_name' => $fresh->handled_by_name,
                        'message' => "Order #{$fresh->order_number} was just claimed by " . ($fresh->handled_by_name ?: 'another staff member') . ".",
                        'order' => $fresh,
                    ], 423);
                }
                $order->setRawAttributes($fresh->getAttributes());
            } else {
                $order->handled_by_user_id = $actor->id;
                $order->handled_by_name = $actor->name;
                $order->handled_at = $now;
            }
        }

        return null;
    }

    public function getOrders()
    {
        $orders = Order::with(['items', 'handler:id,name,role,avatar'])->latest()->get();
        return response()->json(['success' => true, 'orders' => $orders]);
    }

    public function pollOrders(Request $request)
    {
        $hasAfterParam = $request->has('after_id');
        $afterId = max(0, (int) $request->query('after_id', 0));
        $latestId = (int) (Order::max('id') ?? 0);

        $newOrders = collect();
        if ($hasAfterParam && $latestId > $afterId) {
            $newOrders = Order::with(['items', 'handler:id,name,role,avatar'])
                ->where('id', '>', $afterId)
                ->orderBy('id', 'asc')
                ->limit(25)
                ->get();
        }

        $handlingStates = Order::with('handler:id,name,role,avatar')
            ->where(function ($q) {
                $q->whereNotNull('handled_by_user_id')
                    ->orWhere('updated_at', '>=', now()->subHours(12));
            })
            ->latest('updated_at')
            ->limit(150)
            ->get(['id', 'order_number', 'handled_by_user_id', 'handled_by_name', 'handled_at', 'status', 'courier', 'consignment_id']);

        return response()->json([
            'success' => true,
            'latest_id' => $latestId,
            'new_orders' => $newOrders,
            'handling_states' => $handlingStates,
            'open_count' => Order::whereIn('status', ['Pending', 'Printing'])->count(),
        ]);
    }

    public function claimOrder(Request $request, $id)
    {
        $order = $this->findOrderByIdentifier($id);
        /** @var User $actor */
        $actor = Auth::user();

        if ($actor->isMainAdmin() && $request->boolean('force') && (int) $order->handled_by_user_id !== (int) $actor->id) {
            $order->handled_by_user_id = $actor->id;
            $order->handled_by_name = $actor->name;
            $order->handled_at = now();
            $order->save();
        } else {
            if ($lockError = $this->ensureOrderHandlingAccess($order, true)) {
                return $lockError;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "You are now handling Order #{$order->order_number}.",
            'order' => $order->fresh(['items', 'handler:id,name,role,avatar']),
        ]);
    }

    public function releaseOrder($id)
    {
        $order = $this->findOrderByIdentifier($id);
        if ($lockError = $this->ensureOrderHandlingAccess($order, false)) {
            return $lockError;
        }

        $order->handled_by_user_id = null;
        $order->handled_by_name = null;
        $order->handled_at = null;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => "Order #{$order->order_number} released back to unassigned queue.",
            'order' => $order->fresh(['items', 'handler:id,name,role,avatar']),
        ]);
    }

    public function assignOrder(Request $request, $id)
    {
        if ($deny = $this->requireMainAdmin()) {
            return $deny;
        }

        $order = $this->findOrderByIdentifier($id);
        $validated = $request->validate([
            'handled_by_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if (!empty($validated['handled_by_user_id'])) {
            $target = User::findOrFail($validated['handled_by_user_id']);
            if (!$target->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Orders can only be assigned to active admin, manager, or staff members.',
                ], 422);
            }
            $order->handled_by_user_id = $target->id;
            $order->handled_by_name = $target->name;
            $order->handled_at = now();
            $message = "Order #{$order->order_number} assigned to {$target->name}.";
        } else {
            $order->handled_by_user_id = null;
            $order->handled_by_name = null;
            $order->handled_at = null;
            $message = "Order #{$order->order_number} returned to unassigned queue.";
        }

        $order->save();

        return response()->json([
            'success' => true,
            'message' => $message,
            'order' => $order->fresh(['items', 'handler:id,name,role,avatar']),
        ]);
    }

    public function updateOrder(Request $request, $id)
    {
        $order = $this->findOrderByIdentifier($id);
        if ($lockError = $this->ensureOrderHandlingAccess($order, true)) {
            return $lockError;
        }

        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:Pending,Printing,Shipped,Delivered,Cancelled',
            'zone' => 'nullable|string',
            'shipping_fee' => 'nullable|numeric',
        ]);

        if (isset($validated['customer_name'])) $order->customer_name = strip_tags($validated['customer_name']);
        if (isset($validated['customer_phone'])) $order->customer_phone = strip_tags($validated['customer_phone']);
        if (isset($validated['customer_address'])) $order->customer_address = strip_tags($validated['customer_address']);
        if (isset($validated['zone'])) $order->zone = $validated['zone'];
        if (isset($validated['shipping_fee'])) {
            $order->shipping_fee = $validated['shipping_fee'];
            $order->total = $order->subtotal + $order->shipping_fee;
        }

        if (isset($validated['status'])) {
            $order->status = $validated['status'];
            $stepMap = [
                'Pending' => 1,
                'Printing' => 3,
                'Shipped' => 4,
                'Delivered' => 5,
                'Cancelled' => 1,
            ];
            if (isset($stepMap[$order->status])) {
                $order->status_step = $stepMap[$order->status];
            }
        }

        $order->save();

        return response()->json(['success' => true, 'order' => $order->load(['items', 'handler:id,name,role,avatar'])]);
    }

    public function deleteOrder($id)
    {
        $order = $this->findOrderByIdentifier($id);
        if ($lockError = $this->ensureOrderHandlingAccess($order, false)) {
            return $lockError;
        }
        $order->delete();

        return response()->json(['success' => true, 'message' => "Order #{$order->order_number} deleted successfully."]);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = $this->findOrderByIdentifier($id);
        if ($lockError = $this->ensureOrderHandlingAccess($order, true)) {
            return $lockError;
        }
        $status = $request->input('status');
        $stepMap = [
            'Pending' => 1,
            'Printing' => 3,
            'Shipped' => 4,
            'Delivered' => 5,
            'Cancelled' => 1,
        ];

        $order->status = $status;
        if (isset($stepMap[$status])) {
            $order->status_step = $stepMap[$status];
        }
        $order->save();

        return response()->json(['success' => true, 'order' => $order->load(['items', 'handler:id,name,role,avatar'])]);
    }

    public function bookCourier(Request $request, $id)
    {
        $order = $this->findOrderByIdentifier($id);
        if ($lockError = $this->ensureOrderHandlingAccess($order, true)) {
            return $lockError;
        }
        $courier = $request->input('courier', 'Steadfast');

        $order->status = 'Shipped';
        $order->status_step = 4;
        $order->courier = $courier . ' Courier';
        $order->consignment_id = ($courier === 'Pathao' ? 'PTH-' : 'SF-') . rand(80000000, 99999999);
        $order->save();

        return response()->json([
            'success' => true,
            'consignment_id' => $order->consignment_id,
            'courier' => $order->courier,
            'order' => $order->load(['items', 'handler:id,name,role,avatar']),
        ]);
    }

    public function getProducts()
    {
        $products = Product::with('variants')->latest()->get();
        return response()->json(['success' => true, 'products' => $products]);
    }

    public function storeProduct(Request $request, ImageOptimizerService $optimizer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric',
            'regular_price' => 'nullable|numeric',
            'club' => 'nullable|string',
            'sleeve' => 'nullable|string',
            'fabric' => 'nullable|string|max:191',
            'short_desc' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'gallery' => 'nullable',
            'has_custom_print' => 'nullable|boolean',
            'has_variants' => 'nullable|boolean',
            'options' => 'nullable',
            'variants' => 'nullable',
            'sizes' => 'nullable|array',
            'all_sizes' => 'nullable|array',
        ]);

        $featuredImageUrl = $validated['image'] ?? null;
        if ($request->hasFile('photo')) {
            $media = $optimizer->optimizeAndStore($request->file('photo'));
            $featuredImageUrl = $media->url;
        }

        $galleryUrls = $request->input('gallery', []);
        if (is_string($galleryUrls)) {
            $galleryUrls = json_decode($galleryUrls, true) ?: [];
        }
        if (!is_array($galleryUrls)) {
            $galleryUrls = [];
        }

        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $gFile) {
                $gMedia = $optimizer->optimizeAndStore($gFile);
                $galleryUrls[] = $gMedia->url;
            }
        }

        if (empty($featuredImageUrl) && !empty($galleryUrls)) {
            $featuredImageUrl = array_shift($galleryUrls);
        }

        $baseName = str_replace(['/', '\\'], '-', $validated['name']);
        $baseSlug = Str::slug($baseName);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $counter++;
            $slug = "{$baseSlug}-{$counter}";
        }

        $sleeve = $validated['sleeve'] ?? 'half-sleeve';

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'sku' => strtoupper($validated['sku']),
            'club' => $validated['club'] ?? 'all',
            'sleeve' => $sleeve,
            'fabric' => isset($validated['fabric']) ? (trim($validated['fabric']) ?: null) : null,
            'price' => $validated['price'],
            'regular_price' => $validated['regular_price'] ?? ($validated['price'] + 200),
            'short_desc' => $validated['short_desc'] ?? '',
            'description' => $validated['description'] ?? '',
            'image' => $featuredImageUrl ?? '/assets/images/products/real-madrid-home-hs.jpg',
            'gallery' => $galleryUrls,
            'is_ready_stock' => true,
            'has_custom_print' => $request->boolean('has_custom_print', false),
            'has_variants' => $request->boolean('has_variants', false),
        ]);

        $hasVariants = $request->boolean('has_variants', false);
        $rawOptions = $request->input('options');
        if (is_string($rawOptions)) $rawOptions = json_decode($rawOptions, true) ?: [];
        $rawVariants = $request->input('variants');
        if (is_string($rawVariants)) $rawVariants = json_decode($rawVariants, true) ?: [];

        if ($hasVariants && !empty($rawVariants) && is_array($rawVariants)) {
            $this->syncShopifyVariants($product, is_array($rawOptions) ? $rawOptions : [], $rawVariants);
        } else {
            $inStockSizes = $request->exists('sizes') ? ($request->input('sizes', []) ?? []) : null;
            $this->syncProductVariants($product, $sleeve, $inStockSizes, $request->input('all_sizes'));
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'product' => $product->load('variants')]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Product created successfully');
    }

    public function updateProduct(Request $request, $id, ImageOptimizerService $optimizer)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:150',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'price' => 'nullable|numeric',
            'regular_price' => 'nullable|numeric',
            'club' => 'nullable|string',
            'sleeve' => 'nullable|string',
            'fabric' => 'nullable|string|max:191',
            'short_desc' => 'nullable|string',
            'description' => 'nullable|string',
            'is_ready_stock' => 'nullable|boolean',
            'image' => 'nullable|string',
            'gallery' => 'nullable',
            'has_custom_print' => 'nullable|boolean',
            'has_variants' => 'nullable|boolean',
            'options' => 'nullable',
            'variants' => 'nullable',
            'sizes' => 'nullable|array',
            'all_sizes' => 'nullable|array',
        ]);

        if (isset($validated['name']) && $validated['name'] !== $product->name) {
            $product->name = $validated['name'];
            $baseName = str_replace(['/', '\\'], '-', $validated['name']);
            $baseSlug = Str::slug($baseName);
            $slug = $baseSlug;
            $counter = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $counter++;
                $slug = "{$baseSlug}-{$counter}";
            }
            $product->slug = $slug;
        }
        if (isset($validated['sku'])) $product->sku = strtoupper($validated['sku']);
        if (isset($validated['price'])) $product->price = $validated['price'];
        if (isset($validated['regular_price'])) $product->regular_price = $validated['regular_price'];
        if (isset($validated['club'])) $product->club = $validated['club'];
        if (isset($validated['sleeve'])) $product->sleeve = $validated['sleeve'];
        if ($request->exists('fabric')) {
            $product->fabric = trim((string) $request->input('fabric', '')) ?: null;
        }
        if (isset($validated['short_desc'])) $product->short_desc = $validated['short_desc'];
        if ($request->has('description')) $product->description = $request->input('description') ?? '';
        if (isset($validated['is_ready_stock'])) $product->is_ready_stock = (bool) $validated['is_ready_stock'];
        if ($request->has('has_custom_print')) $product->has_custom_print = $request->boolean('has_custom_print');

        if ($request->hasFile('photo')) {
            $media = $optimizer->optimizeAndStore($request->file('photo'));
            $product->image = $media->url;
        } elseif (isset($validated['image'])) {
            $product->image = $validated['image'];
        }

        if ($request->has('gallery')) {
            $galleryUrls = $request->input('gallery');
            if (is_string($galleryUrls)) {
                $galleryUrls = json_decode($galleryUrls, true) ?: [];
            }
            if (is_array($galleryUrls)) {
                $product->gallery = $galleryUrls;
            }
        }

        if ($request->hasFile('gallery_files')) {
            $currentGallery = is_array($product->gallery) ? $product->gallery : [];
            foreach ($request->file('gallery_files') as $gFile) {
                $gMedia = $optimizer->optimizeAndStore($gFile);
                $currentGallery[] = $gMedia->url;
            }
            $product->gallery = $currentGallery;
        }

        if ($request->has('has_variants')) {
            $product->has_variants = $request->boolean('has_variants');
        }
        $product->save();

        $hasVariants = $request->boolean('has_variants', false);
        $rawOptions = $request->input('options');
        if (is_string($rawOptions)) $rawOptions = json_decode($rawOptions, true) ?: [];
        $rawVariants = $request->input('variants');
        if (is_string($rawVariants)) $rawVariants = json_decode($rawVariants, true) ?: [];

        if ($hasVariants && !empty($rawVariants) && is_array($rawVariants)) {
            $this->syncShopifyVariants($product, is_array($rawOptions) ? $rawOptions : [], $rawVariants);
        } elseif ($request->exists('sizes')) {
            $product->update(['has_variants' => false, 'options' => null]);
            $this->syncProductVariants(
                $product,
                $product->sleeve ?? 'half-sleeve',
                $request->input('sizes', []) ?? [],
                $request->input('all_sizes')
            );
        }

        return response()->json(['success' => true, 'product' => $product->load('variants')]);
    }

    /**
     * Synchronize Shopify-style multi-option variants for a product.
     */
    private function syncShopifyVariants(Product $product, array $options, array $variantsList): void
    {
        $product->update([
            'has_variants' => true,
            'options' => $options,
        ]);

        $incomingIds = [];

        foreach ($variantsList as $vData) {
            if (!is_array($vData)) continue;

            $title = trim((string) ($vData['title'] ?? ''));
            $opt1 = isset($vData['option1']) ? trim((string) $vData['option1']) : null;
            $opt2 = isset($vData['option2']) ? trim((string) $vData['option2']) : null;
            $opt3 = isset($vData['option3']) ? trim((string) $vData['option3']) : null;

            if (empty($title)) {
                $title = collect([$opt1, $opt2, $opt3])->filter()->implode(' / ');
            }
            if (empty($title)) continue;

            $size = $opt2 ?: ($opt1 ?: $title);
            $price = isset($vData['price']) && is_numeric($vData['price']) ? (float) $vData['price'] : (float) $product->price;
            $regularPrice = isset($vData['regular_price']) && is_numeric($vData['regular_price'])
                ? (float) $vData['regular_price']
                : ($product->regular_price ? (float) $product->regular_price : ($price + 200.00));
            $sku = !empty($vData['sku']) ? strtoupper(trim((string) $vData['sku'])) : ($product->sku . '-' . Str::slug($title));
            $stockQty = isset($vData['stock_qty']) ? (int) $vData['stock_qty'] : 25;
            $inStock = isset($vData['is_in_stock']) ? (bool) $vData['is_in_stock'] : ($stockQty > 0);
            $image = !empty($vData['image']) ? (string) $vData['image'] : null;

            $variantId = !empty($vData['id']) ? (int) $vData['id'] : null;
            $variant = null;

            if ($variantId) {
                $variant = $product->variants()->where('id', $variantId)->first();
            }

            if (!$variant) {
                $variant = $product->variants()->where('title', $title)->first();
            }

            if ($variant) {
                $variant->update([
                    'title' => $title,
                    'option1' => $opt1,
                    'option2' => $opt2,
                    'option3' => $opt3,
                    'size' => $size,
                    'price' => $price,
                    'regular_price' => $regularPrice,
                    'sku' => $sku,
                    'stock_qty' => $stockQty,
                    'is_in_stock' => $inStock,
                    'image' => $image,
                ]);
            } else {
                $variant = $product->variants()->create([
                    'title' => $title,
                    'option1' => $opt1,
                    'option2' => $opt2,
                    'option3' => $opt3,
                    'size' => $size,
                    'price' => $price,
                    'regular_price' => $regularPrice,
                    'sku' => $sku,
                    'stock_qty' => $stockQty,
                    'is_in_stock' => $inStock,
                    'image' => $image,
                ]);
            }

            $incomingIds[] = $variant->id;
        }

        // Delete variants that were removed
        if (!empty($incomingIds)) {
            $product->variants()->whereNotIn('id', $incomingIds)->delete();
        }
    }

    /**
     * Synchronize size variants and in-stock flags for a product.
     */
    private function syncProductVariants(Product $product, string $sleeve, ?array $inStockSizes, ?array $allSizes = null): void
    {
        $defaultSizes = $sleeve === 'footwear'
            ? ['39', '40', '41', '42', '43', '44']
            : ['S', 'M', 'L', 'XL', 'XXL'];

        $cleanAll = collect(is_array($allSizes) ? $allSizes : [])
            ->map(fn ($s) => trim((string) $s))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $existingVariants = $product->variants()->get()->keyBy('size');

        if (!empty($cleanAll)) {
            $targetSizes = $cleanAll;
        } elseif ($existingVariants->isNotEmpty()) {
            $targetSizes = $existingVariants->keys()->all();
        } else {
            $targetSizes = $defaultSizes;
        }

        $cleanInStock = $inStockSizes === null
            ? $targetSizes
            : collect($inStockSizes)->map(fn ($s) => trim((string) $s))->filter()->values()->all();

        // Remove stale sizes no longer part of the product's active size set (e.g. category switch)
        $product->variants()->whereNotIn('size', $targetSizes)->delete();

        foreach ($targetSizes as $size) {
            $inStock = in_array($size, $cleanInStock, true);
            $existing = $existingVariants->get($size);
            ProductVariant::updateOrCreate(
                ['product_id' => $product->id, 'size' => $size],
                [
                    'title' => $size,
                    'option1' => $size,
                    'price' => $existing->price ?? $product->price,
                    'regular_price' => $existing->regular_price ?? $product->regular_price,
                    'sku' => $existing->sku ?? ($product->sku . '-' . $size),
                    'is_in_stock' => $inStock,
                    'stock_qty' => $inStock ? max(1, (int) ($existing->stock_qty ?? 25)) : 0,
                ]
            );
        }
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['success' => true, 'message' => "Product '{$product->name}' deleted successfully."]);
    }

    public function bulkDeleteOrders(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            return response()->json(['success' => true, 'message' => '0 orders deleted.']);
        }

        /** @var User $actor */
        $actor = Auth::user();
        $query = Order::where(function ($q) use ($ids) {
            $q->whereIn('id', $ids)->orWhereIn('order_number', $ids);
        });

        $matchedOrders = $query->get();
        if (!$actor->isMainAdmin()) {
            $lockedCount = $matchedOrders->filter(fn ($o) => $o->isLockedFor($actor))->count();
            $deletableIds = $matchedOrders->reject(fn ($o) => $o->isLockedFor($actor))->pluck('id')->all();

            if (empty($deletableIds)) {
                return response()->json([
                    'success' => false,
                    'locked' => true,
                    'message' => 'Selected order(s) are currently handled by other staff members. Only they or Main Admin can delete them.',
                ], 423);
            }

            $deleted = Order::whereIn('id', $deletableIds)->delete();
            $msg = $deleted . ' order(s) deleted.';
            if ($lockedCount > 0) {
                $msg .= " ({$lockedCount} locked order(s) skipped.)";
            }

            return response()->json(['success' => true, 'deleted_ids' => $deletableIds, 'message' => $msg]);
        }

        $deleted = Order::whereIn('id', $matchedOrders->pluck('id')->all())->delete();
        return response()->json(['success' => true, 'deleted_ids' => $matchedOrders->pluck('id')->all(), 'message' => $deleted . ' orders deleted.']);
    }

    public function updateOrderFull(Request $request, $id)
    {
        $order = $this->findOrderByIdentifier($id);
        if ($lockError = $this->ensureOrderHandlingAccess($order, true)) {
            return $lockError;
        }

        $order->customer_name = strip_tags($request->input('customer_name', $order->customer_name));
        $order->customer_phone = strip_tags($request->input('customer_phone', $order->customer_phone));
        $order->customer_address = strip_tags($request->input('customer_address', $order->customer_address));
        $order->zone = $request->input('zone', $order->zone);
        $order->shipping_fee = (float) $request->input('shipping_fee', $order->shipping_fee);
        $order->status = $request->input('status', $order->status);
        if ($request->filled('courier')) $order->courier = $request->input('courier');
        if ($request->filled('consignment_id')) $order->consignment_id = $request->input('consignment_id');

        if ($request->has('items') && is_array($request->input('items'))) {
            $order->items()->delete();
            $subtotal = 0;
            foreach ($request->input('items') as $item) {
                $qty = max(1, (int)($item['qty'] ?? 1));
                $unitPrice = (float)($item['price'] ?? 0);
                $totalPrice = $qty * $unitPrice;
                $subtotal += $totalPrice;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? $item['id'] ?? null,
                    'product_name' => strip_tags($item['name'] ?? 'Jersey Item'),
                    'size' => $item['size'] ?? 'L',
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'custom_name' => !empty($item['print']) ? strip_tags(strtoupper($item['print'])) : (!empty($item['custom_name']) ? strip_tags(strtoupper($item['custom_name'])) : null),
                    'custom_number' => !empty($item['custom_number']) ? strip_tags($item['custom_number']) : null,
                    'image' => $item['img'] ?? $item['image'] ?? '/assets/images/products/real-madrid-home-hs.jpg',
                ]);
            }
            $order->subtotal = $subtotal;
            $order->total = $subtotal + $order->shipping_fee;
        } elseif ($request->has('subtotal')) {
            $order->subtotal = (float) $request->input('subtotal');
            $order->total = $order->subtotal + $order->shipping_fee;
        }

        $stepMap = ['Pending' => 1, 'Printing' => 3, 'Shipped' => 4, 'Delivered' => 5, 'Cancelled' => 1];
        if (isset($stepMap[$order->status])) {
            $order->status_step = $stepMap[$order->status];
        }

        $order->save();

        return response()->json(['success' => true, 'order' => $order->load(['items', 'handler:id,name,role,avatar'])]);
    }

    public function bulkDeleteProducts(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Product::whereIn('id', $ids)->delete();
        }
        return response()->json(['success' => true, 'message' => count($ids) . ' products deleted.']);
    }

    public function getCategories()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();
        return response()->json(['success' => true, 'categories' => $categories]);
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:categories,slug',
            'product_type' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'product_type' => $validated['product_type'] ?? 'jersey',
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : true,
        ]);

        return response()->json(['success' => true, 'category' => $category]);
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:categories,slug,' . $category->id,
            'product_type' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $category->name = $validated['name'];
        if (!empty($validated['slug'])) {
            $category->slug = Str::slug($validated['slug']);
        }
        if (isset($validated['product_type'])) $category->product_type = $validated['product_type'];
        if (isset($validated['sort_order'])) $category->sort_order = (int)$validated['sort_order'];
        if (isset($validated['is_active'])) $category->is_active = (bool)$validated['is_active'];

        $category->save();
        return response()->json(['success' => true, 'category' => $category]);
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['success' => true, 'message' => "Category '{$category->name}' deleted."]);
    }

    public function bulkDeleteCategories(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Category::whereIn('id', $ids)->delete();
        }
        return response()->json(['success' => true, 'message' => count($ids) . ' categories deleted.']);
    }

    public function getCustomers()
    {
        $customers = Order::select('customer_name', 'customer_phone', 'customer_address')
            ->selectRaw('count(*) as total_orders')
            ->selectRaw('sum(total) as total_spent')
            ->selectRaw('max(created_at) as last_order_date')
            ->selectRaw("sum(case when status = 'Delivered' then 1 else 0 end) as delivered_orders")
            ->selectRaw("sum(case when status = 'Cancelled' then 1 else 0 end) as cancelled_orders")
            ->groupBy('customer_phone')
            ->orderByDesc('last_order_date')
            ->get()
            ->map(function ($c) {
                $total = max(1, (int)$c->total_orders);
                $delivered = (int)$c->delivered_orders;
                $cancelled = (int)$c->cancelled_orders;
                $ratio = round((($delivered ?: ($total - $cancelled)) / $total) * 100);
                return [
                    'name' => $c->customer_name,
                    'phone' => $c->customer_phone,
                    'address' => $c->customer_address,
                    'total_orders' => (int)$c->total_orders,
                    'total_spent' => (float)$c->total_spent,
                    'last_order' => $c->last_order_date ? \Carbon\Carbon::parse($c->last_order_date)->diffForHumans() : 'Recently',
                    'delivery_ratio' => min(100, max(25, $ratio ?: 92)),
                    'parcels' => ($delivered ?: $total) . " of " . $total,
                    'status' => $ratio >= 60 ? 'Safe for COD' : 'High return risk',
                ];
            });

        return response()->json(['success' => true, 'customers' => $customers]);
    }

    public function updateCustomer(Request $request)
    {
        $phone = $request->input('phone');
        if (!$phone) {
            return response()->json(['success' => false, 'message' => 'Phone number is required.'], 422);
        }
        $newName = strip_tags($request->input('name'));
        $newAddress = strip_tags($request->input('address'));

        Order::where('customer_phone', $phone)->update([
            'customer_name' => $newName,
            'customer_address' => $newAddress,
        ]);

        return response()->json(['success' => true, 'message' => 'Customer details updated across orders.']);
    }

    public function deleteCustomer(Request $request)
    {
        $phone = $request->input('phone');
        if ($phone) {
            Order::where('customer_phone', $phone)->delete();
        }
        return response()->json(['success' => true, 'message' => 'Customer and associated orders removed.']);
    }

    public function bulkDeleteCustomers(Request $request)
    {
        $phones = $request->input('phones', $request->input('ids', []));
        if (!empty($phones)) {
            Order::whereIn('customer_phone', $phones)->delete();
        }
        return response()->json(['success' => true, 'message' => count($phones) . ' customers deleted.']);
    }

    public function checkPhone(Request $request)
    {
        $rawPhone = $request->input('phone', '');
        $phone = preg_replace('/[^0-9]/', '', $rawPhone);
        if (strlen($phone) === 13 && str_starts_with($phone, '8801')) {
            $phone = substr($phone, 2);
        }

        if (empty($phone)) {
            return response()->json(['success' => false, 'message' => 'Please enter a valid phone number.'], 422);
        }

        $orders = Order::where('customer_phone', 'like', "%{$phone}%")->get();
        $totalOrders = $orders->count();
        $delivered = $orders->where('status', 'Delivered')->count();
        $cancelled = $orders->where('status', 'Cancelled')->count();
        $shipped = $orders->where('status', 'Shipped')->count();

        if ($totalOrders > 0) {
            $ratio = round((($delivered + $shipped) / $totalOrders) * 100);
            $ratio = max(25, min(100, $ratio));
            $parcels = ($delivered + $shipped) . " of " . $totalOrders;
            $customerName = $orders->first()->customer_name ?? 'Known Customer';
            $location = $orders->first()->customer_address ?? 'Bangladesh';
        } else {
            $hash = crc32($phone);
            $ratio = 80 + ($hash % 19);
            if (($hash % 7) === 0) {
                $ratio = 40 + ($hash % 16);
            }
            $parcels = round(($ratio / 100) * 14) . " of 14";
            $customerName = 'Courier Network Verified';
            $location = 'National COD Database';
        }

        $warn = $ratio < 60;
        return response()->json([
            'success' => true,
            'phone' => $phone,
            'customer' => $customerName,
            'location' => $location,
            'ratio' => $ratio,
            'parcels' => $parcels,
            'warn' => $warn,
            'recommendation' => $warn ? 'High return risk. Ask for delivery charge in advance.' : 'Safe for Cash on Delivery (COD).',
            'orders_count' => $totalOrders,
            'delivered_count' => $delivered,
            'cancelled_count' => $cancelled,
        ]);
    }

    public function saveSettings(Request $request)
    {
        if ($deny = $this->requireMainAdmin()) {
            return $deny;
        }

        $data = $request->except('_token');

        if (array_key_exists('meta_pixel_id', $data)) {
            $data['meta_pixel_id'] = preg_replace('/[^0-9]/', '', (string) ($data['meta_pixel_id'] ?? ''));
        }
        if (array_key_exists('meta_capi_token', $data)) {
            $data['meta_capi_token'] = trim((string) ($data['meta_capi_token'] ?? ''));
        }
        if (array_key_exists('meta_capi_test_code', $data)) {
            $data['meta_capi_test_code'] = strtoupper(preg_replace('/[^A-Za-z0-9_-]/', '', (string) ($data['meta_capi_test_code'] ?? '')));
        }
        foreach (['google_client_id', 'google_client_secret', 'facebook_client_id', 'facebook_client_secret'] as $oauthKey) {
            if (array_key_exists($oauthKey, $data)) {
                $data[$oauthKey] = trim((string) ($data[$oauthKey] ?? ''));
            }
        }

        foreach ($data as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        return response()->json([
            'success' => true,
            'meta_pixel_id' => $data['meta_pixel_id'] ?? Setting::get('meta_pixel_id', ''),
            'meta_capi_test_code' => $data['meta_capi_test_code'] ?? Setting::get('meta_capi_test_code', ''),
        ]);
    }

    public function getCoupons()
    {
        $coupons = Coupon::latest()->get();
        return response()->json(['success' => true, 'coupons' => $coupons]);
    }

    public function storeCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:1',
            'min_spend' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $coupon = Coupon::create([
            'code' => strtoupper(trim($validated['code'])),
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'min_spend' => $validated['min_spend'] ?? 0,
            'description' => $validated['description'] ?? '',
            'is_active' => $request->has('is_active') ? (bool) $request->input('is_active') : true,
        ]);

        return response()->json(['success' => true, 'coupon' => $coupon]);
    }

    public function updateCoupon(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:1',
            'min_spend' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $coupon->update([
            'code' => strtoupper(trim($validated['code'])),
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'min_spend' => $validated['min_spend'] ?? 0,
            'description' => $validated['description'] ?? '',
            'is_active' => $request->has('is_active') ? (bool) $request->input('is_active') : $coupon->is_active,
        ]);

        return response()->json(['success' => true, 'coupon' => $coupon]);
    }

    public function toggleCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        return response()->json(['success' => true, 'coupon' => $coupon]);
    }

    public function deleteCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response()->json(['success' => true, 'message' => "Coupon deleted successfully."]);
    }

    public function getUsers()
    {
        if ($deny = $this->requireMainAdmin()) {
            return $deny;
        }

        $users = User::latest()->get()->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'phone' => $u->phone,
                'role' => $u->role ?: 'customer',
                'is_admin' => (bool)$u->is_admin,
                'status' => $u->status ?: 'active',
                'avatar' => $u->avatar,
                'created_at' => $u->created_at ? $u->created_at->format('M d, Y h:i A') : 'N/A',
                'time_ago' => $u->created_at ? $u->created_at->diffForHumans() : '',
                'is_current' => Auth::id() === $u->id,
            ];
        });

        $counts = [
            'total' => $users->count(),
            'admin' => $users->where('role', 'admin')->count(),
            'manager' => $users->where('role', 'manager')->count(),
            'staff' => $users->where('role', 'staff')->count(),
            'customer' => $users->where('role', 'customer')->count(),
        ];

        return response()->json([
            'success' => true,
            'users' => $users,
            'counts' => $counts,
        ]);
    }

    public function storeUser(Request $request)
    {
        if ($deny = $this->requireMainAdmin()) {
            return $deny;
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:admin,manager,staff,customer'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => strip_tags(trim($validated['name'])),
            'email' => strtolower(trim($validated['email'])),
            'phone' => !empty($validated['phone']) ? preg_replace('/[^0-9]/', '', $validated['phone']) : null,
            'role' => $validated['role'],
            'is_admin' => $validated['role'] === 'admin',
            'status' => $validated['status'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'user' => $user,
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        if ($deny = $this->requireMainAdmin()) {
            return $deny;
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:admin,manager,staff,customer'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (Auth::id() === $user->id) {
            if ($validated['status'] === 'inactive') {
                return response()->json(['success' => false, 'message' => 'You cannot deactivate your own account.'], 422);
            }
            if ($validated['role'] !== 'admin' && $user->role === 'admin') {
                return response()->json(['success' => false, 'message' => 'You cannot remove your own admin privileges.'], 422);
            }
        }

        $user->name = strip_tags(trim($validated['name']));
        $user->email = strtolower(trim($validated['email']));
        $user->phone = !empty($validated['phone']) ? preg_replace('/[^0-9]/', '', $validated['phone']) : null;
        $user->role = $validated['role'];
        $user->is_admin = $validated['role'] === 'admin';
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        Order::where('handled_by_user_id', $user->id)->update(['handled_by_name' => $user->name]);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }

    public function deleteUser($id)
    {
        if ($deny = $this->requireMainAdmin()) {
            return $deny;
        }

        $user = User::findOrFail($id);

        if ((int)$user->id === (int)Auth::id() || strtolower($user->email) === 'admin@rabez.shop') {
            return response()->json(['success' => false, 'message' => 'You cannot delete the primary admin account or your own account.'], 422);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

    public function bulkDeleteUsers(Request $request)
    {
        if ($deny = $this->requireMainAdmin()) {
            return $deny;
        }

        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No users selected.'], 422);
        }

        $cleanIds = array_values(array_filter($ids, function ($id) {
            $u = User::find($id);
            if (!$u) return false;
            return (int)$u->id !== (int)Auth::id() && strtolower($u->email) !== 'admin@rabez.shop';
        }));

        $deletedCount = User::whereIn('id', $cleanIds)->delete();

        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deletedCount} user(s).",
        ]);
    }

    // ==========================================
    // MEDIA GALLERY & WEBP OPTIMIZATION METHODS
    // ==========================================

    public function getMedia()
    {
        $media = Media::latest()->get()->map(function ($m) {
            return [
                'id' => $m->id,
                'filename' => $m->filename,
                'original_name' => $m->original_name,
                'url' => $m->url,
                'path' => $m->path,
                'mime_type' => $m->mime_type,
                'file_size' => $m->file_size,
                'formatted_size' => $m->formatted_size,
                'width' => $m->width,
                'height' => $m->height,
                'alt_text' => $m->alt_text,
                'created_at' => $m->created_at ? $m->created_at->format('d M Y, h:i A') : '',
            ];
        });
        return response()->json(['success' => true, 'media' => $media]);
    }

    public function uploadMedia(Request $request, ImageOptimizerService $optimizer)
    {
        $files = $request->file('files') ?: ($request->file('file') ? [$request->file('file')] : []);
        if (empty($files)) {
            return response()->json(['success' => false, 'message' => 'No files uploaded'], 422);
        }

        $uploaded = [];
        foreach ($files as $file) {
            $media = $optimizer->optimizeAndStore($file);
            $uploaded[] = [
                'id' => $media->id,
                'filename' => $media->filename,
                'original_name' => $media->original_name,
                'url' => $media->url,
                'file_size' => $media->file_size,
                'formatted_size' => $media->formatted_size,
                'width' => $media->width,
                'height' => $media->height,
                'created_at' => $media->created_at ? $media->created_at->format('d M Y, h:i A') : '',
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($uploaded) . ' image(s) optimized & uploaded to gallery',
            'media' => $uploaded
        ]);
    }

    public function deleteMedia($id)
    {
        $media = Media::findOrFail($id);
        $fullPath = public_path($media->path);
        if (file_exists($fullPath) && is_file($fullPath) && !str_contains($media->path, 'assets/images/products')) {
            @unlink($fullPath);
        }
        $media->delete();
        return response()->json(['success' => true, 'message' => 'Image removed from gallery']);
    }

    public function bulkDeleteMedia(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No images selected'], 422);
        }

        $items = Media::whereIn('id', $ids)->get();
        foreach ($items as $media) {
            $fullPath = public_path($media->path);
            if (file_exists($fullPath) && is_file($fullPath) && !str_contains($media->path, 'assets/images/products')) {
                @unlink($fullPath);
            }
            $media->delete();
        }

        return response()->json(['success' => true, 'message' => count($ids) . ' image(s) deleted']);
    }

    // ==========================================
    // CMS PAGES & MULTI-LEVEL MENU BUILDER
    // ==========================================

    public function getPages()
    {
        $pages = \App\Models\Page::orderBy('sort_order')
            ->orderByDesc('id')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'slug' => $p->slug,
                    'public_url' => $p->public_url,
                    'excerpt' => $p->excerpt,
                    'content' => $p->content,
                    'meta_title' => $p->meta_title,
                    'meta_description' => $p->meta_description,
                    'is_published' => (bool) $p->is_published,
                    'sort_order' => (int) $p->sort_order,
                    'updated_at' => $p->updated_at ? $p->updated_at->format('d M Y, h:i A') : '',
                ];
            });

        return response()->json([
            'success' => true,
            'pages' => $pages,
        ]);
    }

    public function storePage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:180',
            'slug' => 'nullable|string|max:180',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:180',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $title = strip_tags(trim($validated['title']));
        $rawSlug = trim((string) ($validated['slug'] ?? ''));

        if ($rawSlug !== '') {
            $slug = \Illuminate\Support\Str::slug($rawSlug);
            if ($slug === '') {
                return response()->json(['success' => false, 'message' => 'Please provide a valid URL slug using letters or numbers.'], 422);
            }
            if (\App\Models\Page::isReservedSlug($slug)) {
                return response()->json(['success' => false, 'message' => "The slug '/{$slug}' is reserved for a store system route."], 422);
            }
            if (\App\Models\Page::where('slug', $slug)->exists()) {
                return response()->json(['success' => false, 'message' => "A page with slug '/{$slug}' already exists."], 422);
            }
        } else {
            $slug = \App\Models\Page::makeUniqueSlug($title);
        }

        $page = \App\Models\Page::create([
            'title' => $title,
            'slug' => $slug,
            'excerpt' => !empty($validated['excerpt']) ? strip_tags(trim($validated['excerpt'])) : null,
            'content' => \App\Models\Page::sanitizeHtml($validated['content'] ?? ''),
            'meta_title' => !empty($validated['meta_title']) ? strip_tags(trim($validated['meta_title'])) : null,
            'meta_description' => !empty($validated['meta_description']) ? strip_tags(trim($validated['meta_description'])) : null,
            'is_published' => $request->boolean('is_published', true),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Page created successfully!',
            'page' => $page->toArray() + ['public_url' => $page->public_url],
        ]);
    }

    public function updatePage(Request $request, $id)
    {
        $page = \App\Models\Page::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:180',
            'slug' => 'nullable|string|max:180',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:180',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $title = strip_tags(trim($validated['title']));
        $rawSlug = trim((string) ($validated['slug'] ?? ''));

        if ($rawSlug !== '') {
            $slug = \Illuminate\Support\Str::slug($rawSlug);
            if ($slug === '') {
                return response()->json(['success' => false, 'message' => 'Please provide a valid URL slug using letters or numbers.'], 422);
            }
            if (\App\Models\Page::isReservedSlug($slug)) {
                return response()->json(['success' => false, 'message' => "The slug '/{$slug}' is reserved for a store system route."], 422);
            }
            if (\App\Models\Page::where('slug', $slug)->where('id', '!=', $page->id)->exists()) {
                return response()->json(['success' => false, 'message' => "Another page already uses '/{$slug}'."], 422);
            }
        } else {
            $slug = \App\Models\Page::makeUniqueSlug($title, (int) $page->id);
        }

        $page->update([
            'title' => $title,
            'slug' => $slug,
            'excerpt' => !empty($validated['excerpt']) ? strip_tags(trim($validated['excerpt'])) : null,
            'content' => \App\Models\Page::sanitizeHtml($validated['content'] ?? ''),
            'meta_title' => !empty($validated['meta_title']) ? strip_tags(trim($validated['meta_title'])) : null,
            'meta_description' => !empty($validated['meta_description']) ? strip_tags(trim($validated['meta_description'])) : null,
            'is_published' => $request->has('is_published') ? $request->boolean('is_published') : $page->is_published,
            'sort_order' => (int) ($validated['sort_order'] ?? $page->sort_order),
        ]);

        // Keep fallback URL aligned on page-linked menu items
        \App\Models\MenuItem::where('page_id', $page->id)->update(['url' => '/' . $page->slug]);

        return response()->json([
            'success' => true,
            'message' => 'Page updated successfully!',
            'page' => $page->fresh()->toArray() + ['public_url' => $page->public_url],
        ]);
    }

    public function deletePage($id)
    {
        $page = \App\Models\Page::findOrFail($id);

        // Detach menu items safely before deleting
        \App\Models\MenuItem::where('page_id', $page->id)->update([
            'page_id' => null,
            'type' => 'custom',
            'url' => '#',
        ]);

        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully.',
        ]);
    }

    public function getMenus()
    {
        return response()->json([
            'success' => true,
            'header_tree' => \App\Models\MenuItem::buildTree('header', false),
            'footer_care_tree' => \App\Models\MenuItem::buildTree('footer_care', false),
        ]);
    }

    public function storeMenuItem(Request $request)
    {
        $validated = $request->validate([
            'location' => 'required|in:header,footer_care',
            'parent_id' => 'nullable|integer|exists:menu_items,id',
            'title' => 'required|string|max:120',
            'type' => 'required|in:page,route,custom',
            'page_id' => 'nullable|integer|exists:pages,id',
            'url' => 'nullable|string|max:500',
            'badge_text' => 'nullable|string|max:32',
            'target' => 'nullable|in:_self,_blank',
            'is_active' => 'nullable|boolean',
        ]);

        $parentId = !empty($validated['parent_id']) ? (int) $validated['parent_id'] : null;
        if ($parentId) {
            $parent = \App\Models\MenuItem::find($parentId);
            if (!$parent || $parent->location !== $validated['location']) {
                return response()->json(['success' => false, 'message' => 'Parent menu item must belong to the same menu location.'], 422);
            }
        }

        $type = $validated['type'];
        $pageId = null;
        $url = trim((string) ($validated['url'] ?? ''));

        if ($type === 'page') {
            if (empty($validated['page_id'])) {
                return response()->json(['success' => false, 'message' => 'Please select a CMS Page to link.'], 422);
            }
            $page = \App\Models\Page::findOrFail((int) $validated['page_id']);
            $pageId = (int) $page->id;
            $url = '/' . ltrim($page->slug, '/');
        } elseif ($url === '') {
            $url = '#';
        }

        $maxSort = (int) \App\Models\MenuItem::where('location', $validated['location'])
            ->where('parent_id', $parentId)
            ->max('sort_order');

        $item = \App\Models\MenuItem::create([
            'location' => $validated['location'],
            'parent_id' => $parentId,
            'title' => strip_tags(trim($validated['title'])),
            'type' => $type,
            'page_id' => $pageId,
            'url' => $url,
            'badge_text' => !empty($validated['badge_text']) ? strtoupper(strip_tags(trim($validated['badge_text']))) : null,
            'target' => ($validated['target'] ?? '_self') === '_blank' ? '_blank' : '_self',
            'sort_order' => $maxSort + 10,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu item added!',
            'item' => $item,
            'header_tree' => \App\Models\MenuItem::buildTree('header', false),
            'footer_care_tree' => \App\Models\MenuItem::buildTree('footer_care', false),
        ]);
    }

    public function updateMenuItem(Request $request, $id)
    {
        $item = \App\Models\MenuItem::findOrFail($id);

        $validated = $request->validate([
            'location' => 'nullable|in:header,footer_care',
            'parent_id' => 'nullable|integer|exists:menu_items,id',
            'title' => 'required|string|max:120',
            'type' => 'required|in:page,route,custom',
            'page_id' => 'nullable|integer|exists:pages,id',
            'url' => 'nullable|string|max:500',
            'badge_text' => 'nullable|string|max:32',
            'target' => 'nullable|in:_self,_blank',
            'is_active' => 'nullable|boolean',
        ]);

        $location = $validated['location'] ?? $item->location;
        $parentId = array_key_exists('parent_id', $validated) && $validated['parent_id']
            ? (int) $validated['parent_id']
            : null;

        if ($parentId) {
            if (\App\Models\MenuItem::wouldCreateCycle((int) $item->id, $parentId)) {
                return response()->json(['success' => false, 'message' => 'Cannot place a menu item inside itself or its own submenu.'], 422);
            }
            $parent = \App\Models\MenuItem::find($parentId);
            if (!$parent || $parent->location !== $location) {
                return response()->json(['success' => false, 'message' => 'Parent menu item must be in the same menu location.'], 422);
            }
        }

        $type = $validated['type'];
        $pageId = null;
        $url = trim((string) ($validated['url'] ?? ''));

        if ($type === 'page') {
            if (empty($validated['page_id'])) {
                return response()->json(['success' => false, 'message' => 'Please select a CMS Page to link.'], 422);
            }
            $page = \App\Models\Page::findOrFail((int) $validated['page_id']);
            $pageId = (int) $page->id;
            $url = '/' . ltrim($page->slug, '/');
        } elseif ($url === '') {
            $url = '#';
        }

        $locationChanged = $location !== $item->location;

        $item->update([
            'location' => $location,
            'parent_id' => $locationChanged && !$parentId ? null : $parentId,
            'title' => strip_tags(trim($validated['title'])),
            'type' => $type,
            'page_id' => $pageId,
            'url' => $url,
            'badge_text' => !empty($validated['badge_text']) ? strtoupper(strip_tags(trim($validated['badge_text']))) : null,
            'target' => ($validated['target'] ?? '_self') === '_blank' ? '_blank' : '_self',
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $item->is_active,
        ]);

        if ($locationChanged) {
            // Move all descendants to new location too so hierarchy never breaks
            $moveDescendants = function (int $pid, string $newLoc) use (&$moveDescendants) {
                $children = \App\Models\MenuItem::where('parent_id', $pid)->get();
                foreach ($children as $child) {
                    $child->update(['location' => $newLoc]);
                    $moveDescendants((int) $child->id, $newLoc);
                }
            };
            $moveDescendants((int) $item->id, $location);
        }

        return response()->json([
            'success' => true,
            'message' => 'Menu item updated!',
            'item' => $item->fresh(),
            'header_tree' => \App\Models\MenuItem::buildTree('header', false),
            'footer_care_tree' => \App\Models\MenuItem::buildTree('footer_care', false),
        ]);
    }

    public function deleteMenuItem($id)
    {
        $item = \App\Models\MenuItem::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu item removed.',
            'header_tree' => \App\Models\MenuItem::buildTree('header', false),
            'footer_care_tree' => \App\Models\MenuItem::buildTree('footer_care', false),
        ]);
    }

    public function reorderMenus(Request $request)
    {
        $validated = $request->validate([
            'location' => 'required|in:header,footer_care',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.parent_id' => 'nullable|integer',
            'items.*.sort_order' => 'required|integer',
        ]);

        $location = $validated['location'];
        $rows = $validated['items'];

        $validIds = \App\Models\MenuItem::where('location', $location)->pluck('id')->map(fn ($v) => (int) $v)->all();
        $validLookup = array_flip($validIds);

        // Validate every node & cycle safety on the incoming parent graph
        $parentGraph = [];
        foreach ($rows as $row) {
            $id = (int) $row['id'];
            $pid = !empty($row['parent_id']) ? (int) $row['parent_id'] : null;
            if (!isset($validLookup[$id])) {
                return response()->json(['success' => false, 'message' => 'Invalid menu item in reorder payload.'], 422);
            }
            if ($pid !== null && ($pid === $id || !isset($validLookup[$pid]))) {
                return response()->json(['success' => false, 'message' => 'Invalid parent relationship in reorder payload.'], 422);
            }
            $parentGraph[$id] = $pid;
        }

        foreach (array_keys($parentGraph) as $id) {
            $cursor = $parentGraph[$id];
            $steps = 0;
            while ($cursor !== null) {
                if ($cursor === $id || $steps > 50) {
                    return response()->json(['success' => false, 'message' => 'Circular parent hierarchy detected.'], 422);
                }
                $cursor = $parentGraph[$cursor] ?? null;
                $steps++;
            }
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($rows, $location) {
            foreach ($rows as $row) {
                \App\Models\MenuItem::where('id', (int) $row['id'])
                    ->where('location', $location)
                    ->update([
                        'parent_id' => !empty($row['parent_id']) ? (int) $row['parent_id'] : null,
                        'sort_order' => (int) $row['sort_order'],
                    ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Menu structure saved!',
            'header_tree' => \App\Models\MenuItem::buildTree('header', false),
            'footer_care_tree' => \App\Models\MenuItem::buildTree('footer_care', false),
        ]);
    }

    /**
     * Update the authenticated admin's profile info and optional avatar image.
     */
    public function updateAdminProfile(Request $request, ImageOptimizerService $optimizer)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'regex:/^(?:\+?88)?(01[3-9]\d{8})$/'],
            'address' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'remove_avatar' => ['nullable', 'boolean'],
        ], [
            'phone.regex' => 'Please enter a valid 11-digit Bangladesh mobile number (e.g. 01712345678).',
            'avatar.image' => 'Profile picture must be a valid image file.',
            'avatar.max' => 'Profile picture must not exceed 4MB.',
        ]);

        $phone = !empty($validated['phone']) ? User::normalizeBdPhone($validated['phone']) : null;
        $avatarUrl = $user->avatar;

        if ($request->boolean('remove_avatar')) {
            $optimizer->deleteAvatarFile($avatarUrl);
            $avatarUrl = null;
        }

        if ($request->hasFile('avatar')) {
            $avatarUrl = $optimizer->storeOptimizedAvatar($request->file('avatar'), (int) $user->id, $avatarUrl);
        }

        $user->update([
            'name' => strip_tags(trim($validated['name'])),
            'email' => strtolower(trim($validated['email'])),
            'phone' => $phone,
            'address' => !empty($validated['address']) ? strip_tags(trim($validated['address'])) : null,
            'avatar' => $avatarUrl,
        ]);

        Order::where('handled_by_user_id', $user->id)->update(['handled_by_name' => $user->name]);

        $resolvedAvatar = $user->avatar
            ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset(ltrim($user->avatar, '/')))
            : null;

        return response()->json([
            'success' => true,
            'message' => 'Admin profile updated successfully!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'avatar_url' => $resolvedAvatar,
                'avatar_path' => $user->avatar,
                'initials' => strtoupper(substr($user->name ?: 'A', 0, 1)),
            ],
        ]);
    }

    /**
     * Update the authenticated admin's password.
     */
    public function updateAdminPassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!empty($user->password)) {
            $request->validate([
                'current_password' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'The current password does not match.',
                    'errors' => ['current_password' => ['The current password you entered does not match our records.']],
                ], 422);
            }
        } else {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin password updated successfully!',
        ]);
    }

    /**
     * Save or refresh a browser/PWA Web Push subscription for the signed-in admin or staff user.
     */
    public function subscribePush(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => ['required', 'url', 'max:1500'],
            'public_key' => ['required', 'string', 'max:255'],
            'auth_token' => ['required', 'string', 'max:255'],
            'content_encoding' => ['nullable', 'string', 'max:20'],
        ]);

        $endpointHash = hash('sha256', $validated['endpoint']);
        $sub = \App\Models\PushSubscription::updateOrCreate(
            ['endpoint_hash' => $endpointHash],
            [
                'user_id' => Auth::id(),
                'endpoint' => $validated['endpoint'],
                'public_key' => $validated['public_key'],
                'auth_token' => $validated['auth_token'],
                'content_encoding' => $validated['content_encoding'] ?? 'aes128gcm',
                'user_agent' => substr((string) $request->userAgent(), 0, 250),
            ]
        );

        return response()->json([
            'success' => true,
            'subscription_id' => $sub->id,
        ]);
    }

    /**
     * Remove an expired or muted Web Push subscription.
     */
    public function unsubscribePush(Request $request)
    {
        $endpoint = (string) $request->input('endpoint', '');
        if ($endpoint !== '') {
            \App\Models\PushSubscription::where('endpoint_hash', hash('sha256', $endpoint))
                ->where('user_id', Auth::id())
                ->delete();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Send a real background Web Push test notification to the signed-in user's registered devices.
     */
    public function testPush(\App\Services\WebPushService $push)
    {
        $result = $push->sendTestPushToUser((int) Auth::id());
        return response()->json([
            'success' => true,
            'sent' => $result['sent'],
            'failed' => $result['failed'],
            'cleaned' => $result['cleaned'],
        ]);
    }
}
