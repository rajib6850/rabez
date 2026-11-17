<?php

namespace Tests\Feature;

use App\Models\PushSubscription;
use App\Models\Setting;
use App\Models\User;
use App\Services\WebPushService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WebPushAndOnlineStatusTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role, string $email): User
    {
        return User::create([
            'name' => ucfirst($role) . ' Tester',
            'email' => $email,
            'password' => Hash::make('SecretPass123!'),
            'role' => $role,
            'is_admin' => $role === 'admin',
            'status' => 'active',
        ]);
    }

    public function test_vapid_keypair_jwt_and_rfc8291_encryption_work_end_to_end(): void
    {
        $push = app(WebPushService::class);
        $keys = $push->getVapidKeys();

        $rawPub = $push->base64UrlDecode($keys['public_key']);
        $this->assertSame(65, strlen($rawPub));
        $this->assertSame("\x04", $rawPub[0]);

        // Re-fetching returns the exact persisted keypair
        $this->assertSame($keys['public_key'], $push->getPublicKey());
        $this->assertNotEmpty(Setting::get('vapid_private_pem'));

        // Valid 3-segment ES256 VAPID JWT
        $jwt = $push->createVapidJwt('https://fcm.googleapis.com/fcm/send/demo-token', $keys['private_pem']);
        $parts = explode('.', $jwt);
        $this->assertCount(3, $parts);
        $header = json_decode($push->base64UrlDecode($parts[0]), true);
        $claims = json_decode($push->base64UrlDecode($parts[1]), true);
        $this->assertSame('ES256', $header['alg']);
        $this->assertSame('https://fcm.googleapis.com', $claims['aud']);
        $this->assertSame(64, strlen($push->base64UrlDecode($parts[2])));

        // Generate a real client ECDH P-256 keypair and verify RFC 8291 encryption output structure
        $clientKey = openssl_pkey_new($push->resolveOpenSslArgs());
        $clientDetails = openssl_pkey_get_details($clientKey);
        $clientPubRaw = "\x04"
            . str_pad($clientDetails['ec']['x'], 32, "\x00", STR_PAD_LEFT)
            . str_pad($clientDetails['ec']['y'], 32, "\x00", STR_PAD_LEFT);
        $clientAuth = random_bytes(16);

        $encrypted = $push->encryptPayload(
            json_encode(['title' => 'New Order', 'body' => 'Order #RBZ-101']),
            $push->base64UrlEncode($clientPubRaw),
            $push->base64UrlEncode($clientAuth)
        );

        // 16B salt + 4B rs + 1B idlen(65) + 65B pub = 86B header + ciphertext + 16B GCM tag
        $this->assertGreaterThan(86 + 16, strlen($encrypted));
        $this->assertSame(65, ord($encrypted[20]));
    }

    public function test_push_subscription_endpoints_persist_deduplicate_and_enforce_auth(): void
    {
        $endpoint = 'https://fcm.googleapis.com/fcm/send/sample-device-endpoint-xyz';
        $payload = [
            'endpoint' => $endpoint,
            'public_key' => str_repeat('A', 87),
            'auth_token' => str_repeat('B', 22),
            'content_encoding' => 'aes128gcm',
        ];

        $this->postJson(route('admin.push.subscribe'), $payload)->assertStatus(401);

        $customer = $this->makeUser('customer', 'cust-push@rabez.shop');
        $this->actingAs($customer)->postJson(route('admin.push.subscribe'), $payload)->assertStatus(403);

        $staff = $this->makeUser('staff', 'staff-push@rabez.shop');
        $res1 = $this->actingAs($staff)->postJson(route('admin.push.subscribe'), $payload);
        $res1->assertOk()->assertJsonPath('success', true);
        $this->assertSame(1, PushSubscription::count());

        // Re-subscribing same endpoint updates instead of duplicating rows
        $admin = $this->makeUser('admin', 'admin-push@rabez.shop');
        $this->actingAs($admin)->postJson(route('admin.push.subscribe'), $payload)->assertOk();
        $this->assertSame(1, PushSubscription::count());
        $this->assertSame($admin->id, (int) PushSubscription::first()->user_id);

        // Dashboard links Admin PWA manifest, app icon badging, bottom nav badge, and VAPID public key WITHOUT topbar online pill or leaking private key
        $dash = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dash->assertOk();
        $dash->assertDontSee('js-adm-online-pill', false);
        $dash->assertSee('/admin-manifest.json', false);
        $dash->assertSee('setAppBadge', false);
        $dash->assertSee('js-adm-bottom-orders-badge', false);
        $dash->assertSee('VAPID_PUBLIC_KEY', false);
        $dash->assertDontSee('BEGIN EC PRIVATE KEY', false);

        // Unsubscribe deletes cleanly
        $this->actingAs($admin)->postJson(route('admin.push.unsubscribe'), ['endpoint' => $endpoint])->assertOk();
        $this->assertSame(0, PushSubscription::count());
    }
}
