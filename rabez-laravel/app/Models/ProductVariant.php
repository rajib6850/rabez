<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_in_stock' => 'boolean',
        'stock_qty' => 'integer',
        'price' => 'decimal:2',
        'regular_price' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->price ?? $this->product->price ?? 0);
    }

    public function getEffectiveRegularPriceAttribute(): ?float
    {
        return $this->regular_price ? (float) $this->regular_price : ($this->product->regular_price ? (float) $this->product->regular_price : null);
    }
}
