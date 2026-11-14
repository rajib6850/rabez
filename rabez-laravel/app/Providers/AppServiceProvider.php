<?php

namespace App\Providers;

use App\Models\Coupon;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.storefront', 'storefront.*'], function ($view) {
            static $cached = null;

            if ($cached === null) {
                $hasActiveCoupons = false;
                $customPrintFee = 250;
                $storefrontProducts = collect();
                $storefrontProductsJs = [];
                $headerMenuTree = [];
                $footerCareMenu = [];
                $publishedPages = collect();

                try {
                    if (Schema::hasTable('coupons')) {
                        $hasActiveCoupons = Coupon::where('is_active', true)->exists();
                    }
                    if (Schema::hasTable('settings')) {
                        $feeVal = Setting::where('key', 'custom_print_fee')->value('value');
                        if ($feeVal !== null && is_numeric($feeVal)) {
                            $customPrintFee = max(0, intval($feeVal));
                        }
                    }
                    if (Schema::hasTable('menu_items')) {
                        $headerMenuTree = MenuItem::buildTree('header', true);
                        $footerCareMenu = MenuItem::buildTree('footer_care', true);
                    }
                    if (Schema::hasTable('pages')) {
                        $publishedPages = Page::published()->orderBy('sort_order')->orderBy('id')->get(['id', 'title', 'slug']);
                    }
                    if (Schema::hasTable('products')) {
                        $sleeveLabels = [
                            'half-sleeve' => 'Half Sleeve',
                            'full-sleeve' => 'Full Sleeve',
                            'winter-hoodie' => 'Winter Hoodie',
                            'retro' => 'Retro',
                            'footwear' => 'Boots',
                            'tracksuit' => 'Tracksuit',
                        ];

                        $storefrontProducts = Product::with('variants')->latest('id')->get();
                        $storefrontProductsJs = $storefrontProducts->map(function (Product $p) use ($sleeveLabels) {
                            $variants = $p->variants ?? collect();
                            $inStock = $variants->where('is_in_stock', true)->pluck('size')->values()->all();
                            $allSizes = $variants->pluck('size')->values()->all();
                            $defaultSet = $p->sleeve === 'footwear'
                                ? ['39', '40', '41', '42', '43', '44']
                                : ['S', 'M', 'L', 'XL', 'XXL'];
                            $sizes = count($inStock) ? $inStock : (count($allSizes) ? $allSizes : $defaultSet);

                            $rawGallery = is_array($p->gallery)
                                ? $p->gallery
                                : (json_decode($p->gallery ?? '[]', true) ?: []);
                            $gallery = array_values(array_map(function ($g) {
                                return str_starts_with($g, 'http') ? $g : asset(ltrim($g, '/'));
                            }, array_filter($rawGallery)));

                            $image = $p->image
                                ? (str_starts_with($p->image, 'http') ? $p->image : asset(ltrim($p->image, '/')))
                                : asset('assets/images/products/real-madrid-home-hs.jpg');

                            return [
                                'id' => (int) $p->id,
                                'sku' => $p->sku ?: ('RBZ-' . $p->id),
                                'name' => $p->name,
                                'slug' => $p->slug ?: (string) $p->id,
                                'club' => $p->club ?: 'all',
                                'clubLabel' => $p->club_label ?: ucwords(str_replace('-', ' ', $p->club ?: 'Club')),
                                'sleeve' => $p->sleeve ?: 'half-sleeve',
                                'sleeveLabel' => $p->sleeve_label ?: ($sleeveLabels[$p->sleeve] ?? ucwords(str_replace('-', ' ', $p->sleeve ?: 'Jersey'))),
                                'badge' => $p->sleeve === 'winter-hoodie' ? 'Winter Drop' : ($p->is_ready_stock ? null : 'Out of Stock'),
                                'badgeType' => $p->sleeve === 'winter-hoodie' ? 'neon' : ($p->is_ready_stock ? null : 'dark'),
                                'price' => (float) $p->price,
                                'regularPrice' => $p->regular_price ? (float) $p->regular_price : null,
                                'sizes' => $sizes,
                                'defaultSize' => in_array('L', $sizes, true) ? 'L' : ($sizes[0] ?? 'L'),
                                'image' => $image,
                                'gallery' => $gallery,
                                'shortDesc' => $p->short_desc ?: $p->name,
                                'hasCustomPrint' => (bool) $p->has_custom_print,
                                'isReadyStock' => (bool) $p->is_ready_stock,
                            ];
                        })->values()->all();
                    }
                } catch (\Throwable $e) {
                    // Fail silently during setup/migrations
                }

                $cached = [
                    'hasActiveCoupons' => $hasActiveCoupons,
                    'customPrintFee' => $customPrintFee,
                    'storefrontProducts' => $storefrontProducts,
                    'storefrontProductsJs' => $storefrontProductsJs,
                    'customPrintProducts' => $storefrontProducts->where('has_custom_print', true)->values(),
                    'headerMenuTree' => $headerMenuTree,
                    'footerCareMenu' => $footerCareMenu,
                    'publishedPages' => $publishedPages,
                ];
            }

            $view->with($cached);
        });
    }
}
