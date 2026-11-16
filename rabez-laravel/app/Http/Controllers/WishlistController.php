<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class WishlistController extends Controller
{
    /**
     * Render the Pro E-Commerce Wishlist page with saved/shared items and trending jerseys.
     */
    public function index(Request $request)
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        $rawShared = array_values(array_unique(array_filter(
            array_map('intval', explode(',', (string) $request->query('items', ''))),
            fn (int $id): bool => $id > 0
        )));

        $sharedProductIds = [];
        if (!empty($rawShared)) {
            $validLookup = array_flip(Product::whereIn('id', $rawShared)->pluck('id')->all());
            $sharedProductIds = array_values(array_filter($rawShared, fn (int $id): bool => isset($validLookup[$id])));
        }

        $savedWishlistIds = [];
        $savedWishlistSizes = [];
        if (Auth::check() && Schema::hasTable('wishlist_items')) {
            $savedItems = WishlistItem::where('user_id', Auth::id())
                ->orderByDesc('updated_at')
                ->get();

            $savedWishlistIds = $savedItems->pluck('product_id')
                ->map(fn ($id): int => (int) $id)
                ->values()
                ->all();

            $savedWishlistSizes = $savedItems
                ->filter(fn (WishlistItem $item): bool => !empty($item->preferred_size))
                ->mapWithKeys(fn (WishlistItem $item): array => [(int) $item->product_id => (string) $item->preferred_size])
                ->all();
        }

        $trendingProducts = Product::with('variants')
            ->where('is_featured', true)
            ->take(4)
            ->get();

        if ($trendingProducts->isEmpty()) {
            $trendingProducts = Product::with('variants')->latest('id')->take(4)->get();
        }

        return view('storefront.wishlist', compact(
            'settings',
            'savedWishlistIds',
            'savedWishlistSizes',
            'sharedProductIds',
            'trendingProducts'
        ));
    }

    /**
     * Sync localStorage wishlist product IDs and preferred sizes with DB when signed in.
     */
    public function sync(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => ['nullable', 'array', 'max:200'],
            'product_ids.*' => ['integer', 'min:1'],
            'sizes' => ['nullable', 'array'],
            'sizes.*' => ['nullable', 'string', 'max:20'],
        ]);

        $incomingIds = array_values(array_unique(array_map('intval', $validated['product_ids'] ?? [])));
        $validLookup = !empty($incomingIds)
            ? array_flip(Product::whereIn('id', $incomingIds)->pluck('id')->all())
            : [];
        $validIds = array_values(array_filter($incomingIds, fn (int $id): bool => isset($validLookup[$id])));

        $incomingSizes = [];
        foreach (($validated['sizes'] ?? []) as $pid => $size) {
            $intId = (int) $pid;
            $cleanSize = trim((string) ($size ?? ''));
            if ($intId > 0 && $cleanSize !== '' && isset($validLookup[$intId])) {
                $incomingSizes[$intId] = mb_substr($cleanSize, 0, 20);
            }
        }

        if (Auth::check() && Schema::hasTable('wishlist_items')) {
            $userId = Auth::id();
            foreach ($validIds as $pid) {
                if (isset($incomingSizes[$pid])) {
                    WishlistItem::updateOrCreate(
                        ['user_id' => $userId, 'product_id' => $pid],
                        ['preferred_size' => $incomingSizes[$pid]]
                    );
                } else {
                    WishlistItem::firstOrCreate([
                        'user_id' => $userId,
                        'product_id' => $pid,
                    ]);
                }
            }

            $allItems = WishlistItem::where('user_id', $userId)
                ->orderByDesc('updated_at')
                ->get();

            $mergedIds = $allItems->pluck('product_id')->map(fn ($id): int => (int) $id)->values()->all();
            $mergedSizes = $allItems
                ->filter(fn (WishlistItem $item): bool => !empty($item->preferred_size))
                ->mapWithKeys(fn (WishlistItem $item): array => [(int) $item->product_id => (string) $item->preferred_size])
                ->all();

            return response()->json([
                'success' => true,
                'product_ids' => $mergedIds,
                'sizes' => (object) $mergedSizes,
                'count' => count($mergedIds),
            ]);
        }

        return response()->json([
            'success' => true,
            'guest' => true,
            'product_ids' => $validIds,
            'sizes' => (object) $incomingSizes,
            'count' => count($validIds),
        ]);
    }

    /**
     * Toggle a product in the authenticated customer's wishlist or update preferred size.
     */
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'size' => ['nullable', 'string', 'max:20'],
            'mode' => ['nullable', 'string', 'in:toggle,save_size'],
        ]);

        $productId = (int) $validated['product_id'];
        $size = isset($validated['size']) && trim((string) $validated['size']) !== ''
            ? mb_substr(trim((string) $validated['size']), 0, 20)
            : null;
        $mode = $validated['mode'] ?? 'toggle';

        if (!Auth::check() || !Schema::hasTable('wishlist_items')) {
            return response()->json([
                'success' => true,
                'guest' => true,
            ]);
        }

        $userId = Auth::id();
        $existing = WishlistItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($mode === 'save_size') {
            if ($existing) {
                $existing->update(['preferred_size' => $size]);
                $action = 'updated';
            } else {
                WishlistItem::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'preferred_size' => $size,
                ]);
                $action = 'added';
            }
        } elseif ($existing) {
            $existing->delete();
            $action = 'removed';
        } else {
            WishlistItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'preferred_size' => $size,
            ]);
            $action = 'added';
        }

        $allItems = WishlistItem::where('user_id', $userId)
            ->orderByDesc('updated_at')
            ->get();

        $ids = $allItems->pluck('product_id')->map(fn ($id): int => (int) $id)->values()->all();
        $sizes = $allItems
            ->filter(fn (WishlistItem $item): bool => !empty($item->preferred_size))
            ->mapWithKeys(fn (WishlistItem $item): array => [(int) $item->product_id => (string) $item->preferred_size])
            ->all();

        return response()->json([
            'success' => true,
            'action' => $action,
            'product_ids' => $ids,
            'sizes' => (object) $sizes,
            'count' => count($ids),
        ]);
    }

    /**
     * Clear all wishlist items for the authenticated user.
     */
    public function clear()
    {
        if (Auth::check() && Schema::hasTable('wishlist_items')) {
            WishlistItem::where('user_id', Auth::id())->delete();
        }

        return response()->json([
            'success' => true,
            'product_ids' => [],
            'count' => 0,
        ]);
    }
}
