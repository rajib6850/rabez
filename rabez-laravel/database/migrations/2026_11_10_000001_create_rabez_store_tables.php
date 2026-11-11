<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('product_type')->default('jersey'); // jersey, apparel, footwear, accessory
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('club')->nullable();
            $table->string('club_label')->nullable();
            $table->string('sleeve')->nullable(); // half-sleeve, full-sleeve, retro, winter-hoodie
            $table->string('sleeve_label')->nullable();
            $table->decimal('regular_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2);
            $table->text('short_desc')->nullable();
            $table->longText('description')->nullable();
            $table->string('image');
            $table->json('gallery')->nullable();
            $table->json('badges')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_ready_stock')->default(true);
            $table->boolean('has_custom_print')->default(true);
            $table->timestamps();
        });

        // 3. Product Variants (Size & Inventory)
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('size'); // S, M, L, XL, XXL
            $table->integer('stock_qty')->default(20);
            $table->boolean('is_in_stock')->default(true);
            $table->timestamps();
        });

        // 4. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->string('zone')->default('Inside Dhaka (৳70)');
            $table->decimal('shipping_fee', 10, 2)->default(70.00);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('status')->default('Pending'); // Pending, Printing, Shipped, Delivered, Cancelled
            $table->integer('status_step')->default(1);
            $table->string('courier')->nullable();
            $table->string('consignment_id')->nullable();
            $table->string('event_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // 5. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('size');
            $table->integer('qty')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('custom_name')->nullable();
            $table->string('custom_number')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 6. Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};

