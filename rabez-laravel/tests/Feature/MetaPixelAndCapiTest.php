<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MetaPixelAndCapiTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdminUser(): User
    {
        return User::create([
            'name' => 'Pixel Admin',
            'email' => 'pixel-admin@rabez.shop',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'is_admin' => true,
            'status' => 'active',
        ]);
    }

    public function test_seeded_meta_pixel_id_is_injected_before_analytics_script_on_storefront(): void
    {
        $product = Product::create([
            'name' => 'Real Madrid 26/27 Home Jersey',
            'slug' => 'real-madrid-26-27-pixel-test',
            'sku' => 'RBZ-PX-01',
            'price' => 1150,
            'club' => 'real-madrid',
            'sleeve' => 'half-sleeve',
            'image' => 'assets/images/products/real-madrid-home-hs.jpg',
            'is_ready_stock' => true,
        ]);

        $shopRes = $this->get('/shop');
        $shopRes->assertOk()
            ->assertSee('window.RABEZ_PIXEL_ID = "1693763272380977";', false)
            ->assertSee('https://www.facebook.com/tr?id=1693763272380977&ev=PageView&noscript=1', false);

        $html = $shopRes->getContent();
        $pixelPos = strpos($html, 'window.RABEZ_PIXEL_ID');
        $scriptPos = strpos($html, 'assets/js/analytics.js');
        $this->assertNotFalse($pixelPos);
        $this->assertNotFalse($scriptPos);
        $this->assertLessThan($scriptPos, $pixelPos, 'RABEZ_PIXEL_ID must be defined before analytics.js executes');

        $this->get('/product/' . $product->slug)
            ->assertOk()
            ->assertSee('window.RABEZ_PIXEL_ID = "1693763272380977";', false)
            ->assertSee('window.RABEZ_ANALYTICS.trackViewContent(currentProductData);', false);
    }

    public function test_admin_can_update_and_disable_meta_pixel_and_capi_settings(): void
    {
        $admin = $this->makeAdminUser();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('name="meta_pixel_id"', false)
            ->assertSee('name="meta_capi_token"', false)
            ->assertSee('name="meta_capi_test_code"', false)
            ->assertSee('1693763272380977')
            ->assertSee('TEST86756');

        // Update with whitespace/prefix noise -> sanitized cleanly
        $this->actingAs($admin)
            ->postJson('/admin/settings', [
                'meta_pixel_id' => 'ID 9988776655443322 ',
                'meta_capi_token' => ' EAABwTestToken123 ',
                'meta_capi_test_code' => ' test86756 ',
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta_pixel_id', '9988776655443322')
            ->assertJsonPath('meta_capi_test_code', 'TEST86756');

        $this->assertSame('9988776655443322', Setting::get('meta_pixel_id'));
        $this->assertSame('EAABwTestToken123', Setting::get('meta_capi_token'));
        $this->assertSame('TEST86756', Setting::get('meta_capi_test_code'));

        $this->get('/shop')
            ->assertOk()
            ->assertSee('window.RABEZ_PIXEL_ID = "9988776655443322";', false);

        // Disable Pixel by clearing ID
        $this->actingAs($admin)
            ->postJson('/admin/settings', ['meta_pixel_id' => ''])
            ->assertOk()
            ->assertJsonPath('meta_pixel_id', '');

        $this->get('/shop')
            ->assertOk()
            ->assertSee('window.RABEZ_PIXEL_ID = "";', false)
            ->assertDontSee('https://www.facebook.com/tr?id=', false);
    }

    public function test_checkout_dispatches_capi_purchase_only_when_token_configured(): void
    {
        Http::fake([
            'https://graph.facebook.com/*' => Http::response(['events_received' => 1], 200),
        ]);

        // Ensure token is empty initially: no outbound CAPI call on checkout
        Setting::set('meta_pixel_id', '1693763272380977');
        Setting::set('meta_capi_token', '');

        $product = Product::create([
            'name' => 'Bangladesh Home Jersey',
            'slug' => 'bangladesh-home-jersey-capi-test',
            'sku' => 'RBZ-BD-CAPI',
            'price' => 1150,
            'club' => 'bangladesh',
            'sleeve' => 'half-sleeve',
            'image' => 'assets/images/products/real-madrid-home-hs.jpg',
            'is_ready_stock' => true,
        ]);

        $payload = [
            'customer_name' => 'Tanvir Ahmed',
            'customer_phone' => '01712345678',
            'customer_address' => 'House 12, Dhanmondi, Dhaka',
            'shipping_zone' => 130,
            'cart_items' => [
                [
                    'id' => $product->id,
                    'name' => 'Bangladesh Home Jersey',
                    'size' => 'L',
                    'qty' => 2,
                    'price' => 1150,
                ],
            ],
        ];

        $this->postJson('/checkout', $payload)
            ->assertOk()
            ->assertJsonPath('success', true);

        Http::assertNothingSent();

        // Configure CAPI token + test_event_code -> checkout fires Graph API v21.0 Purchase with test_event_code
        Setting::set('meta_capi_token', 'EAABwLiveTokenXYZ');
        Setting::set('meta_capi_test_code', 'TEST86756');

        $res = $this->postJson('/checkout', $payload)
            ->assertOk()
            ->assertJsonPath('success', true);

        $eventId = $res->json('event_id');
        $orderId = $res->json('order_id');
        $expectedHash = hash('sha256', '8801712345678');

        Http::assertSent(function ($request) use ($eventId, $orderId, $expectedHash) {
            $data = $request->data();
            $evt = $data['data'][0] ?? [];
            return str_starts_with($request->url(), 'https://graph.facebook.com/v21.0/1693763272380977/events?access_token=EAABwLiveTokenXYZ')
                && ($data['test_event_code'] ?? null) === 'TEST86756'
                && ($evt['event_name'] ?? null) === 'Purchase'
                && ($evt['event_id'] ?? null) === $eventId
                && ($evt['user_data']['ph'][0] ?? null) === $expectedHash
                && ($evt['custom_data']['currency'] ?? null) === 'BDT'
                && ($evt['custom_data']['order_id'] ?? null) === $orderId
                && (int) ($evt['custom_data']['num_items'] ?? 0) === 2;
        });

        // When test_event_code is cleared, payload omits test_event_code for production live events
        Setting::set('meta_capi_test_code', '');
        $res2 = $this->postJson('/checkout', $payload)
            ->assertOk()
            ->assertJsonPath('success', true);
        $eventId2 = $res2->json('event_id');

        Http::assertSent(function ($request) use ($eventId2) {
            $data = $request->data();
            $evt = $data['data'][0] ?? [];
            return ($evt['event_id'] ?? null) === $eventId2
                && !array_key_exists('test_event_code', $data);
        });
    }
}
