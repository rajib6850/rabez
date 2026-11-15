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

        if (!Setting::where('key', 'meta_capi_test_code')->exists()) {
            Setting::set('meta_capi_test_code', 'TEST86756');
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        Setting::where('key', 'meta_capi_test_code')
            ->where('value', 'TEST86756')
            ->delete();
    }
};
