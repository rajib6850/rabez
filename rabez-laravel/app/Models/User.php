<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'phone', 'address', 'password', 'role', 'is_admin', 'status', 'avatar', 'google_id', 'facebook_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    /**
     * Normalize Bangladeshi phone numbers (e.g. +8801712345678 -> 01712345678).
     */
    public static function normalizeBdPhone(?string $raw): ?string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) ($raw ?? ''));
        if ($digits === '') {
            return null;
        }
        if (strlen($digits) === 13 && str_starts_with($digits, '8801')) {
            $digits = substr($digits, 2);
        }

        return $digits;
    }

    /**
     * Link unowned past guest orders placed with the user's normalized 11-digit phone.
     */
    public function linkGuestOrdersByPhone(?string $rawPhone = null): void
    {
        $normalized = self::normalizeBdPhone($rawPhone ?? $this->phone);
        if (!$normalized || !preg_match('/^01[3-9]\d{8}$/', $normalized)) {
            return;
        }

        Order::whereNull('user_id')
            ->where('customer_phone', $normalized)
            ->update(['user_id' => $this->id]);
    }

    /**
     * Check if user is an administrator.
     */
    public function isAdmin(): bool
    {
        return (bool)$this->is_admin || strtolower($this->role ?? '') === 'admin';
    }
}
