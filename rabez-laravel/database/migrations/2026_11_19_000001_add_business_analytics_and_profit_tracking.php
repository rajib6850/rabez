<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'cost_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('cost_price', 10, 2)->nullable()->default(0)->after('price');
            });
        }

        if (Schema::hasTable('order_items') && !Schema::hasColumn('order_items', 'unit_cost')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->decimal('unit_cost', 10, 2)->nullable()->after('unit_price');
            });
        }

        if (!Schema::hasTable('business_expenses')) {
            Schema::create('business_expenses', function (Blueprint $table) {
                $table->id();
                $table->string('title', 180);
                $table->string('category', 60)->default('ads');
                $table->decimal('amount', 12, 2);
                $table->date('expense_date');
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('settings') && Setting::where('key', 'custom_print_cost')->doesntExist()) {
            Setting::set('custom_print_cost', '80');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('business_expenses');

        if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'unit_cost')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn('unit_cost');
            });
        }

        if (Schema::hasTable('products') && Schema::hasColumn('products', 'cost_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('cost_price');
            });
        }
    }
};
