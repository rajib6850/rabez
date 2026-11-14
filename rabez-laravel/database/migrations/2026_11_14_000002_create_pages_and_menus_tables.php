<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('excerpt', 500)->nullable();
                $table->longText('content')->nullable();
                $table->string('meta_title')->nullable();
                $table->string('meta_description', 500)->nullable();
                $table->boolean('is_published')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('menu_items')) {
            Schema::create('menu_items', function (Blueprint $table) {
                $table->id();
                $table->string('location', 40)->default('header')->index();
                $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
                $table->string('title');
                $table->string('type', 30)->default('custom'); // 'page', 'route', 'custom'
                $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
                $table->string('url', 500)->nullable();
                $table->string('badge_text', 32)->nullable();
                $table->string('target', 20)->default('_self');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        $now = now();

        if (Schema::hasTable('pages') && DB::table('pages')->count() === 0) {
            $privacyId = DB::table('pages')->insertGetId([
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'excerpt' => 'How RABEZ SPORTS BD collects, uses, and protects your order and delivery details.',
                'content' => <<<'HTML'
<h2>1. Information We Collect</h2>
<p>When you place an order or contact RABEZ SPORTS BD, we collect only the details needed to prepare and deliver your jersey safely across Bangladesh:</p>
<ul>
  <li><strong>Customer Details:</strong> Full name and active mobile phone number.</li>
  <li><strong>Delivery Address:</strong> District, Upazila/Thana, and house/road address for courier dispatch.</li>
  <li><strong>Order &amp; Print Notes:</strong> Jersey size, sleeve version, and optional custom player name/number specifications.</li>
</ul>
<h2>2. How We Use Your Information</h2>
<p>Your phone number and shipping address are shared strictly with our verified courier partners (such as Pathao or Steadfast) to complete cash-on-delivery (COD) drop-off and send order tracking updates.</p>
<h2>3. Data Protection &amp; Retention</h2>
<p>We never sell, rent, or trade customer contact details to third parties. Order invoices remain accessible via our Order Tracking page for customer convenience.</p>
<h2>4. Contact Us</h2>
<p>For any privacy questions or correction requests, message our support hotline on WhatsApp at <strong>+880 1612-359652</strong>.</p>
HTML,
                'meta_title' => 'Privacy Policy | RABEZ SPORTS BD',
                'meta_description' => 'Read the Privacy Policy of RABEZ SPORTS BD regarding order details, courier dispatch, and customer data protection.',
                'is_published' => true,
                'sort_order' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $termsId = DB::table('pages')->insertGetId([
                'title' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
                'excerpt' => 'Order confirmation, nationwide Cash on Delivery, and custom print terms for RABEZ SPORTS BD.',
                'content' => <<<'HTML'
<h2>1. Order Confirmation</h2>
<p>All orders placed on <strong>rabez.shop</strong> or via WhatsApp are confirmed based on ready-stock availability. For Cash on Delivery (COD) orders, please keep your phone reachable so our team or courier can confirm dispatch.</p>
<h2>2. Pricing &amp; Delivery Charges</h2>
<p>Product prices are listed in Bangladeshi Taka (BDT ৳). Nationwide delivery fees (Inside Dhaka and Outside Dhaka) are clearly shown at checkout before order submission.</p>
<h2>3. Custom Name &amp; Number Print Policy</h2>
<p>Jerseys ordered with custom player names or numbers are heat-pressed exclusively to order. Once printing starts, custom-printed jerseys cannot be cancelled, refunded, or exchanged unless a manufacturing or print defect occurs from our side.</p>
<h2>4. Open-Box Inspection</h2>
<p>Customers may inspect the parcel in front of the delivery rider upon arrival. Please contact us immediately at <strong>+880 1612-359652</strong> if any item arrives damaged or incorrect.</p>
HTML,
                'meta_title' => 'Terms & Conditions | RABEZ SPORTS BD',
                'meta_description' => 'Order confirmation, delivery charges, custom print rules, and service terms for RABEZ SPORTS BD.',
                'is_published' => true,
                'sort_order' => 20,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $returnId = DB::table('pages')->insertGetId([
                'title' => 'Return & Exchange Policy',
                'slug' => 'return-exchange-policy',
                'excerpt' => 'Easy size exchange and defect replacement guidelines for RABEZ SPORTS BD customers.',
                'content' => <<<'HTML'
<h2>1. 48-Hour Exchange Window</h2>
<p>If you receive a wrong size (non-custom jersey) or notice a stitching/fabric defect, notify us within <strong>48 hours of delivery</strong> via WhatsApp (<strong>+880 1612-359652</strong>) with your Order Code and clear photos.</p>
<h2>2. Condition for Exchange</h2>
<ul>
  <li>The jersey must be unused, unwashed, and include original hang-tags and packaging.</li>
  <li>Custom-printed name/number jerseys are eligible only if we printed the wrong name, number, or shipped an incorrect size.</li>
</ul>
<h2>3. Exchange Delivery Cost</h2>
<p>If the mistake or defect is on our end, RABEZ covers both-way courier charges. For voluntary size changes by the customer, standard return and re-delivery courier fees apply.</p>
HTML,
                'meta_title' => 'Return & Exchange Policy | RABEZ SPORTS BD',
                'meta_description' => 'Learn about RABEZ SPORTS BD 48-hour size exchange and defect replacement policy.',
                'is_published' => true,
                'sort_order' => 30,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $privacyId = DB::table('pages')->where('slug', 'privacy-policy')->value('id');
            $termsId = DB::table('pages')->where('slug', 'terms-and-conditions')->value('id');
            $returnId = DB::table('pages')->where('slug', 'return-exchange-policy')->value('id');
        }

        if (Schema::hasTable('menu_items') && DB::table('menu_items')->count() === 0) {
            // Header Primary Menu (with multi-level dropdown structure)
            DB::table('menu_items')->insert([
                'location' => 'header',
                'parent_id' => null,
                'title' => 'Home',
                'type' => 'route',
                'page_id' => null,
                'url' => '/',
                'badge_text' => null,
                'target' => '_self',
                'sort_order' => 10,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $allJerseysId = DB::table('menu_items')->insertGetId([
                'location' => 'header',
                'parent_id' => null,
                'title' => 'All Jerseys',
                'type' => 'route',
                'page_id' => null,
                'url' => '/shop',
                'badge_text' => null,
                'target' => '_self',
                'sort_order' => 20,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Level 2 under All Jerseys
            DB::table('menu_items')->insert([
                [
                    'location' => 'header',
                    'parent_id' => $allJerseysId,
                    'title' => 'Half Sleeve Kits',
                    'type' => 'custom',
                    'page_id' => null,
                    'url' => '/shop?sleeve=half-sleeve',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 10,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'header',
                    'parent_id' => $allJerseysId,
                    'title' => 'Full Sleeve Kits',
                    'type' => 'custom',
                    'page_id' => null,
                    'url' => '/shop?sleeve=full-sleeve',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 20,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'header',
                    'parent_id' => $allJerseysId,
                    'title' => 'Winter Hoodies',
                    'type' => 'custom',
                    'page_id' => null,
                    'url' => '/shop?sleeve=winter-hoodie',
                    'badge_text' => 'NEW',
                    'target' => '_self',
                    'sort_order' => 30,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'header',
                    'parent_id' => $allJerseysId,
                    'title' => 'Retro Editions',
                    'type' => 'custom',
                    'page_id' => null,
                    'url' => '/shop?sleeve=retro',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 40,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            $clubCollectionId = DB::table('menu_items')->insertGetId([
                'location' => 'header',
                'parent_id' => $allJerseysId,
                'title' => 'Club Collections',
                'type' => 'custom',
                'page_id' => null,
                'url' => '/shop',
                'badge_text' => null,
                'target' => '_self',
                'sort_order' => 50,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Level 3 under Club Collections
            DB::table('menu_items')->insert([
                [
                    'location' => 'header',
                    'parent_id' => $clubCollectionId,
                    'title' => 'Real Madrid',
                    'type' => 'custom',
                    'page_id' => null,
                    'url' => '/shop?club=real-madrid',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 10,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'header',
                    'parent_id' => $clubCollectionId,
                    'title' => 'FC Barcelona',
                    'type' => 'custom',
                    'page_id' => null,
                    'url' => '/shop?club=barcelona',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 20,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'header',
                    'parent_id' => $clubCollectionId,
                    'title' => 'Arsenal',
                    'type' => 'custom',
                    'page_id' => null,
                    'url' => '/shop?club=arsenal',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 30,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'header',
                    'parent_id' => $clubCollectionId,
                    'title' => 'Argentina',
                    'type' => 'custom',
                    'page_id' => null,
                    'url' => '/shop?club=argentina',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 40,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            DB::table('menu_items')->insert([
                [
                    'location' => 'header',
                    'parent_id' => null,
                    'title' => 'Winter Hoodies',
                    'type' => 'custom',
                    'page_id' => null,
                    'url' => '/shop?sleeve=winter-hoodie',
                    'badge_text' => 'NEW',
                    'target' => '_self',
                    'sort_order' => 30,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'header',
                    'parent_id' => null,
                    'title' => 'Full Sleeve',
                    'type' => 'custom',
                    'page_id' => null,
                    'url' => '/shop?sleeve=full-sleeve',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 40,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'header',
                    'parent_id' => null,
                    'title' => 'Retro Kits',
                    'type' => 'custom',
                    'page_id' => null,
                    'url' => '/shop?sleeve=retro',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 50,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'header',
                    'parent_id' => null,
                    'title' => 'Size Chart',
                    'type' => 'custom',
                    'page_id' => null,
                    'url' => '/#size-guide',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 60,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            $policiesId = DB::table('menu_items')->insertGetId([
                'location' => 'header',
                'parent_id' => null,
                'title' => 'Policies',
                'type' => $privacyId ? 'page' : 'custom',
                'page_id' => $privacyId,
                'url' => '/privacy-policy',
                'badge_text' => null,
                'target' => '_self',
                'sort_order' => 70,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('menu_items')->insert([
                [
                    'location' => 'header',
                    'parent_id' => $policiesId,
                    'title' => 'Privacy Policy',
                    'type' => $privacyId ? 'page' : 'custom',
                    'page_id' => $privacyId,
                    'url' => '/privacy-policy',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 10,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'header',
                    'parent_id' => $policiesId,
                    'title' => 'Terms & Conditions',
                    'type' => $termsId ? 'page' : 'custom',
                    'page_id' => $termsId,
                    'url' => '/terms-and-conditions',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 20,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'header',
                    'parent_id' => $policiesId,
                    'title' => 'Return & Exchange Policy',
                    'type' => $returnId ? 'page' : 'custom',
                    'page_id' => $returnId,
                    'url' => '/return-exchange-policy',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 30,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'header',
                    'parent_id' => $policiesId,
                    'title' => 'Track Your Order',
                    'type' => 'route',
                    'page_id' => null,
                    'url' => '/track-order',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 40,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            // Footer Customer Care Menu
            DB::table('menu_items')->insert([
                [
                    'location' => 'footer_care',
                    'parent_id' => null,
                    'title' => 'Track Your Order & Invoice',
                    'type' => 'route',
                    'page_id' => null,
                    'url' => '/track-order',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 10,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'footer_care',
                    'parent_id' => null,
                    'title' => '1-Page COD Checkout',
                    'type' => 'route',
                    'page_id' => null,
                    'url' => '/checkout',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 20,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'footer_care',
                    'parent_id' => null,
                    'title' => 'Privacy Policy',
                    'type' => $privacyId ? 'page' : 'custom',
                    'page_id' => $privacyId,
                    'url' => '/privacy-policy',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 30,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'footer_care',
                    'parent_id' => null,
                    'title' => 'Terms & Conditions',
                    'type' => $termsId ? 'page' : 'custom',
                    'page_id' => $termsId,
                    'url' => '/terms-and-conditions',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 40,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'footer_care',
                    'parent_id' => null,
                    'title' => 'Return & Exchange Policy',
                    'type' => $returnId ? 'page' : 'custom',
                    'page_id' => $returnId,
                    'url' => '/return-exchange-policy',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 50,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'footer_care',
                    'parent_id' => null,
                    'title' => 'Direct WhatsApp Support',
                    'type' => 'custom',
                    'page_id' => null,
                    'url' => 'https://api.whatsapp.com/send/?phone=8801612359652',
                    'badge_text' => null,
                    'target' => '_blank',
                    'sort_order' => 60,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'location' => 'footer_care',
                    'parent_id' => null,
                    'title' => 'RABEZ Admin Panel',
                    'type' => 'route',
                    'page_id' => null,
                    'url' => '/admin',
                    'badge_text' => null,
                    'target' => '_self',
                    'sort_order' => 70,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('pages');
    }
};
