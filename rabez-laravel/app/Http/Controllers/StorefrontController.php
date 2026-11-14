<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StorefrontController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('variants')->where('is_featured', true)->take(8)->get();
        $hoodies = Product::with('variants')->where('sleeve', 'winter-hoodie')->get();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('storefront.index', compact('featuredProducts', 'hoodies', 'categories', 'settings'));
    }

    public function shop(Request $request)
    {
        $query = Product::with('variants');

        if ($sleeve = $request->get('sleeve')) {
            if ($sleeve !== 'all') {
                $query->where('sleeve', $sleeve);
            }
        }

        if ($club = $request->get('club')) {
            if ($club !== 'all') {
                $query->where('club', $club);
            }
        }

        $products = $query->orderBy('is_featured', 'desc')->get();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('storefront.shop', compact('products', 'categories', 'settings'));
    }

    public function product($slug)
    {
        $cleanSlug = trim(str_replace('.html', '', $slug));

        $product = null;

        // 1. Direct ID lookup if numeric
        if (is_numeric($cleanSlug)) {
            $product = Product::with(['variants', 'category'])->find((int) $cleanSlug);
        }

        // 2. Exact slug match
        if (!$product) {
            $product = Product::with(['variants', 'category'])->where('slug', $cleanSlug)->first();
        }

        // 3. Exact SKU match
        if (!$product) {
            $product = Product::with(['variants', 'category'])->where('sku', strtoupper($cleanSlug))->first();
        }

        // 4. Club or keyword match from slug tokens (e.g. "real-madrid", "barcelona", "arsenal")
        if (!$product) {
            if (str_contains($cleanSlug, 'real-madrid')) {
                $product = Product::with(['variants', 'category'])
                    ->where('club', 'real-madrid')
                    ->where('sleeve', str_contains($cleanSlug, 'away') ? 'full-sleeve' : (str_contains($cleanSlug, '3rd') ? 'half-sleeve' : 'half-sleeve'))
                    ->first();
            } elseif (str_contains($cleanSlug, 'barcelona')) {
                $product = Product::with(['variants', 'category'])->where('club', 'barcelona')->first();
            } elseif (str_contains($cleanSlug, 'arsenal')) {
                $product = Product::with(['variants', 'category'])->where('club', 'arsenal')->first();
            } elseif (str_contains($cleanSlug, 'manchester') || str_contains($cleanSlug, 'man-utd')) {
                $product = Product::with(['variants', 'category'])->where('club', 'man-utd')->first();
            } elseif (str_contains($cleanSlug, 'argentina')) {
                $product = Product::with(['variants', 'category'])->where('club', 'argentina')->first();
            } elseif (str_contains($cleanSlug, 'bangladesh')) {
                $product = Product::with(['variants', 'category'])->where('club', 'bangladesh')->first();
            }
        }

        // 5. Broad name search
        if (!$product) {
            $nameSearch = str_replace('-', ' ', $cleanSlug);
            $product = Product::with(['variants', 'category'])
                ->where('name', 'LIKE', "%{$nameSearch}%")
                ->orWhere('slug', 'LIKE', "%{$cleanSlug}%")
                ->first();
        }

        // 6. Safe fallback
        if (!$product) {
            $product = Product::with(['variants', 'category'])->find(19) ?: Product::with(['variants', 'category'])->firstOrFail();
        }

        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                if ($product->club && $product->club !== 'all') {
                    $q->where('club', $product->club);
                }
                if ($product->sleeve) {
                    $q->orWhere('sleeve', $product->sleeve);
                }
            })
            ->take(4)
            ->get();

        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::where('id', '!=', $product->id)->take(4)->get();
        }

        $settings = Setting::pluck('value', 'key')->toArray();

        return view('storefront.product', compact('product', 'relatedProducts', 'settings'));
    }

    public function checkout()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('storefront.checkout', compact('settings'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => ['required', 'string', 'regex:/^(?:\+?88)?(01[3-9]\d{8})$/'],
            'customer_address' => 'required|string|max:255',
            'shipping_zone' => 'nullable|integer',
            'cart_items' => 'required|array|min:1',
            'cart_items.*.id' => 'required|integer',
            'cart_items.*.name' => 'required|string',
            'cart_items.*.size' => 'required|string',
            'cart_items.*.qty' => 'required|integer|min:1',
            'cart_items.*.price' => 'required|numeric',
            'cart_items.*.custom_name' => 'nullable|string|max:50',
            'cart_items.*.custom_number' => 'nullable|string|max:10',
        ], [
            'customer_phone.regex' => 'অনুগ্রহ করে সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (যেমন: 01712345678)',
        ]);

        // Security: Normalize BD Phone number
        $phone = preg_replace('/[^0-9]/', '', $validated['customer_phone']);
        if (strlen($phone) === 13 && str_starts_with($phone, '8801')) {
            $phone = substr($phone, 2);
        }

        $shippingMode = Setting::get('shipping_mode', 'flat');
        $flatFee = floatval(Setting::get('shipping_flat_fee', 130));
        $insideFee = floatval(Setting::get('shipping_inside_dhaka', 70));
        $outsideFee = floatval(Setting::get('shipping_outside_dhaka', 130));

        if ($shippingMode === 'flat') {
            $shippingFee = $flatFee;
            $zoneLabel = 'All Over Bangladesh (৳' . intval($flatFee) . ')';
        } else {
            $reqZone = floatval($validated['shipping_zone'] ?? $insideFee);
            $shippingFee = ($reqZone == $outsideFee) ? $outsideFee : $insideFee;
            $zoneLabel = ($shippingFee == $insideFee) ? 'Inside Dhaka (৳' . intval($insideFee) . ')' : 'Outside Dhaka (৳' . intval($outsideFee) . ')';
        }

        $subtotal = 0;

        foreach ($validated['cart_items'] as $item) {
            $subtotal += ($item['price'] * $item['qty']);
        }

        $couponCode = strtoupper(trim($request->input('coupon_code', '')));
        $discount = 0;
        if (!empty($couponCode)) {
            $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
            if ($coupon) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $orderNumber = 'RBZ-' . rand(10030, 99999);
        $eventId = 'rbz_pur_' . time() . '_' . rand(1000, 9999);

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => strip_tags(trim($validated['customer_name'])),
            'customer_phone' => $phone,
            'customer_address' => strip_tags(trim($validated['customer_address'])),
            'zone' => $zoneLabel,
            'shipping_fee' => $shippingFee,
            'total' => max(0, ($subtotal - $discount)) + $shippingFee,
            'subtotal' => $subtotal,
            'status' => 'Pending',
            'status_step' => 1,
            'courier' => 'Steadfast Courier',
            'consignment_id' => 'Pending Dispatch',
            'event_id' => $eventId,
        ]);

        foreach ($validated['cart_items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'] ?? null,
                'product_name' => strip_tags($item['name']),
                'size' => $item['size'],
                'qty' => $item['qty'],
                'unit_price' => $item['price'],
                'total_price' => $item['price'] * $item['qty'],
                'custom_name' => !empty($item['custom_name']) ? strip_tags(strtoupper(trim($item['custom_name']))) : null,
                'custom_number' => !empty($item['custom_number']) ? strip_tags(trim($item['custom_number'])) : null,
                'image' => $item['image'] ?? '/assets/images/products/real-madrid-home-hs.jpg',
            ]);
        }

        return response()->json([
            'success' => true,
            'order_id' => $order->order_number,
            'total' => $order->total,
            'event_id' => $eventId,
            'redirect_url' => route('track-order', ['order' => $order->order_number]),
        ]);
    }

    public function trackOrder(Request $request)
    {
        $query = $request->get('order', '');
        $order = null;

        if (!empty($query)) {
            $clean = strtoupper(trim($query));
            $order = Order::with('items')
                ->where('order_number', $clean)
                ->orWhere('customer_phone', $clean)
                ->latest()
                ->first();
        }

        $settings = Setting::pluck('value', 'key')->toArray();

        return view('storefront.track-order', compact('order', 'query', 'settings'));
    }

    public function applyCoupon(Request $request)
    {
        $code = strtoupper(trim($request->input('code', '')));
        $subtotal = floatval($request->input('subtotal', 0));

        if (empty($code)) {
            return response()->json(['success' => false, 'message' => 'Please enter a coupon code.']);
        }

        if (!Coupon::where('is_active', true)->exists()) {
            return response()->json(['success' => false, 'message' => 'No active coupons are currently available.']);
        }

        $coupon = Coupon::where('code', $code)->first();
        if (!$coupon) {
            return response()->json(['success' => false, 'message' => "Coupon code '{$code}' is invalid."]);
        }

        if (!$coupon->is_active) {
            return response()->json(['success' => false, 'message' => "Coupon '{$code}' is currently inactive or expired."]);
        }

        if ($coupon->min_spend > 0 && $subtotal < $coupon->min_spend) {
            return response()->json([
                'success' => false,
                'message' => "Minimum order of ৳" . number_format($coupon->min_spend) . " required for coupon '{$code}'."
            ]);
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return response()->json([
            'success' => true,
            'code' => $coupon->code,
            'discount' => $discount,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'description' => $coupon->description,
            'message' => "Coupon '{$coupon->code}' applied successfully! You saved ৳" . number_format($discount) . "."
        ]);
    }
}

