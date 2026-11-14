<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CmsPagesAndMenusTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdminUser(): User
    {
        return User::create([
            'name' => 'Test Admin',
            'email' => 'testadmin@rabez.shop',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'is_admin' => true,
            'status' => 'active',
        ]);
    }

    public function test_seeded_cms_pages_render_on_storefront(): void
    {
        $this->get('/privacy-policy')
            ->assertOk()
            ->assertSee('Privacy Policy')
            ->assertSee('Information We Collect');

        $this->get('/terms-and-conditions')
            ->assertOk()
            ->assertSee('Terms & Conditions');

        $this->get('/return-exchange-policy')
            ->assertOk()
            ->assertSee('Return & Exchange Policy');
    }

    public function test_non_admin_cannot_access_cms_admin_endpoints(): void
    {
        $this->getJson('/admin/api/pages')->assertUnauthorized();
        $this->postJson('/admin/pages', ['title' => 'Unauthorized'])->assertUnauthorized();

        $customer = User::create([
            'name' => 'Customer',
            'email' => 'cust@rabez.shop',
            'password' => Hash::make('Password123!'),
            'role' => 'customer',
            'is_admin' => false,
            'status' => 'active',
        ]);

        $this->actingAs($customer)
            ->post('/admin/pages', ['title' => 'Blocked'])
            ->assertRedirect('/');
    }

    public function test_admin_can_crud_cms_pages_with_reserved_slug_and_xss_protection(): void
    {
        $admin = $this->makeAdminUser();

        // Reserved slug blocked
        $this->actingAs($admin)
            ->postJson('/admin/pages', [
                'title' => 'Fake Shop',
                'slug' => 'shop',
                'is_published' => true,
            ])
            ->assertStatus(422);

        // Create page with mixed safe HTML and unsafe script
        $createRes = $this->actingAs($admin)
            ->postJson('/admin/pages', [
                'title' => 'About RABEZ Store',
                'slug' => 'about-rabez',
                'excerpt' => 'Our jersey story.',
                'content' => '<h2>Authentic Thai Quality</h2><p onclick="alert(1)">Every kit is checked.</p><script>alert("xss")</script>',
                'is_published' => true,
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('page.slug', 'about-rabez');

        $pageId = $createRes->json('page.id');

        $storefrontRes = $this->get('/about-rabez')->assertOk();
        $storefrontRes->assertSee('About RABEZ Store');
        $storefrontRes->assertSee('Authentic Thai Quality');
        $storefrontRes->assertDontSee('<script>alert("xss")</script>', false);
        $storefrontRes->assertDontSee('onclick="alert(1)"', false);

        // Unpublish -> guest gets 404, admin gets 200 with ?preview=1
        $this->actingAs($admin)
            ->postJson("/admin/pages/{$pageId}/update", [
                'title' => 'About RABEZ Store Draft',
                'slug' => 'about-rabez',
                'content' => '<p>Updated draft copy.</p>',
                'is_published' => false,
            ])
            ->assertOk()
            ->assertJsonPath('page.is_published', false);

        auth()->logout();
        $this->get('/about-rabez')->assertNotFound();

        $this->actingAs($admin)
            ->get('/about-rabez?preview=1')
            ->assertOk()
            ->assertSee('Draft Preview')
            ->assertSee('Updated draft copy.');

        // Delete page
        $this->actingAs($admin)
            ->deleteJson("/admin/pages/{$pageId}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('pages', ['id' => $pageId]);
    }

    public function test_admin_can_build_and_reorder_multi_level_menus_with_cycle_protection(): void
    {
        $admin = $this->makeAdminUser();

        $page = Page::create([
            'title' => 'Size Help Guide',
            'slug' => 'size-help-guide',
            'content' => '<p>Size measurements guide</p>',
            'is_published' => true,
        ]);

        // Level 1 Parent
        $l1 = $this->actingAs($admin)
            ->postJson('/admin/menus', [
                'location' => 'header',
                'title' => 'Special Drops',
                'type' => 'custom',
                'url' => '#',
                'badge_text' => 'HOT',
                'is_active' => true,
            ])
            ->assertOk()
            ->json('item');

        // Level 2 Child
        $l2 = $this->actingAs($admin)
            ->postJson('/admin/menus', [
                'location' => 'header',
                'parent_id' => $l1['id'],
                'title' => 'League Kits',
                'type' => 'custom',
                'url' => '/shop',
                'is_active' => true,
            ])
            ->assertOk()
            ->json('item');

        // Level 3 Grandchild linked to CMS Page
        $l3 = $this->actingAs($admin)
            ->postJson('/admin/menus', [
                'location' => 'header',
                'parent_id' => $l2['id'],
                'title' => 'Size Help Link',
                'type' => 'page',
                'page_id' => $page->id,
                'is_active' => true,
            ])
            ->assertOk()
            ->json('item');

        // Cycle protection: cannot move L1 inside L3
        $this->actingAs($admin)
            ->postJson("/admin/menus/{$l1['id']}/update", [
                'location' => 'header',
                'parent_id' => $l3['id'],
                'title' => 'Special Drops',
                'type' => 'custom',
                'url' => '#',
            ])
            ->assertStatus(422);

        // Verify tree depth and rendered storefront DOM contains all 3 levels
        $tree = MenuItem::buildTree('header', true);
        $special = collect($tree)->firstWhere('id', $l1['id']);
        $this->assertNotNull($special);
        $this->assertSame('League Kits', $special['children'][0]['title']);
        $this->assertSame('Size Help Link', $special['children'][0]['children'][0]['title']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Special Drops')
            ->assertSee('League Kits')
            ->assertSee('Size Help Link')
            ->assertSee('/size-help-guide');

        // Reorder payload reparents L3 directly under L1 cleanly
        $headerIds = MenuItem::where('location', 'header')->orderBy('sort_order')->get();
        $payload = $headerIds->values()->map(function (MenuItem $m, int $idx) use ($l1, $l3) {
            return [
                'id' => $m->id,
                'parent_id' => $m->id === $l3['id'] ? $l1['id'] : $m->parent_id,
                'sort_order' => ($idx + 1) * 10,
            ];
        })->all();

        $this->actingAs($admin)
            ->postJson('/admin/menus/reorder', [
                'location' => 'header',
                'items' => $payload,
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertEquals($l1['id'], MenuItem::find($l3['id'])->parent_id);

        // Delete L1 cascades to descendants safely
        $this->actingAs($admin)
            ->deleteJson("/admin/menus/{$l1['id']}")
            ->assertOk();

        $this->assertDatabaseMissing('menu_items', ['id' => $l1['id']]);
        $this->assertDatabaseMissing('menu_items', ['id' => $l2['id']]);
        $this->assertDatabaseMissing('menu_items', ['id' => $l3['id']]);
    }
}
