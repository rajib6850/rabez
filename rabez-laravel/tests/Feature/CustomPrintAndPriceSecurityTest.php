<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomPrintAndPriceSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_overrides_client_tampered_price_with_verified_database_price()
    {
        Setting::set('shipping_mode', 'flat');
        Setting::set('shipping_flat_fee', 130);
        Setting::set('custom_print_fee', 250);

        $product = Product::create([
            'name' => 'Real Madrid 26/27 Home Jersey',
            'slug' => 'real-madrid-26-27-home-jersey',
            'sku' => 'RM-HOME-26',
            'price' => 1150,
            'image' => 'assets/images/products/sample.jpg',
            'club' => 'real-madrid',
            'sleeve' => 'half-sleeve',
            'has_custom_print' => true,
        ]);

        // Attacker attempts to buy 1150 Tk jersey for 10 Tk
        $response = $this->postJson('/checkout', [
            'customer_name' => 'Security Tester',
            'customer_phone' => '01711223344',
            'customer_address' => 'Mirpur 10, Dhaka',
            'shipping_zone' => 130,
            'cart_items' => [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'size' => 'L',
                    'qty' => 1,
                    'price' => 10, // Tampered price
                    'image' => '/assets/images/products/sample.jpg',
                ]
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $orderNumber = $response->json('order_id');
        $order = Order::where('order_number', $orderNumber)->first();

        $this->assertNotNull($order);
        // Subtotal must be 1150 (not 10!), total must be 1150 + 130 = 1280
        $this->assertEquals(1150, $order->subtotal);
        $this->assertEquals(1280, $order->total);

        $item = $order->items->first();
        $this->assertEquals(1150, $item->unit_price);
        $this->assertEquals(1150, $item->total_price);
    }

    public function test_checkout_enforces_custom_print_fee_and_sanitizes_inputs()
    {
        Setting::set('shipping_mode', 'flat');
        Setting::set('shipping_flat_fee', 130);
        Setting::set('custom_print_fee', 250);

        $product = Product::create([
            'name' => 'Barcelona 26/27 Home Jersey',
            'slug' => 'barcelona-26-27-home-jersey',
            'sku' => 'FCB-HOME-26',
            'price' => 1150,
            'image' => 'assets/images/products/sample.jpg',
            'club' => 'barcelona',
            'sleeve' => 'half-sleeve',
            'has_custom_print' => true,
        ]);

        // Customer requests custom print with dirty input and tampered price
        $response = $this->postJson('/checkout', [
            'customer_name' => 'Lionel Fan',
            'customer_phone' => '01799887766',
            'customer_address' => 'Gulshan 2, Dhaka',
            'shipping_zone' => 130,
            'cart_items' => [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'size' => 'XL',
                    'qty' => 2,
                    'price' => 1150, // Client tries to evade 250 fee
                    'custom_name' => '<script>alert(1)</script>messi-10',
                    'custom_number' => '10abc',
                    'image' => '/assets/images/products/sample.jpg',
                ]
            ],
        ]);

        $response->assertStatus(200);
        $orderNumber = $response->json('order_id');
        $order = Order::where('order_number', $orderNumber)->first();

        // 1150 + 250 = 1400 per jersey. For 2 jerseys: 1400 * 2 = 2800. Total = 2800 + 130 = 2930.
        $this->assertEquals(2800, $order->subtotal);
        $this->assertEquals(2930, $order->total);

        $item = $order->items->first();
        $this->assertEquals(1400, $item->unit_price);
        $this->assertEquals(2800, $item->total_price);
        // Sanitized custom name & number
        $this->assertEquals('ALERT1MESSI-10', $item->custom_name);
        $this->assertEquals('10', $item->custom_number);
    }

    public function test_custom_print_rejected_if_product_does_not_support_it()
    {
        Setting::set('shipping_mode', 'flat');
        Setting::set('shipping_flat_fee', 130);
        Setting::set('custom_print_fee', 250);

        $product = Product::create([
            'name' => 'Plain Training Jersey',
            'slug' => 'plain-training-jersey',
            'sku' => 'TRN-01',
            'price' => 850,
            'image' => 'assets/images/products/sample.jpg',
            'club' => 'other',
            'sleeve' => 'half-sleeve',
            'has_custom_print' => false, // Does NOT allow custom print
        ]);

        $response = $this->postJson('/checkout', [
            'customer_name' => 'Training Fan',
            'customer_phone' => '01811223344',
            'customer_address' => 'Uttara Sector 7, Dhaka',
            'shipping_zone' => 130,
            'cart_items' => [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'size' => 'M',
                    'qty' => 1,
                    'price' => 850,
                    'custom_name' => 'HACKER',
                    'custom_number' => '99',
                    'image' => '/assets/images/products/sample.jpg',
                ]
            ],
        ]);

        $response->assertStatus(200);
        $orderNumber = $response->json('order_id');
        $order = Order::where('order_number', $orderNumber)->first();

        // Must NOT add custom print fee
        $this->assertEquals(850, $order->subtotal);
        $item = $order->items->first();
        $this->assertNull($item->custom_name);
        $this->assertNull($item->custom_number);
    }

    public function test_admin_dashboard_calculates_and_displays_print_queue_accurately()
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);

        // Order 1: Pending with custom print (MUST be in print queue)
        $order1 = Order::create([
            'order_number' => 'RBZ-10001',
            'customer_name' => 'John Doe',
            'customer_phone' => '01711223344',
            'customer_address' => 'Dhaka',
            'status' => 'Pending',
            'subtotal' => 1400,
            'total' => 1470,
            'shipping_fee' => 70,
        ]);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_name' => 'Real Madrid Home',
            'size' => 'L',
            'qty' => 1,
            'unit_price' => 1400,
            'total_price' => 1400,
            'custom_name' => 'MESSI',
            'custom_number' => '10',
        ]);

        // Order 2: Shipped with custom print (Already shipped, MUST NOT be in queue)
        $order2 = Order::create([
            'order_number' => 'RBZ-10002',
            'customer_name' => 'Jane Doe',
            'customer_phone' => '01811223344',
            'customer_address' => 'Chittagong',
            'status' => 'Shipped',
            'subtotal' => 1400,
            'total' => 1530,
            'shipping_fee' => 130,
        ]);
        OrderItem::create([
            'order_id' => $order2->id,
            'product_name' => 'Barcelona Home',
            'size' => 'M',
            'qty' => 1,
            'unit_price' => 1400,
            'total_price' => 1400,
            'custom_name' => 'RONALDO',
            'custom_number' => '7',
        ]);

        // Order 3: Printing with custom print (In progress, MUST be in print queue)
        $order3 = Order::create([
            'order_number' => 'RBZ-10003',
            'customer_name' => 'David Warner',
            'customer_phone' => '01911223344',
            'customer_address' => 'Sylhet',
            'status' => 'Printing',
            'subtotal' => 1400,
            'total' => 1530,
            'shipping_fee' => 130,
        ]);
        OrderItem::create([
            'order_id' => $order3->id,
            'product_name' => 'Arsenal Home',
            'size' => 'XL',
            'qty' => 1,
            'unit_price' => 1400,
            'total_price' => 1400,
            'custom_name' => 'BELLINGHAM',
            'custom_number' => '5',
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertViewHas('printQueueCount', 2);
        $response->assertSee('<span class="adm-nav-count js-print-count">2</span>', false);
        $response->assertSee('<span class="adm-chip adm-chip--printing js-print-chip">2 waiting</span>', false);
    }

    public function test_homepage_renders_upgraded_custom_print_studio_with_only_eligible_products()
    {
        Setting::set('custom_print_fee', 250);

        $jersey = Product::create([
            'name' => 'Real Madrid 26/27 Home Jersey',
            'slug' => 'real-madrid-26-27-home-jersey',
            'sku' => 'RM-HOME-26',
            'price' => 1150,
            'image' => 'assets/images/products/real-madrid-home-hs.jpg',
            'club' => 'real-madrid',
            'sleeve' => 'half-sleeve',
            'has_custom_print' => true,
        ]);

        $hoodie = Product::create([
            'name' => 'Winter Heavyweight Test Hoodie',
            'slug' => 'winter-heavyweight-test-hoodie',
            'sku' => 'HD-TEST-99',
            'price' => 1450,
            'image' => 'assets/images/products/messi-barca-arg-hoodie.jpg',
            'club' => 'argentina',
            'sleeve' => 'winter-hoodie',
            'has_custom_print' => false,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('id="custom-print"', false);
        $response->assertSee('+৳250', false);
        $response->assertSee('BELLINGHAM 5', false);
        $response->assertSee('data-color-name="Gold"', false);
        $response->assertSee($jersey->name, false);

        // Extract only the #custom-print section HTML and assert hoodie is NOT inside the customizer kit rail or select
        $html = $response->getContent();
        $startPos = strpos($html, '<section id="custom-print"');
        $endPos = strpos($html, '</section>', $startPos);
        $this->assertNotFalse($startPos);
        $this->assertNotFalse($endPos);
        $studioHtml = substr($html, $startPos, $endPos - $startPos);

        $this->assertStringContainsString('js-home-kit-rail', $studioHtml);
        $this->assertStringContainsString($jersey->name, $studioHtml);
        $this->assertStringNotContainsString($hoodie->name, $studioHtml);
    }

    public function test_checkout_preserves_full_custom_name_and_allowlisted_vinyl_color()
    {
        Setting::set('shipping_mode', 'flat');
        Setting::set('shipping_flat_fee', 130);
        Setting::set('custom_print_fee', 250);

        $product = Product::create([
            'name' => 'Arsenal 26/27 Home Jersey',
            'slug' => 'arsenal-26-27-home-jersey',
            'sku' => 'ARS-HOME-26',
            'price' => 1150,
            'image' => 'assets/images/products/arsenal-home-hs.jpg',
            'club' => 'arsenal',
            'sleeve' => 'half-sleeve',
            'has_custom_print' => true,
        ]);

        // Valid color + 10-char name
        $res1 = $this->postJson('/checkout', [
            'customer_name' => 'Gunner Fan',
            'customer_phone' => '01712345678',
            'customer_address' => 'Banani, Dhaka',
            'shipping_zone' => 130,
            'cart_items' => [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'size' => 'L',
                    'qty' => 1,
                    'price' => 1400,
                    'custom_name' => 'BELLINGHAM',
                    'custom_number' => '5',
                    'custom_color' => 'Gold',
                ]
            ],
        ]);
        $res1->assertStatus(200);
        $order1 = Order::where('order_number', $res1->json('order_id'))->firstOrFail();
        $this->assertEquals(1400, $order1->subtotal);
        $item1 = $order1->items->first();
        $this->assertEquals('BELLINGHAM [GOLD]', $item1->custom_name);
        $this->assertEquals('5', $item1->custom_number);

        // Malicious / non-allowlisted custom_color must be ignored
        $res2 = $this->postJson('/checkout', [
            'customer_name' => 'Safe Fan',
            'customer_phone' => '01812345678',
            'customer_address' => 'Mirpur, Dhaka',
            'shipping_zone' => 130,
            'cart_items' => [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'size' => 'M',
                    'qty' => 1,
                    'price' => 1400,
                    'custom_name' => 'SAKA',
                    'custom_number' => '7',
                    'custom_color' => '<script>hack</script>',
                ]
            ],
        ]);
        $res2->assertStatus(200);
        $order2 = Order::where('order_number', $res2->json('order_id'))->firstOrFail();
        $item2 = $order2->items->first();
        $this->assertEquals('SAKA', $item2->custom_name);
        $this->assertEquals('7', $item2->custom_number);
    }
}
