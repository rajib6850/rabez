<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add options & has_variants to products
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'has_variants')) {
                $table->boolean('has_variants')->default(false)->after('has_custom_print');
            }
            if (!Schema::hasColumn('products', 'options')) {
                $table->json('options')->nullable()->after('has_variants');
            }
        });

        // 2. Add Shopify-style variant fields to product_variants
        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'title')) {
                $table->string('title')->nullable()->after('size');
            }
            if (!Schema::hasColumn('product_variants', 'option1')) {
                $table->string('option1')->nullable()->after('title');
            }
            if (!Schema::hasColumn('product_variants', 'option2')) {
                $table->string('option2')->nullable()->after('option1');
            }
            if (!Schema::hasColumn('product_variants', 'option3')) {
                $table->string('option3')->nullable()->after('option2');
            }
            if (!Schema::hasColumn('product_variants', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('option3');
            }
            if (!Schema::hasColumn('product_variants', 'regular_price')) {
                $table->decimal('regular_price', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('product_variants', 'sku')) {
                $table->string('sku')->nullable()->after('regular_price');
            }
            if (!Schema::hasColumn('product_variants', 'image')) {
                $table->string('image')->nullable()->after('sku');
            }
        });

        // 3. Add variant tracking to order_items
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'variant_id')) {
                $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            }
            if (!Schema::hasColumn('order_items', 'variant_title')) {
                $table->string('variant_title')->nullable()->after('size');
            }
        });

        // 4. Backfill existing product_variants so title, option1, price, sku match size
        try {
            $variants = DB::table('product_variants')->get();
            foreach ($variants as $v) {
                $prod = DB::table('products')->where('id', $v->product_id)->first();
                $size = $v->size ?: 'L';
                $prodPrice = $prod ? (float) $prod->price : 1150.00;
                $prodRegPrice = $prod && $prod->regular_price ? (float) $prod->regular_price : ($prodPrice + 200.00);
                $sku = $prod ? ($prod->sku . '-' . $size) : ('RBZ-' . $v->id);

                DB::table('product_variants')->where('id', $v->id)->update([
                    'title' => $v->title ?: $size,
                    'option1' => $v->option1 ?: $size,
                    'price' => $v->price ?: $prodPrice,
                    'regular_price' => $v->regular_price ?: $prodRegPrice,
                    'sku' => $v->sku ?: $sku,
                ]);
            }
        } catch (\Throwable $e) {
            // Ignore backfill errors in non-standard test runners
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'variant_id')) {
                $table->dropForeign(['variant_id']);
                $table->dropColumn('variant_id');
            }
            if (Schema::hasColumn('order_items', 'variant_title')) {
                $table->dropColumn('variant_title');
            }
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'option1',
                'option2',
                'option3',
                'price',
                'regular_price',
                'sku',
                'image',
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['has_variants', 'options']);
        });
    }
};
