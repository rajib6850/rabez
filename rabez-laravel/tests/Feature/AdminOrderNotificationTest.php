<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminOrderNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::create([
            'name' => 'RABEZ Admin',
            'email' => 'admin-notif@rabez.shop',
            'password' => Hash::make('AdminPass123!'),
            'role' => 'admin',
            'is_admin' => true,
            'status' => 'active',
        ]);
    }

    public function test_poll_endpoint_requires_admin_and_returns_new_orders_cleanly(): void
    {
        $this->getJson('/admin/api/orders/poll')->assertStatus(401);

        $customer = User::create([
            'name' => 'Normal User',
            'email' => 'normal@rabez.shop',
            'password' => Hash::make('Secret123!'),
            'role' => 'customer',
            'is_admin' => false,
        ]);
        $this->actingAs($customer)->getJson('/admin/api/orders/poll')->assertStatus(403);

        $admin = $this->makeAdmin();

        $o1 = Order::create([
            'order_number' => 'RBZ-40001',
            'customer_name' => 'First Buyer',
            'customer_phone' => '01711112222',
            'customer_address' => 'Mirpur, Dhaka',
            'zone' => 'All Over Bangladesh',
            'shipping_fee' => 130,
            'subtotal' => 1150,
            'total' => 1280,
            'status' => 'Pending',
            'status_step' => 1,
        ]);

        // Poll from after_id=0 catches first order
        $res0 = $this->actingAs($admin)->getJson('/admin/api/orders/poll?after_id=0');
        $res0->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('latest_id', $o1->id)
            ->assertJsonCount(1, 'new_orders')
            ->assertJsonPath('new_orders.0.order_number', 'RBZ-40001');

        // Poll at current id returns 0 new orders
        $resSame = $this->actingAs($admin)->getJson('/admin/api/orders/poll?after_id=' . $o1->id);
        $resSame->assertOk()
            ->assertJsonPath('latest_id', $o1->id)
            ->assertJsonCount(0, 'new_orders');

        // New order arrives
        $o2 = Order::create([
            'order_number' => 'RBZ-40002',
            'customer_name' => 'Second Buyer',
            'customer_phone' => '01811112222',
            'customer_address' => 'Banani, Dhaka',
            'zone' => 'All Over Bangladesh',
            'shipping_fee' => 130,
            'subtotal' => 1450,
            'total' => 1580,
            'status' => 'Pending',
            'status_step' => 1,
        ]);
        OrderItem::create([
            'order_id' => $o2->id,
            'product_name' => 'Real Madrid Away 24/25',
            'size' => 'XL',
            'variant_title' => 'Player Edition / XL',
            'qty' => 1,
            'unit_price' => 1450,
            'total_price' => 1450,
        ]);

        $resNew = $this->actingAs($admin)->getJson('/admin/api/orders/poll?after_id=' . $o1->id);
        $resNew->assertOk()
            ->assertJsonPath('latest_id', $o2->id)
            ->assertJsonPath('open_count', 2)
            ->assertJsonCount(1, 'new_orders')
            ->assertJsonPath('new_orders.0.order_number', 'RBZ-40002')
            ->assertJsonCount(1, 'new_orders.0.items');
    }

    public function test_admin_dashboard_renders_notification_center_and_sound_controls(): void
    {
        $admin = $this->makeAdmin();

        $res = $this->actingAs($admin)->get('/admin');
        $res->assertOk();
        $res->assertSee('js-adm-sound-btn', false);
        $res->assertSee('js-adm-notif-btn', false);
        $res->assertSee('js-adm-notif-dropdown', false);
        $res->assertSee('js-adm-sound-tone', false);
        $res->assertSee('js-adm-test-sound', false);
        $res->assertSee('js-adm-order-alert', false);
        $res->assertSee('js-adm-profile-btn', false);
        $res->assertSee('js-adm-profile-dropdown', false);
        $res->assertSee('/admin/api/orders/poll', false);
    }
}
