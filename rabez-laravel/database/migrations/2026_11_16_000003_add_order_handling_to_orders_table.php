<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('handled_by_user_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->string('handled_by_name', 100)->nullable()->after('handled_by_user_id');
            $table->timestamp('handled_at')->nullable()->after('handled_by_name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['handled_by_user_id']);
            $table->dropColumn(['handled_by_user_id', 'handled_by_name', 'handled_at']);
        });
    }
};
