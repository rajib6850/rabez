<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'shipping_fee' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'status_step' => 'integer',
        'handled_by_user_id' => 'integer',
        'handled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Determine whether this order is currently locked by another handler for the given actor.
     * Main Admins are never locked out of any order.
     */
    public function isLockedFor(?User $actor): bool
    {
        if (!$actor || $actor->isMainAdmin()) {
            return false;
        }

        if ($this->handled_by_user_id === null) {
            return false;
        }

        return (int) $this->handled_by_user_id !== (int) $actor->id;
    }
}

