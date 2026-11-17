<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Product;

return new class extends Migration
{
    public function up(): void
    {
        Product::whereIn('sleeve', ['half-sleeve', 'full-sleeve', 'retro'])->update([
            'has_custom_print' => true
        ]);
    }

    public function down(): void
    {
        // Keep as-is or rollback if needed
    }
};
