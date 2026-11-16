<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_redirects_to_signed_order_confirmation_page()
    {
        $product = Product::create([
            'name' => 'Real Madrid 26/27 Winter Fleece Warmup Hoodie',
            'slug' => 'real-madrid-winter-hoodie',
            'sku' => 'RM-WH-2627',
            'price' => 1450,
            'image' => 'assets/images/products/sample.jpg',
            'club' => 'real-madrid',
            'sleeve' => 'hoodie',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'title' => 'Player Edition / L',
            'option1' => 'Player Edition',
            'option2' => 'L',
            'size' => 'L',
            'price' => 1450,
            'stock_qty' => 10,
            'is_in_stock' => true,
            'sku' => 'RM-WH-L',
        ]);

        $response = $this->postJson('/checkout', [
            'customer_name' => 'Md. Rajib',
            'customer_phone' => '01792332909',
            'customer_address' => 'Road-10, College Avenue, Barishal',
            'shipping_zone' => 130,
            'cart_items' => [
                [
                    'id' => $product->id,
                    'variant_id' => $variant->id,
                    'variant_title' => 'Player Edition / L',
                    'name' => $product->name,
                    'size' => 'Player Edition / L',
                    'qty' => 1,
                    'price' => 1450,
                    'image' => '/assets/images/products/sample.jpg',
                ]
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $redirectUrl = $response->json('redirect_url');
        $orderNumber = $response->json('order_id');
        $this->assertStringContainsString('/order-confirmed/' . $orderNumber, $redirectUrl);
        $this->assertStringContainsString('token=', $redirectUrl);

        $confirmPage = $this->get($redirectUrl);
        $confirmPage->assertStatus(200);
        $confirmPage->assertSee('Thank You, Md. Rajib!');
        $confirmPage->assertSee($orderNumber);
        $confirmPage->assertSee('Real Madrid 26/27 Winter Fleece Warmup Hoodie');
        $confirmPage->assertSee('Player Edition / L');
        $confirmPage->assertSee('Road-10, College Avenue, Barishal');
        $confirmPage->assertSee('Track Order &amp; Invoice', false);
        $confirmPage->assertSee('Print Receipt');
    }

    public function test_unauthorized_guest_cannot_view_other_order_confirmation_without_token()
    {
        $order = Order::create([
            'order_number' => 'RBZ-99111',
            'customer_name' => 'Private Buyer',
            'customer_phone' => '01711111111',
            'customer_address' => 'Secret Address Dhaka',
            'zone' => 'All Over Bangladesh (৳130)',
            'shipping_fee' => 130,
            'subtotal' => 1150,
            'total' => 1280,
            'status' => 'Pending',
            'status_step' => 1,
            'event_id' => 'evt_secret_123',
        ]);

        $response = $this->get('/order-confirmed/RBZ-99111');
        $response->assertRedirect(route('track-order', ['order' => 'RBZ-99111']));
    }

    public function test_authenticated_owner_can_revisit_order_confirmation_without_token()
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'RBZ-99222',
            'customer_name' => $user->name,
            'customer_phone' => '01722222222',
            'customer_address' => 'Dhanmondi 27, Dhaka',
            'zone' => 'All Over Bangladesh (৳130)',
            'shipping_fee' => 130,
            'subtotal' => 1150,
            'total' => 1280,
            'status' => 'Pending',
            'status_step' => 1,
            'event_id' => 'evt_owner_456',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_name' => 'Argentina Away Jersey',
            'size' => 'XL',
            'variant_title' => 'Fan Edition / XL',
            'qty' => 1,
            'unit_price' => 1150,
            'total_price' => 1150,
        ]);

        $response = $this->actingAs($user)->get('/order-confirmed/RBZ-99222');
        $response->assertStatus(200);
        $response->assertSee('RBZ-99222');
        $response->assertSee('Argentina Away Jersey');
        $response->assertSee('Fan Edition / XL');
    }
}
