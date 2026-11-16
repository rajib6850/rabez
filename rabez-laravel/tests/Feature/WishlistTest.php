<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    private function makeCustomer(string $email = 'wishlist@example.com'): User
    {
        return User::create([
            'name' => 'Wishlist Customer',
            'email' => $email,
            'password' => Hash::make('Password123!'),
            'role' => 'customer',
            'is_admin' => false,
            'status' => 'active',
        ]);
    }

    private function makeProduct(array $overrides = []): Product
    {
        $idx = Product::count() + 1;

        return Product::create(array_merge([
            'name' => "Test Jersey {$idx}",
            'slug' => "test-jersey-{$idx}",
            'sku' => "RBZ-TEST-{$idx}",
            'club' => 'real-madrid',
            'club_label' => 'Real Madrid',
            'sleeve' => 'half-sleeve',
            'sleeve_label' => 'Half Sleeve',
            'fabric' => '190 GSM Jacquard Mesh',
            'image' => 'assets/images/products/real-madrid-home-hs.jpg',
            'price' => 1150,
            'regular_price' => 1350,
            'is_featured' => true,
            'is_ready_stock' => true,
            'has_custom_print' => true,
        ], $overrides));
    }

    public function test_wishlist_page_renders_and_filters_valid_shared_items(): void
    {
        $prodA = $this->makeProduct(['name' => 'Real Madrid Home Jersey']);
        $prodB = $this->makeProduct(['name' => 'Argentina Away Jersey']);

        $res = $this->get('/wishlist?items=' . $prodA->id . ',999999,' . $prodB->id);

        $res->assertOk()
            ->assertSee('My Saved Jerseys &amp; Kits', false)
            ->assertSee('Saved Jerseys')
            ->assertSee('Move All In-Stock to Bag')
            ->assertSee('window.RABEZ_SHARED_WISHLIST = [' . $prodA->id . ',' . $prodB->id . ']', false);
    }

    public function test_guest_wishlist_sync_validates_product_ids(): void
    {
        $prod = $this->makeProduct();

        $res = $this->postJson('/api/wishlist/sync', [
            'product_ids' => [$prod->id, 999999, $prod->id],
            'sizes' => [
                (string) $prod->id => 'XL',
                '999999' => 'L',
            ],
        ]);

        $res->assertOk()
            ->assertJson([
                'success' => true,
                'guest' => true,
                'product_ids' => [$prod->id],
                'count' => 1,
            ]);
    }

    public function test_authenticated_user_can_toggle_save_size_and_sync_wishlist(): void
    {
        $user = $this->makeCustomer();
        $prod1 = $this->makeProduct();
        $prod2 = $this->makeProduct();

        // Add product 1 with size M
        $addRes = $this->actingAs($user)->postJson('/api/wishlist/toggle', [
            'product_id' => $prod1->id,
            'size' => 'M',
        ]);

        $addRes->assertOk()
            ->assertJson([
                'success' => true,
                'action' => 'added',
                'product_ids' => [$prod1->id],
                'count' => 1,
            ]);

        $this->assertDatabaseHas('wishlist_items', [
            'user_id' => $user->id,
            'product_id' => $prod1->id,
            'preferred_size' => 'M',
        ]);

        // Update preferred size to XL via save_size mode without removing item
        $sizeRes = $this->actingAs($user)->postJson('/api/wishlist/toggle', [
            'product_id' => $prod1->id,
            'size' => 'XL',
            'mode' => 'save_size',
        ]);

        $sizeRes->assertOk()
            ->assertJson([
                'success' => true,
                'action' => 'updated',
                'count' => 1,
            ]);

        $this->assertDatabaseHas('wishlist_items', [
            'user_id' => $user->id,
            'product_id' => $prod1->id,
            'preferred_size' => 'XL',
        ]);

        // Sync merges product 2 cleanly
        $syncRes = $this->actingAs($user)->postJson('/api/wishlist/sync', [
            'product_ids' => [$prod1->id, $prod2->id],
            'sizes' => [(string) $prod2->id => 'L'],
        ]);

        $syncRes->assertOk()
            ->assertJson(['success' => true, 'count' => 2]);

        $this->assertDatabaseHas('wishlist_items', [
            'user_id' => $user->id,
            'product_id' => $prod2->id,
            'preferred_size' => 'L',
        ]);

        // Storefront exposes synced IDs to authenticated user
        $this->actingAs($user)
            ->get('/wishlist')
            ->assertOk()
            ->assertSee('window.RABEZ_AUTH_WISHLIST = [', false);

        // Toggle removes product 1
        $remRes = $this->actingAs($user)->postJson('/api/wishlist/toggle', [
            'product_id' => $prod1->id,
        ]);

        $remRes->assertOk()
            ->assertJson([
                'success' => true,
                'action' => 'removed',
                'count' => 1,
            ]);

        $this->assertDatabaseMissing('wishlist_items', [
            'user_id' => $user->id,
            'product_id' => $prod1->id,
        ]);
    }

    public function test_authenticated_user_clear_only_clears_own_wishlist(): void
    {
        $user1 = $this->makeCustomer('u1@example.com');
        $user2 = $this->makeCustomer('u2@example.com');
        $prod = $this->makeProduct();

        WishlistItem::create(['user_id' => $user1->id, 'product_id' => $prod->id, 'preferred_size' => 'L']);
        WishlistItem::create(['user_id' => $user2->id, 'product_id' => $prod->id, 'preferred_size' => 'M']);

        $this->actingAs($user1)
            ->postJson('/api/wishlist/clear')
            ->assertOk()
            ->assertJson(['success' => true, 'count' => 0]);

        $this->assertDatabaseMissing('wishlist_items', ['user_id' => $user1->id]);
        $this->assertDatabaseHas('wishlist_items', ['user_id' => $user2->id, 'product_id' => $prod->id]);
    }
}
