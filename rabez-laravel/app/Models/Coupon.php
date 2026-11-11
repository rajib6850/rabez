<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = [];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function calculateDiscount(float $subtotal): float
    {
        if (!$this->is_active) {
            return 0;
        }

        if ($this->min_spend > 0 && $subtotal < $this->min_spend) {
            return 0;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 0;
        }

        if ($this->discount_type === 'percent') {
            return round(($subtotal * ($this->discount_value / 100)), 2);
        }

        return min($this->discount_value, $subtotal);
    }
}

