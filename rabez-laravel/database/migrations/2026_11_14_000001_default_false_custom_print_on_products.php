<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'has_custom_print')) {
            DB::table('products')->update(['has_custom_print' => false]);
        }
    }

    public function down(): void
    {
        // Intentionally left empty; toggle per-product via Admin Dashboard.
    }
};
