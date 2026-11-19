<?php

namespace Tests\Feature;

use App\Models\BusinessExpense;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_cannot_access_business_analytics_endpoints()
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'is_admin' => false,
            'status' => 'active',
        ]);

        $manager = User::factory()->create([
            'role' => 'manager',
            'is_admin' => false,
            'status' => 'active',
        ]);

        foreach ([$staff, $manager] as $actor) {
            $this->actingAs($actor)->getJson('/admin/api/analytics')->assertStatus(403);
            $this->actingAs($actor)->get('/admin/api/analytics/export')->assertStatus(403);
            $this->actingAs($actor)->postJson('/admin/api/product-costs', [])->assertStatus(403);
            $this->actingAs($actor)->postJson('/admin/expenses', [
                'title' => 'Blocked',
                'category' => 'ads',
                'amount' => 100,
                'expense_date' => now()->format('Y-m-d'),
            ])->assertStatus(403);
        }
    }

    public function test_main_admin_gets_accurate_sales_cogs_print_cost_expenses_and_net_profit()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_admin' => true,
            'status' => 'active',
        ]);

        Setting::set('custom_print_fee', '250');
        Setting::set('custom_print_cost', '80');

        $product = Product::create([
            'name' => 'Real Madrid 26/27 Home Jersey',
            'slug' => 'real-madrid-26-27-home-jersey',
            'sku' => 'RM-HOME-26',
            'club' => 'real-madrid',
            'sleeve' => 'half-sleeve',
            'price' => 1150,
            'cost_price' => 650,
            'image' => '/assets/images/products/real-madrid-home-hs.jpg',
            'has_custom_print' => true,
            'is_ready_stock' => true,
        ]);

        // Order 1: Delivered — 2 pcs, 1 with custom print (+250 fee, +80 cost), ৳100 coupon discount, ৳70 shipping
        // Subtotal = 1150 + (1150 + 250) = 2550; net product sales = 2550 - 100 = 2450; total = 2450 + 70 = 2520
        // COGS = (650 * 2) + 80 = 1380
        $deliveredOrder = Order::create([
            'order_number' => 'RBZ-20001',
            'customer_name' => 'Kamal Hasan',
            'customer_phone' => '01711223344',
            'customer_address' => 'Banani, Dhaka',
            'status' => 'Delivered',
            'shipping_fee' => 70,
            'subtotal' => 2550,
            'total' => 2520,
        ]);
        OrderItem::create([
            'order_id' => $deliveredOrder->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'size' => 'L',
            'qty' => 1,
            'unit_price' => 1150,
            'unit_cost' => 650,
            'total_price' => 1150,
        ]);
        OrderItem::create([
            'order_id' => $deliveredOrder->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'size' => 'XL',
            'qty' => 1,
            'unit_price' => 1400,
            'unit_cost' => 650,
            'total_price' => 1400,
            'custom_name' => 'BELLINGHAM',
            'custom_number' => '5',
        ]);

        // Order 2: Pending — 1 pc (1150 net product sales, COGS = 650)
        $pendingOrder = Order::create([
            'order_number' => 'RBZ-20002',
            'customer_name' => 'Nabil Ahmed',
            'customer_phone' => '01811223344',
            'customer_address' => 'Uttara, Dhaka',
            'status' => 'Pending',
            'shipping_fee' => 130,
            'subtotal' => 1150,
            'total' => 1280,
        ]);
        OrderItem::create([
            'order_id' => $pendingOrder->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'size' => 'M',
            'qty' => 1,
            'unit_price' => 1150,
            'unit_cost' => 650,
            'total_price' => 1150,
        ]);

        // Order 3: Cancelled — 5 pcs (MUST be excluded from units, sales, and profit)
        $cancelledOrder = Order::create([
            'order_number' => 'RBZ-20003',
            'customer_name' => 'Cancelled Buyer',
            'customer_phone' => '01911223344',
            'customer_address' => 'Sylhet',
            'status' => 'Cancelled',
            'shipping_fee' => 130,
            'subtotal' => 5750,
            'total' => 5880,
        ]);
        OrderItem::create([
            'order_id' => $cancelledOrder->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'size' => 'L',
            'qty' => 5,
            'unit_price' => 1150,
            'unit_cost' => 650,
            'total_price' => 5750,
        ]);

        BusinessExpense::create([
            'title' => 'Facebook Boost Campaign',
            'category' => 'ads',
            'amount' => 300,
            'expense_date' => now()->toDateString(),
            'created_by' => $admin->id,
        ]);

        // 1. Check Active basis (Delivered + Pending = 3 units, 3600 product net sales, 2030 COGS, 1570 gross, 300 exp, 1270 net)
        $resActive = $this->actingAs($admin)->getJson('/admin/api/analytics?range=30d&basis=active');
        $resActive->assertStatus(200)->assertJson(['success' => true]);
        $k = $resActive->json('kpis');

        $this->assertEquals(3, $k['total_units_sold']);
        $this->assertEquals(1, $k['cancelled_orders']);
        $this->assertEquals(3600, $k['product_net_sales']);
        $this->assertEquals(100, $k['discounts_given']);
        $this->assertEquals(2030, $k['total_cogs']);
        $this->assertEquals(1570, $k['gross_profit']);
        $this->assertEquals(300, $k['total_expenses']);
        $this->assertEquals(1270, $k['net_profit']);
        $this->assertEquals(1, $k['custom_prints_sold']);
        $this->assertEquals(170, $k['custom_print_profit']);

        // 2. Check Delivered Only basis (only Order 1: 2 units, 2450 net sales, 1380 COGS, 1070 gross, 770 net)
        $resDelivered = $this->actingAs($admin)->getJson('/admin/api/analytics?range=30d&basis=delivered');
        $resDelivered->assertStatus(200);
        $kd = $resDelivered->json('kpis');

        $this->assertEquals(2, $kd['total_units_sold']);
        $this->assertEquals(2450, $kd['product_net_sales']);
        $this->assertEquals(1380, $kd['total_cogs']);
        $this->assertEquals(1070, $kd['gross_profit']);
        $this->assertEquals(770, $kd['net_profit']);
    }

    public function test_main_admin_can_save_product_costs_manage_expenses_and_export_csv()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_admin' => true,
            'status' => 'active',
        ]);

        $product = Product::create([
            'name' => 'Barcelona 26/27 Home Jersey',
            'slug' => 'barcelona-26-27-home-jersey',
            'sku' => 'FCB-HOME-26',
            'club' => 'barcelona',
            'sleeve' => 'half-sleeve',
            'price' => 1250,
            'cost_price' => 0,
            'image' => '/assets/images/products/barcelona-home-hs.jpg',
        ]);

        // Save bulk product cost + custom print cost
        $costRes = $this->actingAs($admin)->postJson('/admin/api/product-costs', [
            'custom_print_cost' => 95,
            'costs' => [
                ['id' => $product->id, 'cost_price' => 700],
            ],
        ]);
        $costRes->assertStatus(200)->assertJson(['success' => true, 'custom_print_cost' => 95]);
        $this->assertEquals(700, (float) $product->fresh()->cost_price);
        $this->assertEquals('95', Setting::get('custom_print_cost'));

        // Add expense
        $expRes = $this->actingAs($admin)->postJson('/admin/expenses', [
            'title' => 'Poly Packaging Pack',
            'category' => 'packaging',
            'amount' => 450,
            'expense_date' => now()->format('Y-m-d'),
            'notes' => '100 poly mailers',
        ]);
        $expRes->assertStatus(200)->assertJson(['success' => true]);
        $expId = $expRes->json('expense.id');
        $this->assertDatabaseHas('business_expenses', ['id' => $expId, 'title' => 'Poly Packaging Pack']);

        // Order to verify CSV export
        $order = Order::create([
            'order_number' => 'RBZ-CSV-01',
            'customer_name' => 'CSV Buyer',
            'customer_phone' => '01711223344',
            'customer_address' => 'Dhaka',
            'status' => 'Delivered',
            'shipping_fee' => 70,
            'subtotal' => 1250,
            'total' => 1320,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'size' => 'L',
            'qty' => 1,
            'unit_price' => 1250,
            'unit_cost' => 700,
            'total_price' => 1250,
        ]);

        // Export CSV
        $csvRes = $this->actingAs($admin)->get('/admin/api/analytics/export?range=30d');
        $csvRes->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $csvRes->headers->get('Content-Type'));
        $csvContent = $csvRes->streamedContent();
        $this->assertStringContainsString('Order #,Date,Customer,Phone,Status', $csvContent);
        $this->assertStringContainsString('RBZ-CSV-01', $csvContent);

        // Delete expense
        $delRes = $this->actingAs($admin)->deleteJson("/admin/expenses/{$expId}");
        $delRes->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('business_expenses', ['id' => $expId]);
    }
}
