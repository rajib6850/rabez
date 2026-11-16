<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MultiStaffOrderHandlingTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role, string $email, string $name, string $status = 'active'): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'phone' => '01711112222',
            'password' => Hash::make('SecretPass123!'),
            'role' => $role,
            'is_admin' => $role === 'admin',
            'status' => $status,
        ]);
    }

    private function makeOrder(string $orderNumber): Order
    {
        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => 'Karim Customer',
            'customer_phone' => '01812345678',
            'customer_address' => 'Dhanmondi, Dhaka',
            'zone' => 'Inside Dhaka (৳70)',
            'shipping_fee' => 70,
            'subtotal' => 1150,
            'total' => 1220,
            'status' => 'Pending',
            'status_step' => 1,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_name' => 'Argentina 26/27 Home Jersey',
            'size' => 'L',
            'qty' => 1,
            'unit_price' => 1150,
            'total_price' => 1150,
        ]);

        return $order;
    }

    public function test_staff_and_manager_can_access_admin_panel_while_inactive_or_customer_cannot(): void
    {
        $staff = $this->makeUser('staff', 'staff1@rabez.shop', 'Staff One');
        $manager = $this->makeUser('manager', 'mgr1@rabez.shop', 'Manager One');
        $inactiveStaff = $this->makeUser('staff', 'inactive@rabez.shop', 'Inactive Staff', 'inactive');
        $customer = $this->makeUser('customer', 'cust1@rabez.shop', 'Normal Customer');

        $this->actingAs($staff)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($manager)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($inactiveStaff)->get(route('admin.dashboard'))->assertRedirect(route('home'));
        $this->actingAs($customer)->get(route('admin.dashboard'))->assertRedirect(route('home'));
    }

    public function test_staff_exclusive_claim_blocks_second_staff_across_all_order_mutations(): void
    {
        $staffA = $this->makeUser('staff', 'rafiq@rabez.shop', 'Rafiq Staff');
        $staffB = $this->makeUser('staff', 'hasan@rabez.shop', 'Hasan Staff');
        $order = $this->makeOrder('RBZ-81001');

        // Staff A claims the unassigned order
        $claimA = $this->actingAs($staffA)->postJson(route('admin.orders.claim', $order->id));
        $claimA->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('order.handled_by_user_id', $staffA->id)
            ->assertJsonPath('order.handled_by_name', 'Rafiq Staff');

        // Staff B tries to claim, release, update status, edit full, book courier, or delete -> ALL return 423 Locked!
        $this->actingAs($staffB)->postJson(route('admin.orders.claim', $order->id))
            ->assertStatus(423)
            ->assertJsonPath('locked', true)
            ->assertJsonPath('handled_by_user_id', $staffA->id);

        $this->actingAs($staffB)->postJson(route('admin.orders.release', $order->id))
            ->assertStatus(423)
            ->assertJsonPath('locked', true);

        $this->actingAs($staffB)->postJson(route('admin.orders.status', $order->id), ['status' => 'Printing'])
            ->assertStatus(423)
            ->assertJsonPath('locked', true);

        $this->actingAs($staffB)->postJson(route('admin.orders.update', $order->id), ['customer_name' => 'Hack Name'])
            ->assertStatus(423);

        $this->actingAs($staffB)->postJson(route('admin.orders.update_full', $order->id), ['customer_name' => 'Hack Full'])
            ->assertStatus(423);

        $this->actingAs($staffB)->postJson(route('admin.orders.book', $order->id), ['courier' => 'Steadfast'])
            ->assertStatus(423);

        $this->actingAs($staffB)->deleteJson(route('admin.orders.delete', $order->id))
            ->assertStatus(423);

        $this->actingAs($staffB)->postJson(route('admin.orders.bulk_delete'), ['ids' => [$order->id]])
            ->assertStatus(423);

        // Order remains untouched & still assigned to Staff A
        $fresh = $order->fresh();
        $this->assertSame($staffA->id, (int) $fresh->handled_by_user_id);
        $this->assertSame('Pending', $fresh->status);

        // Staff A can modify and book courier on their own claimed order
        $this->actingAs($staffA)->postJson(route('admin.orders.update', $order->id), ['status' => 'Printing'])
            ->assertOk()
            ->assertJsonPath('order.status', 'Printing');
    }

    public function test_updating_unassigned_order_auto_claims_it_and_releasing_unlocks_for_team(): void
    {
        $staffA = $this->makeUser('staff', 'auto-a@rabez.shop', 'Sajib Staff');
        $staffB = $this->makeUser('staff', 'auto-b@rabez.shop', 'Nabil Staff');
        $order = $this->makeOrder('RBZ-81002');

        // Booking an unassigned order auto-claims it for Sajib Staff
        $this->actingAs($staffA)->postJson(route('admin.orders.book', $order->id), ['courier' => 'Steadfast'])
            ->assertOk()
            ->assertJsonPath('order.handled_by_user_id', $staffA->id)
            ->assertJsonPath('order.handled_by_name', 'Sajib Staff');

        // Nabil Staff is blocked while locked by Sajib
        $this->actingAs($staffB)->postJson(route('admin.orders.status', $order->id), ['status' => 'Delivered'])
            ->assertStatus(423);

        // Sajib Staff releases the lock
        $this->actingAs($staffA)->postJson(route('admin.orders.release', $order->id))
            ->assertOk()
            ->assertJsonPath('order.handled_by_user_id', null);

        // Now Nabil Staff can claim it cleanly
        $this->actingAs($staffB)->postJson(route('admin.orders.claim', $order->id))
            ->assertOk()
            ->assertJsonPath('order.handled_by_user_id', $staffB->id);
    }

    public function test_main_admin_can_override_reassign_release_delete_and_manage_staff(): void
    {
        $mainAdmin = $this->makeUser('admin', 'boss@rabez.shop', 'Main Admin Boss');
        $staffA = $this->makeUser('staff', 'worker-a@rabez.shop', 'Worker A');
        $staffB = $this->makeUser('staff', 'worker-b@rabez.shop', 'Worker B');
        $order = $this->makeOrder('RBZ-81003');

        // Staff A locks the order
        $this->actingAs($staffA)->postJson(route('admin.orders.claim', $order->id))->assertOk();

        // Main Admin CAN still update/edit/override the locked order directly!
        $this->actingAs($mainAdmin)->postJson(route('admin.orders.update', $order->id), [
            'status' => 'Printing',
            'customer_name' => 'Boss Updated Name',
        ])->assertOk()
            ->assertJsonPath('order.status', 'Printing')
            ->assertJsonPath('order.customer_name', 'Boss Updated Name');

        // Staff CANNOT reassign orders or manage users/settings (403 Forbidden)
        $this->actingAs($staffA)->postJson(route('admin.orders.assign', $order->id), ['handled_by_user_id' => $staffB->id])
            ->assertStatus(403);
        $this->actingAs($staffA)->postJson(route('admin.users.store'), [
            'name' => 'Rogue', 'email' => 'rogue@rabez.shop', 'role' => 'admin', 'status' => 'active', 'password' => 'Secret123!',
        ])->assertStatus(403);
        $this->actingAs($staffA)->postJson(route('admin.settings.save'), ['shipping_flat_fee' => 999])
            ->assertStatus(403);

        // Main Admin reassigns order from Worker A to Worker B
        $this->actingAs($mainAdmin)->postJson(route('admin.orders.assign', $order->id), [
            'handled_by_user_id' => $staffB->id,
        ])->assertOk()
            ->assertJsonPath('order.handled_by_user_id', $staffB->id)
            ->assertJsonPath('order.handled_by_name', 'Worker B');

        // Worker A is now locked out; Worker B owns it
        $this->actingAs($staffA)->postJson(route('admin.orders.status', $order->id), ['status' => 'Shipped'])->assertStatus(423);
        $this->actingAs($staffB)->postJson(route('admin.orders.status', $order->id), ['status' => 'Shipped'])->assertOk();

        // Main Admin can also force take over or delete any locked order
        $this->actingAs($mainAdmin)->postJson(route('admin.orders.claim', $order->id), ['force' => true])
            ->assertOk()
            ->assertJsonPath('order.handled_by_user_id', $mainAdmin->id);

        $this->actingAs($mainAdmin)->postJson(route('admin.orders.assign', $order->id), ['handled_by_user_id' => $staffB->id])->assertOk();
        $this->actingAs($mainAdmin)->deleteJson(route('admin.orders.delete', $order->id))->assertOk();
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
