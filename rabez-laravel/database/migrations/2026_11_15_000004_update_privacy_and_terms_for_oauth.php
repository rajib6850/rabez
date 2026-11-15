<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pages')) {
            return;
        }

        $now = now();

        DB::table('pages')
            ->where('slug', 'privacy-policy')
            ->update([
                'excerpt' => 'How RABEZ (rabez.shop) collects, uses, and protects your order, account, and Google/Facebook sign-in data.',
                'content' => <<<'HTML'
<h2>1. Information We Collect</h2>
<p>When you place an order, create a customer account, or contact <strong>RABEZ (rabez.shop)</strong>, we collect only the details needed to prepare your jersey, deliver your parcel safely across Bangladesh, and provide access to your order dashboard:</p>
<ul>
  <li><strong>Customer &amp; Account Details:</strong> Full name, email address, and active 11-digit mobile phone number.</li>
  <li><strong>Delivery Address:</strong> District, Upazila/Thana, and house/road address saved in your account or entered at checkout.</li>
  <li><strong>Google &amp; Facebook Sign-In (OAuth 2.0) Data:</strong> If you choose <em>Continue with Google</em> or <em>Continue with Facebook</em>, we receive only your basic profile information (full name, email address, provider user ID, and profile avatar URL) with your explicit consent. We do not collect your password or private social posts, contacts, or friend lists.</li>
  <li><strong>Order &amp; Custom Print Notes:</strong> Jersey size, sleeve version, and optional custom player name/number specifications.</li>
</ul>

<h2>2. How We Use Your Information &amp; Google API Compliance</h2>
<p>We use your information strictly for the following purposes:</p>
<ul>
  <li><strong>Authentication &amp; Customer Dashboard:</strong> Creating and securing your member account at <code>rabez.shop/account</code> so you can view your order history, invoices, and saved delivery details.</li>
  <li><strong>Order Fulfillment &amp; Courier Delivery:</strong> Your name, mobile number, and shipping address are shared strictly with our verified courier partners (such as Steadfast or Pathao) to complete Cash on Delivery (COD) shipments.</li>
  <li><strong>Google API Services User Data Policy:</strong> RABEZ's use and transfer of information received from Google APIs adheres strictly to the Google API Services User Data Policy, including the Limited Use requirements. We never sell, rent, or transfer Google or Facebook login data to third-party data brokers.</li>
</ul>

<h2>3. Cookies, Session Storage &amp; Meta Pixel Analytics</h2>
<p>We use essential session cookies to keep you signed in and store your shopping cart locally in your browser. We also use Meta Pixel and Conversions API (CAPI) strictly to measure storefront purchases (such as PageView, ViewContent, AddToCart, InitiateCheckout, and Purchase) and improve ad relevance.</p>

<h2>4. Account &amp; Data Deletion Requests (Google &amp; Facebook Compliance)</h2>
<p>You may request updating, exporting, or permanent deletion of your RABEZ customer account, saved delivery address, and linked Google/Facebook OAuth identifiers at any time:</p>
<ul>
  <li><strong>Email Request:</strong> Write to <strong>rajib6850@gmail.com</strong> from your registered email address with the subject line <em>&ldquo;Account &amp; Data Deletion Request &ndash; rabez.shop&rdquo;</em>.</li>
  <li><strong>WhatsApp Support:</strong> Message our official support desk at <strong>+880 1612-359652</strong> with your registered phone number or email.</li>
</ul>
<p>Verified deletion requests are processed within <strong>48 hours</strong>, removing your personal profile, social OAuth IDs, and saved delivery details (except transactional records required by local accounting laws).</p>

<h2>5. Contact Us</h2>
<p>If you have any questions about this Privacy Policy or how RABEZ protects your data, email us at <strong>rajib6850@gmail.com</strong> or message us on WhatsApp at <strong>+880 1612-359652</strong>.</p>
HTML,
                'meta_title' => 'Privacy Policy | RABEZ (rabez.shop)',
                'meta_description' => 'Privacy Policy for RABEZ (rabez.shop) covering customer accounts, Google & Facebook OAuth login, courier delivery, and data deletion.',
                'updated_at' => $now,
            ]);

        DB::table('pages')
            ->where('slug', 'terms-and-conditions')
            ->update([
                'excerpt' => 'Customer account rules, Google/Facebook sign-in, order confirmation, Cash on Delivery, and custom print terms for rabez.shop.',
                'content' => <<<'HTML'
<h2>1. Customer Accounts &amp; Social Sign-In (Google / Facebook)</h2>
<p>Customers may check out as guests or sign in at <strong>rabez.shop/login</strong> using email/password, <strong>Google</strong>, or <strong>Facebook</strong> to manage orders from their dashboard (<strong>rabez.shop/account</strong>). By registering or signing in via Google or Facebook, you agree to provide accurate contact and delivery information and to keep your account credentials secure. Orders placed as a guest using the same 11-digit mobile number may automatically appear in your dashboard once verified in your profile.</p>

<h2>2. Order Confirmation</h2>
<p>All orders placed on <strong>rabez.shop</strong> or via WhatsApp are confirmed based on ready-stock availability. For Cash on Delivery (COD) orders, please keep your phone reachable so our team or courier can confirm dispatch.</p>

<h2>3. Pricing &amp; Delivery Charges</h2>
<p>Product prices are listed in Bangladeshi Taka (BDT &#2547;). Nationwide delivery fees are clearly displayed at checkout before order submission.</p>

<h2>4. Custom Name &amp; Number Print Policy</h2>
<p>Jerseys ordered with custom player names or numbers are heat-pressed exclusively to order. Once printing starts, custom-printed jerseys cannot be cancelled, refunded, or exchanged unless a manufacturing or print defect occurs from our side.</p>

<h2>5. Open-Box Inspection</h2>
<p>Customers may inspect the parcel in front of the delivery rider upon arrival. Please contact us immediately via WhatsApp at <strong>+880 1612-359652</strong> or email <strong>rajib6850@gmail.com</strong> if any item arrives damaged or incorrect.</p>

<h2>6. Account Termination &amp; Updates to Terms</h2>
<p>RABEZ reserves the right to suspend fraudulent or abusive accounts (such as repeated fake COD orders). Continued use of <strong>rabez.shop</strong> constitutes acceptance of these Terms &amp; Conditions.</p>
HTML,
                'meta_title' => 'Terms & Conditions | RABEZ (rabez.shop)',
                'meta_description' => 'Terms & Conditions for RABEZ (rabez.shop) including customer accounts, Google/Facebook social login, COD delivery, and custom jersey printing.',
                'updated_at' => $now,
            ]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('pages')) {
            return;
        }

        $now = now();

        DB::table('pages')
            ->where('slug', 'privacy-policy')
            ->update([
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
                'updated_at' => $now,
            ]);

        DB::table('pages')
            ->where('slug', 'terms-and-conditions')
            ->update([
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
                'updated_at' => $now,
            ]);
    }
};
