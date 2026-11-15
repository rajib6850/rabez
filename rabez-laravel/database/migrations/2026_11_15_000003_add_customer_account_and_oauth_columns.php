<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'address')) {
                    $table->text('address')->nullable()->after('phone');
                }
                if (!Schema::hasColumn('users', 'google_id')) {
                    $table->string('google_id')->nullable()->index()->after('avatar');
                }
                if (!Schema::hasColumn('users', 'facebook_id')) {
                    $table->string('facebook_id')->nullable()->index()->after('google_id');
                }
            });
        }

        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'user_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            });
        }

        if (Schema::hasTable('settings')) {
            foreach ([
                'google_client_id',
                'google_client_secret',
                'facebook_client_id',
                'facebook_client_secret',
            ] as $key) {
                Setting::firstOrCreate(['key' => $key], ['value' => '']);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'user_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'facebook_id')) {
                    $table->dropIndex(['facebook_id']);
                    $table->dropColumn('facebook_id');
                }
                if (Schema::hasColumn('users', 'google_id')) {
                    $table->dropIndex(['google_id']);
                    $table->dropColumn('google_id');
                }
                if (Schema::hasColumn('users', 'address')) {
                    $table->dropColumn('address');
                }
            });
        }
    }
};
