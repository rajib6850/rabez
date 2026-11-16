<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileSetupAndAvatarTest extends TestCase
{
    use RefreshDatabase;

    /** @var array<int, string> */
    private array $trackedAvatarFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->trackedAvatarFiles as $relPath) {
            if ($relPath && str_starts_with($relPath, '/uploads/avatars/')) {
                $full = public_path(ltrim($relPath, '/'));
                if (is_file($full)) {
                    @unlink($full);
                }
            }
        }
        parent::tearDown();
    }

    public function test_customer_can_upload_replace_and_remove_profile_picture(): void
    {
        $customer = User::create([
            'name' => 'Karim Ahmed',
            'email' => 'karim-avatar@rabez.shop',
            'password' => Hash::make('Secret123!'),
            'role' => 'customer',
            'is_admin' => false,
            'google_id' => 'google-test-123',
        ]);

        $file1 = UploadedFile::fake()->image('avatar1.jpg', 900, 900);
        $res1 = $this->actingAs($customer)->post(route('account.profile.update'), [
            'name' => 'Karim Updated',
            'phone' => '8801712345678',
            'address' => 'House 12, Mirpur, Dhaka',
            'avatar' => $file1,
        ]);

        $res1->assertRedirect(route('account.dashboard'));
        $customer->refresh();

        $this->assertSame('Karim Updated', $customer->name);
        $this->assertSame('01712345678', $customer->phone);
        $this->assertNotNull($customer->avatar);
        $this->assertStringStartsWith('/uploads/avatars/avatar-', $customer->avatar);

        $firstRelPath = $customer->avatar;
        $this->trackedAvatarFiles[] = $firstRelPath;
        $firstFullPath = public_path(ltrim($firstRelPath, '/'));
        $this->assertFileExists($firstFullPath);

        $page = $this->actingAs($customer)->get(route('account.dashboard'));
        $page->assertOk();
        $page->assertSee($firstRelPath, false);
        $page->assertSee('rabez-topbar-avatar', false);
        $page->assertSee('Sign-In &amp; Security Overview', false);
        $page->assertSee('Google Sign-In', false);
        $page->assertSee('Connected', false);

        // Replace avatar -> previous local file should be cleaned up
        $file2 = UploadedFile::fake()->image('avatar2.png', 500, 500);
        $res2 = $this->actingAs($customer)->post(route('account.profile.update'), [
            'name' => 'Karim Updated',
            'phone' => '01712345678',
            'address' => 'House 12, Mirpur, Dhaka',
            'avatar' => $file2,
        ]);
        $res2->assertRedirect(route('account.dashboard'));
        $customer->refresh();

        $secondRelPath = $customer->avatar;
        $this->trackedAvatarFiles[] = $secondRelPath;
        $this->assertNotSame($firstRelPath, $secondRelPath);
        $this->assertFileDoesNotExist($firstFullPath);
        $this->assertFileExists(public_path(ltrim($secondRelPath, '/')));

        // Remove avatar -> clears DB and deletes local file
        $res3 = $this->actingAs($customer)->post(route('account.profile.update'), [
            'name' => 'Karim Updated',
            'phone' => '01712345678',
            'address' => 'House 12, Mirpur, Dhaka',
            'remove_avatar' => '1',
        ]);
        $res3->assertRedirect(route('account.dashboard'));
        $customer->refresh();

        $this->assertNull($customer->avatar);
        $this->assertFileDoesNotExist(public_path(ltrim($secondRelPath, '/')));
    }

    public function test_external_oauth_avatar_removal_clears_column_safely(): void
    {
        $customer = User::create([
            'name' => 'Social User',
            'email' => 'social@rabez.shop',
            'password' => Hash::make('Secret123!'),
            'role' => 'customer',
            'is_admin' => false,
            'avatar' => 'https://lh3.googleusercontent.com/demo-avatar.jpg',
        ]);

        $res = $this->actingAs($customer)->post(route('account.profile.update'), [
            'name' => 'Social User',
            'phone' => '01812345678',
            'remove_avatar' => '1',
        ]);

        $res->assertRedirect(route('account.dashboard'));
        $this->assertNull($customer->fresh()->avatar);
    }

    public function test_admin_profile_and_password_endpoints_enforce_auth_and_persist_avatar(): void
    {
        $this->postJson(route('admin.profile.update'), ['name' => 'Guest'])->assertStatus(401);
        $this->postJson(route('admin.profile.password'), ['password' => 'NewPass123!'])->assertStatus(401);

        $customer = User::create([
            'name' => 'Customer Blocked',
            'email' => 'blocked@rabez.shop',
            'password' => Hash::make('Secret123!'),
            'role' => 'customer',
            'is_admin' => false,
        ]);

        $this->actingAs($customer)
            ->postJson(route('admin.profile.update'), ['name' => 'Hack', 'email' => 'blocked@rabez.shop'])
            ->assertStatus(403);
        $this->actingAs($customer)
            ->postJson(route('admin.profile.password'), ['current_password' => 'Secret123!', 'password' => 'NewPass123!', 'password_confirmation' => 'NewPass123!'])
            ->assertStatus(403);

        $admin = User::create([
            'name' => 'RABEZ Admin',
            'email' => 'admin-profile@rabez.shop',
            'password' => Hash::make('AdminPass123!'),
            'role' => 'admin',
            'is_admin' => true,
            'status' => 'active',
        ]);

        $avatarFile = UploadedFile::fake()->image('admin-dp.jpg', 750, 750);
        $updateRes = $this->actingAs($admin)->post(route('admin.profile.update'), [
            'name' => 'Md. Rajib Admin',
            'email' => 'rajib-admin@rabez.shop',
            'phone' => '8801912345678',
            'address' => 'Banani HQ, Dhaka',
            'avatar' => $avatarFile,
        ], ['Accept' => 'application/json']);

        $updateRes->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('user.name', 'Md. Rajib Admin')
            ->assertJsonPath('user.email', 'rajib-admin@rabez.shop')
            ->assertJsonPath('user.phone', '01912345678');

        $admin->refresh();
        $this->assertNotNull($admin->avatar);
        $this->assertStringStartsWith('/uploads/avatars/avatar-', $admin->avatar);
        $this->trackedAvatarFiles[] = $admin->avatar;
        $fullPath = public_path(ltrim($admin->avatar, '/'));
        $this->assertFileExists($fullPath);

        $dash = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dash->assertOk();
        $dash->assertSee('js-admin-profile-drawer', false);
        $dash->assertSee('Profile &amp; Avatar Setup', false);
        $dash->assertSee($admin->avatar, false);

        // Wrong current password returns 422
        $badPw = $this->actingAs($admin)->postJson(route('admin.profile.password'), [
            'current_password' => 'WrongPassword!',
            'password' => 'BrandNewPass99!',
            'password_confirmation' => 'BrandNewPass99!',
        ]);
        $badPw->assertStatus(422);

        // Correct password updates hash cleanly
        $goodPw = $this->actingAs($admin)->postJson(route('admin.profile.password'), [
            'current_password' => 'AdminPass123!',
            'password' => 'BrandNewPass99!',
            'password_confirmation' => 'BrandNewPass99!',
        ]);
        $goodPw->assertOk()->assertJsonPath('success', true);
        $this->assertTrue(Hash::check('BrandNewPass99!', $admin->fresh()->password));

        // Remove admin avatar
        $rmRes = $this->actingAs($admin)->postJson(route('admin.profile.update'), [
            'name' => 'Md. Rajib Admin',
            'email' => 'rajib-admin@rabez.shop',
            'remove_avatar' => true,
        ]);
        $rmRes->assertOk()->assertJsonPath('user.avatar_url', null);
        $this->assertNull($admin->fresh()->avatar);
        $this->assertFileDoesNotExist($fullPath);
    }
}
