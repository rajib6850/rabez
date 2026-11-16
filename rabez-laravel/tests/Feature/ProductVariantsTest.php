<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantsTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_variants_and_options_storage()
    {
        $options = [
            ['name' => 'Edition', 'values' => ['Fan Edition', 'Player Edition']],
            ['name' => 'Size', 'values' => ['M', 'L', 'XL']],
        ];

        $product = Product::create([
            'name' => 'Real Madrid Home 24/25',
            'slug' => 'real-madrid-home-24-25',
            'sku' => 'RM-H-2425',
            'price' => 1150,
            'regular_price' => 1350,
            'image' => 'assets/images/products/sample.jpg',
            'has_variants' => true,
            'options' => $options,
            'club' => 'real-madrid',
            'sleeve' => 'half-sleeve',
        ]);

        $v1 = ProductVariant::create([
            'product_id' => $product->id,
            'title' => 'Fan Edition / M',
            'option1' => 'Fan Edition',
            'option2' => 'M',
            'size' => 'M',
            'price' => 1150,
            'regular_price' => 1350,
            'stock_qty' => 15,
            'is_in_stock' => true,
            'sku' => 'RM-H-2425-FAN-M',
        ]);

        $v2 = ProductVariant::create([
            'product_id' => $product->id,
            'title' => 'Player Edition / XL',
            'option1' => 'Player Edition',
            'option2' => 'XL',
            'size' => 'XL',
            'price' => 1450,
            'regular_price' => 1650,
            'stock_qty' => 5,
            'is_in_stock' => true,
            'sku' => 'RM-H-2425-PLY-XL',
        ]);

        $this->assertTrue($product->fresh()->has_variants);
        $this->assertCount(2, $product->fresh()->options);
        $this->assertEquals(1450, (float) $v2->fresh()->price);
        $this->assertEquals('RM-H-2425-PLY-XL', $v2->fresh()->sku);
    }

    public function test_single_product_page_renders_shopify_options()
    {
        $options = [
            ['name' => 'Edition', 'values' => ['Fan Edition', 'Player Edition']],
            ['name' => 'Size', 'values' => ['M', 'L']],
        ];

        $product = Product::create([
            'name' => 'Barcelona Away 24/25',
            'slug' => 'barcelona-away-24-25',
            'sku' => 'FCB-A-2425',
            'price' => 1150,
            'regular_price' => 1350,
            'image' => 'assets/images/products/sample.jpg',
            'has_variants' => true,
            'options' => $options,
            'club' => 'barcelona',
            'sleeve' => 'half-sleeve',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'title' => 'Player Edition / L',
            'option1' => 'Player Edition',
            'option2' => 'L',
            'size' => 'L',
            'price' => 1450,
            'stock_qty' => 10,
            'is_in_stock' => true,
            'sku' => 'FCB-A-2425-PLY-L',
        ]);

        $response = $this->get('/product/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee('Select Edition:');
        $response->assertSee('Select Size:');
        $response->assertSee('Fan Edition');
        $response->assertSee('Player Edition');
        $response->assertSee('FCB-A-2425-PLY-L');
    }

    public function test_checkout_records_variant_id_and_variant_title()
    {
        $product = Product::create([
            'name' => 'Argentina 3 Star Jersey',
            'slug' => 'argentina-3-star-jersey',
            'sku' => 'ARG-3S-24',
            'price' => 1250,
            'regular_price' => 1450,
            'image' => 'assets/images/products/sample.jpg',
            'has_variants' => true,
            'options' => [
                ['name' => 'Edition', 'values' => ['Player Edition']],
                ['name' => 'Size', 'values' => ['XL']],
            ],
            'club' => 'argentina',
            'sleeve' => 'half-sleeve',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'title' => 'Player Edition / XL',
            'option1' => 'Player Edition',
            'option2' => 'XL',
            'size' => 'XL',
            'price' => 1450,
            'regular_price' => 1650,
            'stock_qty' => 8,
            'is_in_stock' => true,
            'sku' => 'ARG-PLY-XL',
        ]);

        $payload = [
            'customer_name' => 'Shakib Al Hasan',
            'customer_phone' => '01712345678',
            'customer_address' => 'Mirpur, Dhaka',
            'shipping_zone' => 70,
            'cart_items' => [
                [
                    'id' => $product->id,
                    'variant_id' => $variant->id,
                    'variant_title' => 'Player Edition / XL',
                    'name' => $product->name,
                    'size' => 'Player Edition / XL',
                    'qty' => 1,
                    'price' => 1450,
                    'image' => '/assets/images/products/argentina.jpg',
                ]
            ]
        ];

        $response = $this->postJson('/checkout', $payload);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals(1580, (float) $order->total); // 1450 + 130 flat shipping

        $item = OrderItem::where('order_id', $order->id)->first();
        $this->assertNotNull($item);
        $this->assertEquals($product->id, $item->product_id);
        $this->assertEquals($variant->id, $item->variant_id);
        $this->assertEquals('Player Edition / XL', $item->variant_title);
        $this->assertEquals(1450, (float) $item->unit_price);
    }

    public function test_admin_sync_shopify_variants()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $product = Product::create([
            'name' => 'Arsenal Home 24/25',
            'slug' => 'arsenal-home-24-25',
            'sku' => 'ARS-H-2425',
            'price' => 1150,
            'image' => 'assets/images/products/sample.jpg',
            'club' => 'arsenal',
            'sleeve' => 'half-sleeve',
        ]);

        $updateData = [
            'name' => 'Arsenal Home 24/25 Updated',
            'price' => 1150,
            'regular_price' => 1350,
            'has_variants' => 1,
            'options' => json_encode([
                ['name' => 'Edition', 'values' => ['Fan Edition', 'Player Edition']],
                ['name' => 'Size', 'values' => ['M', 'L']],
            ]),
            'variants' => json_encode([
                [
                    'title' => 'Fan Edition / M',
                    'option1' => 'Fan Edition',
                    'option2' => 'M',
                    'price' => 1150,
                    'regular_price' => 1350,
                    'sku' => 'ARS-FAN-M',
                    'is_in_stock' => 1,
                    'stock_qty' => 12,
                ],
                [
                    'title' => 'Player Edition / L',
                    'option1' => 'Player Edition',
                    'option2' => 'L',
                    'price' => 1450,
                    'regular_price' => 1650,
                    'sku' => 'ARS-PLY-L',
                    'is_in_stock' => 1,
                    'stock_qty' => 6,
                ]
            ]),
        ];

        $response = $this->actingAs($admin)->postJson('/admin/products/' . $product->id . '/update', $updateData);
        $response->assertStatus(200);

        $product->refresh();
        $this->assertTrue($product->has_variants);
        $this->assertCount(2, $product->options);
        $this->assertCount(2, $product->variants);

        $ply = $product->variants()->where('option1', 'Player Edition')->first();
        $this->assertNotNull($ply);
        $this->assertEquals(1450, (float) $ply->price);
        $this->assertEquals('ARS-PLY-L', $ply->sku);
    }
}
