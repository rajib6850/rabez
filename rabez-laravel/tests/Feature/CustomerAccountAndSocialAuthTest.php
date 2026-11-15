<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CustomerAccountAndSocialAuthTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::create([
            'name' => 'RABEZ Admin',
            'email' => 'admin-oauth@rabez.shop',
            'password' => Hash::make('AdminPass123!'),
            'role' => 'admin',
            'is_admin' => true,
            'status' => 'active',
        ]);
    }

    public function test_guest_is_redirected_from_account_and_customer_register_links_past_guest_orders(): void
    {
        $this->get('/account')->assertRedirect('/login');

        // Guest placed an order before registering
        $guestOrder = Order::create([
            'order_number' => 'RBZ-88101',
            'customer_name' => 'Tanvir Guest',
            'customer_phone' => '01712345678',
            'customer_address' => 'Mirpur 10, Dhaka',
            'zone' => 'Inside Dhaka',
            'shipping_fee' => 70,
            'subtotal' => 1150,
            'total' => 1220,
            'status' => 'Printing',
            'status_step' => 2,
            'courier' => 'Steadfast Courier',
            'consignment_id' => 'SF-9091',
        ]);

        OrderItem::create([
            'order_id' => $guestOrder->id,
            'product_name' => 'Real Madrid 26/27 Home Jersey',
            'size' => 'L',
            'qty' => 1,
            'unit_price' => 1150,
            'total_price' => 1150,
            'custom_name' => 'VINICIUS',
            'custom_number' => '7',
        ]);

        $res = $this->post('/register', [
            'name' => 'Tanvir Ahmed',
            'email' => 'tanvir@example.com',
            'phone' => '+8801712345678',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $res->assertRedirect(route('account.dashboard'));

        $user = User::where('email', 'tanvir@example.com')->firstOrFail();
        $this->assertSame('01712345678', $user->phone);
        $this->assertSame($user->id, $guestOrder->fresh()->user_id);

        $this->actingAs($user)
            ->get('/account')
            ->assertOk()
            ->assertSee('RBZ-88101')
            ->assertSee('Real Madrid 26/27 Home Jersey')
            ->assertSee('VINICIUS #7')
            ->assertSee('Mirpur 10, Dhaka');
    }

    public function test_login_redirects_customer_to_account_and_admin_to_admin_dashboard(): void
    {
        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer-login@example.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'customer',
            'is_admin' => false,
            'status' => 'active',
        ]);

        $this->post('/login', [
            'email' => $customer->email,
            'password' => 'SecretPass123!',
        ])->assertRedirect(route('account.dashboard'));

        $this->post('/logout');

        $admin = $this->makeAdmin();

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'AdminPass123!',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_customer_profile_and_password_updates_work_and_autofill_checkout(): void
    {
        $user = User::create([
            'name' => 'Hasan Ali',
            'email' => 'hasan@example.com',
            'password' => Hash::make('OldPassword123!'),
            'role' => 'customer',
            'is_admin' => false,
            'status' => 'active',
        ]);

        // Unlinked guest order on the phone number the user is about to save
        $pastOrder = Order::create([
            'order_number' => 'RBZ-77202',
            'customer_name' => 'Hasan Ali',
            'customer_phone' => '01811223344',
            'customer_address' => 'Chawkbazar, Chattogram',
            'zone' => 'Outside Dhaka',
            'shipping_fee' => 130,
            'subtotal' => 1450,
            'total' => 1580,
            'status' => 'Delivered',
            'status_step' => 5,
        ]);

        $this->actingAs($user)
            ->post('/account/profile', [
                'name' => 'Hasan Ali Khan',
                'phone' => '8801811223344',
                'address' => 'House 45, Road 7, Nasirabad, Chattogram',
            ])
            ->assertRedirect(route('account.dashboard'));

        $user->refresh();
        $this->assertSame('Hasan Ali Khan', $user->name);
        $this->assertSame('01811223344', $user->phone);
        $this->assertSame('House 45, Road 7, Nasirabad, Chattogram', $user->address);
        $this->assertSame($user->id, $pastOrder->fresh()->user_id);

        // Checkout page auto-fills saved profile details
        $this->actingAs($user)
            ->get('/checkout')
            ->assertOk()
            ->assertSee('value="Hasan Ali Khan"', false)
            ->assertSee('value="01811223344"', false)
            ->assertSee('value="House 45, Road 7, Nasirabad, Chattogram"', false);

        // Password update requires valid current_password
        $this->actingAs($user)
            ->from('/account')
            ->post('/account/password', [
                'current_password' => 'WrongPassword!',
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ])
            ->assertRedirect('/account')
            ->assertSessionHasErrors('current_password');

        $this->actingAs($user)
            ->post('/account/password', [
                'current_password' => 'OldPassword123!',
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ])
            ->assertRedirect(route('account.dashboard'));

        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
    }

    public function test_authenticated_checkout_links_order_and_backfills_missing_phone_and_address(): void
    {
        $user = User::create([
            'name' => 'OAuth User Without Phone',
            'email' => 'oauth-user@example.com',
            'password' => Hash::make('RandomSecret123!'),
            'role' => 'customer',
            'is_admin' => false,
            'status' => 'active',
        ]);

        $product = Product::create([
            'name' => 'Argentina 2026 Home Jersey',
            'slug' => 'argentina-2026-home-test',
            'sku' => 'RBZ-ARG-26',
            'price' => 1150,
            'club' => 'argentina',
            'sleeve' => 'half-sleeve',
            'image' => 'assets/images/products/real-madrid-home-hs.jpg',
            'is_ready_stock' => true,
        ]);

        Setting::set('meta_capi_token', '');

        $res = $this->actingAs($user)->postJson('/checkout', [
            'customer_name' => 'OAuth User',
            'customer_phone' => '01912345678',
            'customer_address' => 'Taltali, Barguna',
            'shipping_zone' => 130,
            'cart_items' => [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'size' => 'L',
                    'qty' => 1,
                    'price' => 1150,
                ],
            ],
        ]);

        $res->assertOk()->assertJsonPath('success', true);
        $orderNumber = $res->json('order_id');

        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $this->assertSame($user->id, $order->user_id);

        $user->refresh();
        $this->assertSame('01912345678', $user->phone);
        $this->assertSame('Taltali, Barguna', $user->address);

        $this->actingAs($user)
            ->get('/account')
            ->assertOk()
            ->assertSee($orderNumber)
            ->assertSee('Argentina 2026 Home Jersey');
    }

    public function test_google_and_facebook_oauth_settings_redirect_and_callback(): void
    {
        // 1. Login and Register pages display both social login buttons
        $this->get('/login')
            ->assertOk()
            ->assertSee('Continue with Google')
            ->assertSee('Continue with Facebook');

        $this->get('/register')
            ->assertOk()
            ->assertSee('Continue with Google')
            ->assertSee('Continue with Facebook');

        // 2. Without configured credentials, social redirect returns to /login with a friendly error
        $this->get('/auth/google/redirect')
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->get('/auth/facebook/redirect')
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        // 3. Admin saves Google & Facebook OAuth credentials via /admin/settings
        $admin = $this->makeAdmin();
        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('name="google_client_id"', false)
            ->assertSee('name="google_client_secret"', false)
            ->assertSee('name="facebook_client_id"', false)
            ->assertSee('name="facebook_client_secret"', false);

        $this->actingAs($admin)
            ->postJson('/admin/settings', [
                'google_client_id' => ' test-google-client-id ',
                'google_client_secret' => ' test-google-secret ',
                'facebook_client_id' => ' test-fb-app-id ',
                'facebook_client_secret' => ' test-fb-secret ',
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSame('test-google-client-id', Setting::get('google_client_id'));
        $this->assertSame('test-google-secret', Setting::get('google_client_secret'));
        $this->assertSame('test-fb-app-id', Setting::get('facebook_client_id'));
        $this->assertSame('test-fb-secret', Setting::get('facebook_client_secret'));

        $this->post('/logout');
        $this->assertGuest();

        // 4. Google OAuth Redirect sets state and redirects to Google Accounts
        $googleRedirect = $this->get('/auth/google/redirect');
        $googleRedirect->assertStatus(302);
        $location = (string) $googleRedirect->headers->get('Location');
        $this->assertStringStartsWith('https://accounts.google.com/o/oauth2/v2/auth?', $location);
        $this->assertStringContainsString('client_id=test-google-client-id', $location);
        parse_str((string) parse_url($location, PHP_URL_QUERY), $googleQuery);
        $this->assertNotEmpty($googleQuery['state'] ?? '');

        // Invalid state rejected cleanly
        $this->get('/auth/google/callback?state=invalid-state&code=fake-code')
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        // 5. Valid Google OAuth Callback creates customer account and logs into /account
        Http::fake([
            'https://oauth2.googleapis.com/token' => Http::response([
                'access_token' => 'google-access-token-123',
            ], 200),
            'https://www.googleapis.com/oauth2/v3/userinfo' => Http::response([
                'sub' => 'google-sub-999111',
                'email' => 'rafi.google@example.com',
                'name' => 'Rafiul Islam',
                'picture' => 'https://example.com/rafi.jpg',
            ], 200),
            'https://graph.facebook.com/v21.0/oauth/access_token' => Http::response([
                'access_token' => 'fb-access-token-456',
            ], 200),
            'https://graph.facebook.com/v21.0/me*' => Http::response([
                'id' => 'fb-uid-555222',
                'name' => 'Sakib Facebook',
                'email' => 'sakib.fb@example.com',
                'picture' => [
                    'data' => [
                        'url' => 'https://example.com/sakib.jpg',
                    ],
                ],
            ], 200),
        ]);

        // Re-initiate redirect so fresh state is in session after failed attempt pulled it
        $freshRedirect = $this->get('/auth/google/redirect');
        parse_str((string) parse_url((string) $freshRedirect->headers->get('Location'), PHP_URL_QUERY), $freshQuery);

        $this->get('/auth/google/callback?state=' . urlencode($freshQuery['state']) . '&code=google-auth-code')
            ->assertRedirect(route('account.dashboard'));

        $googleUser = User::where('google_id', 'google-sub-999111')->firstOrFail();
        $this->assertSame('rafi.google@example.com', $googleUser->email);
        $this->assertSame('Rafiul Islam', $googleUser->name);
        $this->assertAuthenticatedAs($googleUser);

        $this->post('/logout');
        $this->assertGuest();

        // 6. Facebook OAuth Redirect & Callback
        $fbRedirect = $this->get('/auth/facebook/redirect');
        $fbRedirect->assertStatus(302);
        $fbLocation = (string) $fbRedirect->headers->get('Location');
        $this->assertStringStartsWith('https://www.facebook.com/v21.0/dialog/oauth?', $fbLocation);
        parse_str((string) parse_url($fbLocation, PHP_URL_QUERY), $fbQuery);
        $this->assertNotEmpty($fbQuery['state'] ?? '');

        $this->get('/auth/facebook/callback?state=' . urlencode($fbQuery['state']) . '&code=fb-auth-code')
            ->assertRedirect(route('account.dashboard'));

        $fbUser = User::where('facebook_id', 'fb-uid-555222')->firstOrFail();
        $this->assertSame('sakib.fb@example.com', $fbUser->email);
        $this->assertSame('Sakib Facebook', $fbUser->name);
        $this->assertAuthenticatedAs($fbUser);
    }

    public function test_privacy_policy_and_terms_include_oauth_and_data_deletion_disclosures(): void
    {
        $this->get('/privacy-policy')
            ->assertOk()
            ->assertSee('Google &amp; Facebook Sign-In (OAuth 2.0) Data', false)
            ->assertSee('Account &amp; Data Deletion Requests', false)
            ->assertSee('rajib6850@gmail.com');

        $this->get('/terms-and-conditions')
            ->assertOk()
            ->assertSee('Customer Accounts &amp; Social Sign-In (Google / Facebook)', false)
            ->assertSee('rabez.shop/account');
    }
}
