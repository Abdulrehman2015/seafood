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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->text('summary')->nullable();
            $table->string('status', 20)->default('published'); // 'published', 'draft'
            $table->integer('sort_order')->default(0);
            
            // Multilingual Support
            $table->string('title_zh')->nullable();
            $table->longText('content_zh')->nullable();
            $table->string('title_bm')->nullable();
            $table->longText('content_bm')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
        });

        // Seed default policies with professional seafood trading & retail content
        $now = now();
        DB::table('policies')->insert([
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'summary' => 'Our commitment to protecting your personal data, customer accounts, and order records.',
                'content' => "<h2>1. Introduction</h2><p>Welcome to MST Import & Export Sdn Bhd. We are dedicated to maintaining the privacy and security of the personal data of our customers, wholesalers, and website visitors. This Privacy Policy details how we collect, handle, protect, and process your information when you access our store, catalogue, or request quotations.</p><h2>2. Information We Collect</h2><p>We may collect personal and commercial information including:</p><ul><li><strong>Contact details:</strong> Name, business name, company registration number, email address, telephone numbers, and delivery address.</li><li><strong>Account details:</strong> Login credentials, account verification status, and customer group (Retail, Wholesale, Trading, or Walk-in).</li><li><strong>Order & Transaction data:</strong> Products purchased, quotation requests, invoice details, and payment confirmation.</li></ul><h2>3. How We Use Your Data</h2><p>Your information is used strictly to fulfill orders, process wholesale/RFQ quotations, provide logistics and delivery updates, issue tax invoices, and comply with Malaysian statutory and food safety regulatory requirements.</p><h2>4. Data Security</h2><p>We implement industry-standard encryption, firewalls, and strict access controls to safeguard your data against unauthorized access, loss, alteration, or disclosure.</p><h2>5. Contact Us</h2><p>If you have questions regarding this Privacy Policy or your personal data, please reach out to us at <strong>mikatrading15@gmail.com</strong> or call <strong>013-2800168</strong>.</p>",
                'status' => 'published',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
                'summary' => 'Terms of service, quotation agreements, order fulfilment, and store usage policies.',
                'content' => "<h2>1. Agreement to Terms</h2><p>By browsing, registering, requesting quotations, or placing orders with MST Import & Export Sdn Bhd, you acknowledge and agree to be bound by these Terms and Conditions.</p><h2>2. Product Availability & Freshness</h2><p>Seafood, meat, and frozen items are subject to seasonal availability, catch cycles, and strict cold-chain quality controls. We strive to provide accurate stock representations; in the event of stock variation, our team will promptly contact you with equivalent premium alternatives.</p><h2>3. Pricing & Quotations</h2><p>All prices listed in MYR (with SGD/USD indicative conversions) are subject to confirmation at quotation issuance or checkout. Wholesale and trading rates apply strictly to approved commercial accounts.</p><h2>4. Deliveries & Cold-Chain Logistics</h2><p>We utilize specialized refrigerated transport to ensure the cold chain is preserved from our warehouse to your doorstep or facility. Customers or their authorized representatives must inspect goods upon delivery.</p><h2>5. Governing Law</h2><p>These terms are governed by and construed in accordance with the laws of Malaysia.</p>",
                'status' => 'published',
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Refund & Return Policy',
                'slug' => 'refund-policy',
                'summary' => 'Quality assurance guidelines, damaged goods protocol, and refund request procedures.',
                'content' => "<h2>1. Cold-Chain Quality Guarantee</h2><p>At MST Import & Export Sdn Bhd, product freshness and customer satisfaction are our highest priorities. Because frozen seafood and perishable foodstuffs require continuous cold-chain management, strict guidelines apply for return and refund requests.</p><h2>2. Reporting Quality Issues</h2><p>If you receive an item that is defective, damaged in transit, or does not match your order specifications:</p><ul><li>Notify our customer care team within <strong>24 hours of delivery</strong>.</li><li>Provide clear photographs or video evidence of the item, temperature tag (if applicable), and invoice number.</li><li>Maintain the item stored in appropriate frozen storage until our quality officer provides instructions.</li></ul><h2>3. Refund Processing</h2><p>Upon verification, approved refunds will be credited back via the original payment method, bank transfer, or store credit within 3 to 5 business days.</p>",
                'status' => 'published',
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Shipping & Delivery Policy',
                'slug' => 'shipping-policy',
                'summary' => 'Refrigerated logistics routes, fulfillment schedules, and regional delivery coverage.',
                'content' => "<h2>1. Delivery Coverage</h2><p>We provide temperature-controlled delivery throughout Johor, Klang Valley, and major commercial hubs across Peninsular Malaysia, as well as export coordination to Singapore and regional partners.</p><h2>2. Delivery Schedules</h2><p>Standard orders placed before 2:00 PM are processed for next-day dispatch. Wholesale logistics schedules are coordinated directly with our dispatch department based on agreed delivery slots.</p><h2>3. Self-Collection & Walk-In Store</h2><p>Customers can opt for self-pickup at our central facility at <strong>7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor</strong> during operating hours (Monday to Saturday, 8:00 AM – 6:00 PM).</p>",
                'status' => 'published',
                'sort_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
