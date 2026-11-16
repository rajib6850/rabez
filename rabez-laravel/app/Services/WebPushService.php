<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PushSubscription;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

/**
 * Pure-PHP Web Push Service implementing RFC 8291 (aes128gcm) payload encryption
 * and RFC 8292 (VAPID ES256) authentication with zero external composer packages.
 */
class WebPushService
{
    /**
     * Get (or lazily generate) the persistent VAPID keypair.
     *
     * @return array{public_key: string, private_pem: string}
     */
    public function getVapidKeys(): array
    {
        $publicKey = Setting::get('vapid_public_key');
        $privatePem = Setting::get('vapid_private_pem');

        if (!empty($publicKey) && !empty($privatePem) && openssl_pkey_get_private($privatePem)) {
            return [
                'public_key' => $publicKey,
                'private_pem' => $privatePem,
            ];
        }

        $sslArgs = $this->resolveOpenSslArgs();
        $key = openssl_pkey_new($sslArgs);

        if (!$key) {
            throw new \RuntimeException('Unable to generate VAPID EC P-256 keypair.');
        }

        openssl_pkey_export($key, $privatePem, null, $sslArgs);
        $details = openssl_pkey_get_details($key);
        $x = str_pad($details['ec']['x'], 32, "\x00", STR_PAD_LEFT);
        $y = str_pad($details['ec']['y'], 32, "\x00", STR_PAD_LEFT);
        $publicKey = $this->base64UrlEncode("\x04" . $x . $y);

        Setting::set('vapid_public_key', $publicKey);
        Setting::set('vapid_private_pem', $privatePem);

        return [
            'public_key' => $publicKey,
            'private_pem' => $privatePem,
        ];
    }

    public function getPublicKey(): string
    {
        return $this->getVapidKeys()['public_key'];
    }

    /**
     * Send background Web Push notification to all active admin/manager/staff subscriptions
     * when a new storefront order arrives.
     *
     * @return array{sent: int, failed: int, cleaned: int}
     */
    public function sendNewOrderPush(Order $order): array
    {
        $order->loadMissing('items');
        $itemsSummary = $order->items
            ->take(2)
            ->map(fn ($i) => ($i->qty ?: 1) . 'x ' . $i->product_name)
            ->implode(', ');

        $openCount = Order::whereIn('status', ['Pending', 'Printing'])->count();

        $payload = [
            'title' => "New Order #{$order->order_number} (৳" . number_format((float) $order->total) . ")",
            'body' => trim("{$order->customer_name} ({$order->customer_phone}) • " . ($itemsSummary ?: 'New Jersey Order')),
            'order_id' => $order->order_number,
            'db_id' => $order->id,
            'total' => (float) $order->total,
            'open_count' => $openCount,
            'url' => '/admin',
            'tag' => 'rabez-order-' . $order->order_number,
        ];

        $subs = PushSubscription::with('user')
            ->whereHas('user', function ($q) {
                $q->whereIn('role', ['admin', 'manager', 'staff'])
                    ->where(function ($sq) {
                        $sq->whereNull('status')->orWhere('status', '!=', 'inactive');
                    });
            })
            ->get();

        return $this->sendBatch($subs, $payload);
    }

    /**
     * Send a test Web Push payload to all registered subscriptions of one user.
     *
     * @return array{sent: int, failed: int, cleaned: int}
     */
    public function sendTestPushToUser(int $userId): array
    {
        $openCount = Order::whereIn('status', ['Pending', 'Printing'])->count();
        $payload = [
            'title' => 'RABEZ Background Push Active!',
            'body' => 'Mobile shutter alerts work even when Chrome/app is minimized.',
            'order_id' => 'TEST',
            'open_count' => max(1, $openCount),
            'url' => '/admin',
            'tag' => 'rabez-test-push-' . time(),
            'is_test' => true,
        ];

        $subs = PushSubscription::where('user_id', $userId)->get();
        return $this->sendBatch($subs, $payload);
    }

    /**
     * Encrypt payload cleanly for each subscriber and dispatch in parallel via cURL multi.
     *
     * @param \Illuminate\Support\Collection<int, PushSubscription> $subscriptions
     * @param array<string, mixed> $payload
     * @return array{sent: int, failed: int, cleaned: int}
     */
    public function sendBatch($subscriptions, array $payload): array
    {
        if ($subscriptions->isEmpty()) {
            return ['sent' => 0, 'failed' => 0, 'cleaned' => 0];
        }

        $keys = $this->getVapidKeys();
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $mh = curl_multi_init();
        $handles = [];

        foreach ($subscriptions as $sub) {
            try {
                $encryptedBody = $this->encryptPayload($json, $sub->public_key, $sub->auth_token);
                $jwt = $this->createVapidJwt($sub->endpoint, $keys['private_pem']);

                $ch = curl_init($sub->endpoint);
                curl_setopt_array($ch, [
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $encryptedBody,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 8,
                    CURLOPT_CONNECTTIMEOUT => 4,
                    CURLOPT_HTTPHEADER => [
                        'Authorization: vapid t=' . $jwt . ', k=' . $keys['public_key'],
                        'Content-Type: application/octet-stream',
                        'Content-Encoding: aes128gcm',
                        'TTL: 86400',
                        'Urgency: high',
                    ],
                ]);
                curl_multi_add_handle($mh, $ch);
                $handles[] = ['handle' => $ch, 'sub' => $sub];
            } catch (\Throwable $e) {
                Log::warning('WebPush encrypt failure: ' . $e->getMessage());
            }
        }

        if (empty($handles)) {
            curl_multi_close($mh);
            return ['sent' => 0, 'failed' => $subscriptions->count(), 'cleaned' => 0];
        }

        $running = null;
        do {
            curl_multi_exec($mh, $running);
            if ($running > 0) {
                curl_multi_select($mh, 0.25);
            }
        } while ($running > 0);

        $sent = 0;
        $failed = 0;
        $cleaned = 0;

        foreach ($handles as $entry) {
            $ch = $entry['handle'];
            /** @var PushSubscription $sub */
            $sub = $entry['sub'];
            $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($status >= 200 && $status < 300) {
                $sent++;
                $sub->update(['last_pushed_at' => now()]);
            } elseif (in_array($status, [404, 410], true)) {
                $cleaned++;
                $sub->delete();
            } else {
                $failed++;
            }

            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }

        curl_multi_close($mh);

        return ['sent' => $sent, 'failed' => $failed, 'cleaned' => $cleaned];
    }

    /**
     * Encrypt payload according to RFC 8291 (aes128gcm content encoding).
     */
    public function encryptPayload(string $payload, string $userPublicKeyB64, string $userAuthB64): string
    {
        $uaPub = $this->base64UrlDecode($userPublicKeyB64);
        $authSecret = $this->base64UrlDecode($userAuthB64);

        if (strlen($uaPub) !== 65 || $uaPub[0] !== "\x04") {
            throw new \InvalidArgumentException('Invalid client p256dh public key.');
        }

        // Convert uncompressed P-256 point into ASN.1 SPKI PEM for OpenSSL
        $spkiDer = hex2bin('3059301306072a8648ce3d020106082a8648ce3d030107034200') . $uaPub;
        $uaPem = "-----BEGIN PUBLIC KEY-----\n" . chunk_split(base64_encode($spkiDer), 64, "\n") . "-----END PUBLIC KEY-----\n";
        $uaKey = openssl_pkey_get_public($uaPem);
        if (!$uaKey) {
            throw new \RuntimeException('Unable to parse client public key.');
        }

        $localKey = openssl_pkey_new($this->resolveOpenSslArgs());
        $localDetails = openssl_pkey_get_details($localKey);
        $asPub = "\x04"
            . str_pad($localDetails['ec']['x'], 32, "\x00", STR_PAD_LEFT)
            . str_pad($localDetails['ec']['y'], 32, "\x00", STR_PAD_LEFT);

        $shared = openssl_pkey_derive($uaKey, $localKey, 32);
        if ($shared === false) {
            throw new \RuntimeException('ECDH key derivation failed.');
        }
        $sharedSecret = str_pad($shared, 32, "\x00", STR_PAD_LEFT);

        $salt = random_bytes(16);
        $keyInfo = "WebPush: info\x00" . $uaPub . $asPub;
        $ikm = hash_hkdf('sha256', $sharedSecret, 32, $keyInfo, $authSecret);
        $cek = hash_hkdf('sha256', $ikm, 16, "Content-Encoding: aes128gcm\x00", $salt);
        $nonce = hash_hkdf('sha256', $ikm, 12, "Content-Encoding: nonce\x00", $salt);

        $tag = '';
        $ciphertext = openssl_encrypt($payload . "\x02", 'aes-128-gcm', $cek, OPENSSL_RAW_DATA, $nonce, $tag, '', 16);
        if ($ciphertext === false) {
            throw new \RuntimeException('AES-128-GCM encryption failed.');
        }

        $header = $salt . pack('N', 4096) . chr(strlen($asPub)) . $asPub;
        return $header . $ciphertext . $tag;
    }

    /**
     * Build signed VAPID JWT (RFC 8292 ES256).
     */
    public function createVapidJwt(string $endpoint, string $privatePem, string $subject = 'mailto:admin@rabez.shop'): string
    {
        $parts = parse_url($endpoint);
        $aud = ($parts['scheme'] ?? 'https') . '://' . ($parts['host'] ?? '') . (isset($parts['port']) ? ':' . $parts['port'] : '');

        $header = $this->base64UrlEncode(json_encode(['typ' => 'JWT', 'alg' => 'ES256']));
        $claims = $this->base64UrlEncode(json_encode([
            'aud' => $aud,
            'exp' => time() + 43200,
            'sub' => $subject,
        ], JSON_UNESCAPED_SLASHES));

        $signingInput = $header . '.' . $claims;
        $privateKey = openssl_pkey_get_private($privatePem);
        if (!$privateKey || !openssl_sign($signingInput, $derSig, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new \RuntimeException('VAPID JWT signing failed.');
        }

        $rawSig = $this->derToRawEcdsaSig($derSig);
        return $signingInput . '.' . $this->base64UrlEncode($rawSig);
    }

    protected function derToRawEcdsaSig(string $der): string
    {
        $pos = 0;
        if (ord($der[$pos++]) !== 0x30) {
            throw new \RuntimeException('Invalid ECDSA DER signature.');
        }
        $seqLen = ord($der[$pos++]);
        if ($seqLen & 0x80) {
            $pos += ($seqLen & 0x7f);
        }
        if (ord($der[$pos++]) !== 0x02) {
            throw new \RuntimeException('Invalid DER R integer.');
        }
        $rLen = ord($der[$pos++]);
        $r = substr($der, $pos, $rLen);
        $pos += $rLen;

        if (ord($der[$pos++]) !== 0x02) {
            throw new \RuntimeException('Invalid DER S integer.');
        }
        $sLen = ord($der[$pos++]);
        $s = substr($der, $pos, $sLen);

        $r = str_pad(ltrim($r, "\x00"), 32, "\x00", STR_PAD_LEFT);
        $s = str_pad(ltrim($s, "\x00"), 32, "\x00", STR_PAD_LEFT);

        return substr($r, -32) . substr($s, -32);
    }

    public function resolveOpenSslArgs(): array
    {
        $args = [
            'curve_name' => 'prime256v1',
            'private_key_type' => OPENSSL_KEYTYPE_EC,
        ];

        $candidates = array_filter([
            getenv('OPENSSL_CONF') ?: null,
            dirname(PHP_BINARY) . DIRECTORY_SEPARATOR . 'extras' . DIRECTORY_SEPARATOR . 'ssl' . DIRECTORY_SEPARATOR . 'openssl.cnf',
            dirname(PHP_BINARY) . DIRECTORY_SEPARATOR . 'openssl.cnf',
        ]);

        foreach ($candidates as $path) {
            if (is_file($path)) {
                $args['config'] = $path;
                break;
            }
        }

        return $args;
    }

    public function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64UrlDecode(string $data): string
    {
        $padded = strtr($data, '-_', '+/');
        $mod = strlen($padded) % 4;
        if ($mod > 0) {
            $padded .= str_repeat('=', 4 - $mod);
        }
        return base64_decode($padded, true) ?: '';
    }
}
