<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSeo extends Model
{
    protected $table = 'page_seos';

    protected $fillable = [
        'page_name',
        'page_slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'canonical_url',
        'schema_markup',
        'is_system',
    ];

    /**
     * Get predefined system pages.
     */
    public static function defaultPages(): array
    {
        return [
            'home' => [
                'name'        => 'Home',
                'title'       => 'MST IMPORT AND EXPORT SDN BHD | Premium Fresh & Frozen Seafood Exporter',
                'description' => 'MST IMPORT AND EXPORT SDN BHD is a leading supplier and distributor of premium frozen and fresh seafood, catering to retail, wholesale, and bulk trading clients in Malaysia and Singapore.',
                'keywords'    => 'fresh seafood, frozen salmon, king prawns, lobsters, seafood export, b2b seafood',
            ],
            'about' => [
                'name'        => 'About Us',
                'title'       => 'About MST IMPORT AND EXPORT SDN BHD | Global Seafood Supply & Sustainability',
                'description' => 'Learn about MST IMPORT AND EXPORT SDN BHD, our commitment to sustainable ocean harvesting, international cold-chain quality assurance, and global seafood supply.',
                'keywords'    => 'about mst import and export sdn bhd, sustainable seafood, cold chain logistics, seafood wholesale',
            ],
            'shop' => [
                'name'        => 'Shop / Products',
                'title'       => 'Seafood Catalogue & Shop | MST IMPORT AND EXPORT SDN BHD',
                'description' => 'Explore our extensive catalogue of fresh and frozen seafood including Atlantic Salmon, King Prawns, Mud Crabs, Lobsters, and Whole Fish.',
                'keywords'    => 'buy seafood online, salmon fillets, fresh tiger prawns, frozen seafood wholesale',
            ],
            'contact' => [
                'name'        => 'Contact Us',
                'title'       => 'Contact Us & Global Enquiries | MST IMPORT AND EXPORT SDN BHD',
                'description' => 'Get in touch with the MST IMPORT AND EXPORT SDN BHD team for retail questions, commercial wholesale partnerships, cold-storage logistics, or customer support.',
                'keywords'    => 'contact seafood supplier, wholesale enquiry, seafood customer service',
            ],
            'quotations' => [
                'name'        => 'Request a Quotation (RFQ)',
                'title'       => 'Request a Quotation (RFQ) | MST IMPORT AND EXPORT SDN BHD Commercial Trading',
                'description' => 'Submit a customized Request for Quotation (RFQ) for bulk container shipments, custom packing, and wholesale commercial seafood pricing.',
                'keywords'    => 'seafood RFQ, bulk seafood quotation, commercial seafood order',
            ],
            'walkin' => [
                'name'        => 'Walk-In Catalogue',
                'title'       => 'Walk-In Customer Store & Token Pass | MST IMPORT AND EXPORT SDN BHD',
                'description' => 'Browse our in-store digital catalogue, order fresh seafood instantly, and receive a collection token at our physical outlet.',
                'keywords'    => 'walkin seafood, QR seafood ordering, store collection token',
            ],
            'cart' => [
                'name'        => 'Shopping Cart',
                'title'       => 'Your Cart | MST IMPORT AND EXPORT SDN BHD',
                'description' => 'Review your selected seafood items, quantities, tiered discounts, and proceed to checkout.',
                'keywords'    => 'seafood cart, checkout seafood',
            ],
            'checkout' => [
                'name'        => 'Checkout',
                'title'       => 'Secure Checkout | MST IMPORT AND EXPORT SDN BHD',
                'description' => 'Complete your seafood order securely with credit/debit card, bank transfer, or cash on delivery.',
                'keywords'    => 'secure checkout, buy fish online',
            ],
            'privacy_policy' => [
                'name'        => 'Privacy Policy',
                'title'       => 'Privacy Policy | Data Protection & Cookies | MST IMPORT AND EXPORT SDN BHD',
                'description' => 'Read how MST IMPORT AND EXPORT SDN BHD protects customer information, handles cookies, and respects international data privacy standards.',
                'keywords'    => 'privacy policy, customer data protection, cookies policy',
            ],
            'terms_conditions' => [
                'name'        => 'Terms & Conditions',
                'title'       => 'Terms & Conditions | MST IMPORT AND EXPORT SDN BHD Commercial & Retail Sales',
                'description' => 'Review the official terms of service, delivery policies, return guidelines, and wholesale conditions of MST IMPORT AND EXPORT SDN BHD.',
                'keywords'    => 'terms of service, seafood return policy, delivery terms',
            ],
            'blogs' => [
                'name'        => 'Blogs / Thought Leadership',
                'title'       => 'Thought Leadership | Insights & Market Analysis | MST IMPORT AND EXPORT SDN BHD',
                'description' => 'Articles and industry insights on global aquaculture trends, seasonal seafood catch forecasts, and cold-chain innovations.',
                'keywords'    => 'seafood industry blog, aquaculture insights, fish market news',
            ],
            'accessibility_policy' => [
                'name'        => 'Accessibility Policy',
                'title'       => 'Accessibility & Inclusion Policy | MST IMPORT AND EXPORT SDN BHD',
                'description' => 'Our commitment to ensuring digital accessibility for all users across our online ordering platforms.',
                'keywords'    => 'accessibility policy, web standards',
            ],
            'ai_use_policy' => [
                'name'        => 'AI Use Policy',
                'title'       => 'Responsible AI Use Policy | MST IMPORT AND EXPORT SDN BHD',
                'description' => 'Guidelines on our responsible deployment of automated inventory forecasting and customer communication AI systems.',
                'keywords'    => 'ai ethics, responsible technology',
            ],
            'anti_fraud_policy' => [
                'name'        => 'Anti-Fraud & Ethics Policy',
                'title'       => 'Anti-Fraud, Anti-Corruption & Anti-Bribery | MST IMPORT AND EXPORT SDN BHD',
                'description' => 'Corporate integrity standards, anti-fraud compliance, and fair trade practices across all supply chain partners.',
                'keywords'    => 'anti fraud policy, corporate ethics, compliance',
            ],
        ];
    }

    /**
     * Seed or get all pages ensuring default frontend pages exist.
     */
    public static function ensureDefaults(): void
    {
        $defaults = self::defaultPages();
        foreach ($defaults as $slug => $data) {
            self::firstOrCreate(
                ['page_slug' => $slug],
                [
                    'page_name'        => $data['name'],
                    'meta_title'       => $data['title'],
                    'meta_description' => $data['description'],
                    'meta_keywords'    => $data['keywords'],
                    'is_system'        => true,
                ]
            );
        }
    }

    /**
     * Retrieve SEO for specific page slug.
     */
    public static function forPage(string $slug): ?self
    {
        return self::where('page_slug', $slug)->first();
    }
}
