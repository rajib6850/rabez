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
}
