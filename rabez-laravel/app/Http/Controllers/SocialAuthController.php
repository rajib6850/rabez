<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    private const PROVIDERS = [
        'google' => [
            'auth_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
            'token_url' => 'https://oauth2.googleapis.com/token',
            'profile_url' => 'https://www.googleapis.com/oauth2/v3/userinfo',
            'scope' => 'openid email profile',
        ],
        'facebook' => [
            'auth_url' => 'https://www.facebook.com/v21.0/dialog/oauth',
            'token_url' => 'https://graph.facebook.com/v21.0/oauth/access_token',
            'profile_url' => 'https://graph.facebook.com/v21.0/me?fields=id,name,email,picture.type(large)',
            'scope' => 'email,public_profile',
        ],
    ];

    /**
     * Resolve OAuth 2.0 client credentials from Admin Settings or config fallback.
     */
    private function getCredentials(string $provider): array
    {
        $clientId = trim((string) (Setting::get("{$provider}_client_id") ?: config("services.{$provider}.client_id", '')));
        $clientSecret = trim((string) (Setting::get("{$provider}_client_secret") ?: config("services.{$provider}.client_secret", '')));

        return [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect' => route('auth.social.callback', ['provider' => $provider]),
        ];
    }

    /**
     * Redirect user to Google or Facebook OAuth consent screen.
     */
    public function redirect(Request $request, string $provider)
    {
        if (!isset(self::PROVIDERS[$provider])) {
            abort(404);
        }

        $creds = $this->getCredentials($provider);
        if ($creds['client_id'] === '' || $creds['client_secret'] === '') {
            $label = ucfirst($provider);
            return redirect()->route('login')->withErrors([
                'email' => "{$label} লগইন এখনো অ্যাক্টিভ করা হয়নি। অনুগ্রহ করে ইমেইল ও পাসওয়ার্ড দিয়ে লগইন করুন অথবা Admin Settings থেকে {$label} Client ID ও Secret যুক্ত করুন।",
            ]);
        }

        $state = Str::random(40);
        $request->session()->put('oauth_state', $state);
        $request->session()->put('oauth_provider', $provider);

        $cfg = self::PROVIDERS[$provider];
        $query = [
            'client_id' => $creds['client_id'],
            'redirect_uri' => $creds['redirect'],
            'response_type' => 'code',
            'scope' => $cfg['scope'],
            'state' => $state,
        ];

        if ($provider === 'google') {
            $query['prompt'] = 'select_account';
        }

        return redirect()->away($cfg['auth_url'] . '?' . http_build_query($query));
    }

    /**
     * Handle OAuth callback, verify CSRF state, fetch profile, and sign the user in.
     */
    public function callback(Request $request, string $provider)
    {
        if (!isset(self::PROVIDERS[$provider])) {
            abort(404);
        }

        if ($request->filled('error')) {
            return redirect()->route('login')->withErrors([
                'email' => ucfirst($provider) . ' লগইন বাতিল করা হয়েছে বা অনুমোদন পাওয়া যায়নি।',
            ]);
        }

        $expectedState = (string) $request->session()->pull('oauth_state', '');
        $expectedProvider = $request->session()->pull('oauth_provider');
        $incomingState = (string) $request->query('state', '');
        $code = (string) $request->query('code', '');

        if (
            $expectedState === '' ||
            $incomingState === '' ||
            !hash_equals($expectedState, $incomingState) ||
            ($expectedProvider !== null && $expectedProvider !== $provider) ||
            $code === ''
        ) {
            return redirect()->route('login')->withErrors([
                'email' => 'নিরাপত্তা যাচাই ব্যর্থ হয়েছে। অনুগ্রহ করে আবার ' . ucfirst($provider) . ' দিয়ে লগইন করুন।',
            ]);
        }

        $creds = $this->getCredentials($provider);
        if ($creds['client_id'] === '' || $creds['client_secret'] === '') {
            return redirect()->route('login')->withErrors([
                'email' => ucfirst($provider) . ' OAuth credentials Admin Settings-এ সেট করা নেই।',
            ]);
        }

        $profile = $this->fetchSocialProfile($provider, $code, $creds);
        if ($profile === null || $profile['id'] === '') {
            return redirect()->route('login')->withErrors([
                'email' => ucfirst($provider) . ' থেকে প্রোফাইল তথ্য আনা যায়নি। কিছুক্ষণ পর আবার চেষ্টা করুন।',
            ]);
        }

        $email = $profile['email'] !== '' ? $profile['email'] : "{$provider}_{$profile['id']}@oauth.rabez.shop";
        $name = $profile['name'] !== '' ? strip_tags($profile['name']) : 'RABEZ Customer';
        $providerColumn = "{$provider}_id";

        $user = User::where($providerColumn, $profile['id'])
            ->orWhere('email', $email)
            ->first();

        if ($user) {
            if (($user->status ?? 'active') === 'inactive') {
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account is currently inactive. Please contact support.',
                ]);
            }

            $updates = [$providerColumn => $profile['id']];
            if (empty($user->avatar) && !empty($profile['avatar'])) {
                $updates['avatar'] = $profile['avatar'];
            }
            $user->update($updates);
        } else {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                $providerColumn => $profile['id'],
                'avatar' => $profile['avatar'],
                'role' => 'customer',
                'is_admin' => false,
                'status' => 'active',
                'password' => Hash::make(Str::random(32)),
            ]);
        }

        $user->linkGuestOrdersByPhone();

        Auth::login($user, true);
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('success', "Welcome back, {$user->name}!");
        }

        return redirect()->route('account.dashboard')
            ->with('success', "স্বাগতম, {$user->name}!");
    }

    /**
     * Exchange authorization code for an access token and retrieve the user's social profile.
     */
    private function fetchSocialProfile(string $provider, string $code, array $creds): ?array
    {
        $cfg = self::PROVIDERS[$provider];

        try {
            $tokenPayload = [
                'code' => $code,
                'client_id' => $creds['client_id'],
                'client_secret' => $creds['client_secret'],
                'redirect_uri' => $creds['redirect'],
            ];
            if ($provider === 'google') {
                $tokenPayload['grant_type'] = 'authorization_code';
            }

            $tokenRes = Http::asForm()->timeout(15)->post($cfg['token_url'], $tokenPayload);
            if (!$tokenRes->successful()) {
                Log::warning('Social OAuth token exchange failed', [
                    'provider' => $provider,
                    'status' => $tokenRes->status(),
                ]);
                return null;
            }

            $accessToken = (string) $tokenRes->json('access_token', '');
            if ($accessToken === '') {
                return null;
            }

            $profileRes = Http::withToken($accessToken)->timeout(15)->get($cfg['profile_url']);
            if (!$profileRes->successful()) {
                Log::warning('Social OAuth profile fetch failed', [
                    'provider' => $provider,
                    'status' => $profileRes->status(),
                ]);
                return null;
            }

            $raw = $profileRes->json() ?? [];
            $id = (string) ($provider === 'google' ? ($raw['sub'] ?? $raw['id'] ?? '') : ($raw['id'] ?? ''));
            $avatar = $provider === 'google'
                ? ($raw['picture'] ?? null)
                : ($raw['picture']['data']['url'] ?? null);

            return [
                'id' => $id,
                'email' => strtolower(trim((string) ($raw['email'] ?? ''))),
                'name' => trim((string) ($raw['name'] ?? '')),
                'avatar' => is_string($avatar) && $avatar !== '' ? $avatar : null,
            ];
        } catch (\Throwable $e) {
            Log::warning('Social OAuth exception: ' . $e->getMessage(), ['provider' => $provider]);
            return null;
        }
    }
}
