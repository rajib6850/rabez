<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        if (!Setting::where('key', 'meta_pixel_id')->exists()) {
            Setting::set('meta_pixel_id', '1693763272380977');
        }

        if (!Setting::where('key', 'meta_capi_token')->exists()) {
            Setting::set('meta_capi_token', '');
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        Setting::where('key', 'meta_pixel_id')
            ->where('value', '1693763272380977')
            ->delete();
    }
};
