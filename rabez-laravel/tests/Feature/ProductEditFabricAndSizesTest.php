<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductEditFabricAndSizesTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdminUser(): User
    {
        return User::create([
            'name' => 'Test Admin',
            'email' => 'admin-prodtest@rabez.shop',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'is_admin' => true,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_create_and_update_product_fabric_and_sizes_in_stock(): void
    {
        $admin = $this->makeAdminUser();

        // 1. Create product with explicit fabric and subset of sizes in stock
        $createRes = $this->actingAs($admin)->postJson('/admin/products', [
            'name' => 'Argentina 2026 Jersey',
            'sku' => 'RBZ-ARG-TEST1',
            'price' => 1150,
            'regular_price' => 1350,
            'club' => 'argentina',
            'sleeve' => 'half-sleeve',
            'fabric' => '195 GSM Micro-Mesh Jacquard',
            'all_sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
            'sizes' => ['M', 'L'],
            'has_custom_print' => true,
        ]);

        $createRes->assertOk()->assertJsonPath('success', true);
        $productId = $createRes->json('product.id');

        $product = Product::with('variants')->findOrFail($productId);
        $this->assertSame('195 GSM Micro-Mesh Jacquard', $product->fabric);
        $this->assertEqualsCanonicalizing(
            ['M', 'L'],
            $product->variants->where('is_in_stock', true)->pluck('size')->all()
        );
        $this->assertEqualsCanonicalizing(
            ['S', 'XL', 'XXL'],
            $product->variants->where('is_in_stock', false)->pluck('size')->all()
        );

        // 2. Update fabric and toggle different sizes in stock via edit endpoint
        $updateRes = $this->actingAs($admin)->postJson("/admin/products/{$productId}/update", [
            'fabric' => '220 GSM Aeroready Double-Knit',
            'all_sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
            'sizes' => ['S', 'XL', 'XXL'],
        ]);

        $updateRes->assertOk()->assertJsonPath('success', true);
        $product->refresh()->load('variants');
        $this->assertSame('220 GSM Aeroready Double-Knit', $product->fabric);
        $this->assertEqualsCanonicalizing(
            ['S', 'XL', 'XXL'],
            $product->variants->where('is_in_stock', true)->pluck('size')->all()
        );
        $this->assertEqualsCanonicalizing(
            ['M', 'L'],
            $product->variants->where('is_in_stock', false)->pluck('size')->all()
        );

        // 3. Mark all sizes out of stock cleanly (must not fall back to all sizes in stock)
        $soldOutRes = $this->actingAs($admin)->postJson("/admin/products/{$productId}/update", [
            'all_sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
            'sizes' => [],
        ]);

        $soldOutRes->assertOk();
        $product->refresh()->load('variants');
        $this->assertCount(0, $product->variants->where('is_in_stock', true));

        // 4. Switch category to footwear and verify sizes sync cleanly without stale S..XXL rows
        $bootRes = $this->actingAs($admin)->postJson("/admin/products/{$productId}/update", [
            'sleeve' => 'footwear',
            'fabric' => 'Textured Synthetic Upper',
            'all_sizes' => ['39', '40', '41', '42', '43', '44'],
            'sizes' => ['41', '42'],
        ]);

        $bootRes->assertOk();
        $product->refresh()->load('variants');
        $this->assertEqualsCanonicalizing(['39', '40', '41', '42', '43', '44'], $product->variants->pluck('size')->all());
        $this->assertEqualsCanonicalizing(['41', '42'], $product->variants->where('is_in_stock', true)->pluck('size')->all());

        // 5. Verify storefront product page renders updated fabric and disabled out-of-stock swatches
        $pageRes = $this->get('/product/' . $product->slug);
        $pageRes->assertOk()
            ->assertSee('Textured Synthetic Upper')
            ->assertSee('is-out-of-stock')
            ->assertSee('data-size="41"', false);
    }

    public function test_admin_dashboard_view_includes_edit_fabric_and_sizes_controls(): void
    {
        $admin = $this->makeAdminUser();

        Product::create([
            'name' => 'Brazil Retro Kit',
            'slug' => 'brazil-retro-kit',
            'sku' => 'RBZ-BRA-01',
            'club' => 'all',
            'sleeve' => 'retro',
            'fabric' => '210 GSM Pique Knit',
            'price' => 1150,
            'regular_price' => 1350,
            'image' => '/assets/images/products/real-madrid-home-hs.jpg',
        ]);

        $this->actingAs($admin)->get('/admin')
            ->assertOk()
            ->assertSee('id="edit-prod-fabric"', false)
            ->assertSee('id="edit-prod-sizes"', false)
            ->assertSee('210 GSM Pique Knit');
    }
}
