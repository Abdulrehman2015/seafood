<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            [
                'name' => 'Chef Marcus Tan',
                'role_or_company' => 'Executive Head Chef, Marina Seafood Bistro (KL)',
                'rating' => 5,
                'comment' => 'MST IMPORT AND EXPORT SDN BHD has been our primary seafood purveyor for over 3 years. The IQF King Salmon fillets and Tiger Prawns arrive with flawless texture and zero glazing loss. Truly five-star grade consistency for our banquet service.',
                'avatar' => null,
                'is_featured' => true,
                'status' => 'approved',
                'sort_order' => 1,
            ],
            [
                'name' => 'Datin Sarah Al-Haddad',
                'role_or_company' => 'Verified Retail Gourmet Buyer (Ampang)',
                'rating' => 5,
                'comment' => 'The cold-truck delivery arrived in under 24 hours strictly frozen at -18°C. The Norwegian Cod steaks were exceptionally sweet and tender. Having restaurant-grade seafood delivered right to our door is unbeatable.',
                'avatar' => null,
                'is_featured' => true,
                'status' => 'approved',
                'sort_order' => 2,
            ],
            [
                'name' => 'Kenji Takahashi',
                'role_or_company' => 'Owner & Head Chef, Omakase Bar Pavilion',
                'rating' => 5,
                'comment' => 'Their sashimi-grade Scallops and Yellowfin Tuna meet our stringent standards for raw preparation. Reliable supply chain, prompt RFQ quotes, and pristine cold-chain hygiene throughout.',
                'avatar' => null,
                'is_featured' => true,
                'status' => 'approved',
                'sort_order' => 3,
            ],
            [
                'name' => 'Haji Rahim bin Yusof',
                'role_or_company' => 'Procurement Director, Selera Samudera Catering',
                'rating' => 5,
                'comment' => 'We manage corporate catering for up to 2,000 pax weekly. MST IMPORT AND EXPORT SDN BHD’s wholesale tier and self-pickup counter at SILC Iskandar Puteri save us crucial logistics time. Certified Halal and always dependable.',
                'avatar' => null,
                'is_featured' => true,
                'status' => 'approved',
                'sort_order' => 4,
            ],
            [
                'name' => 'Emily Wong',
                'role_or_company' => 'Home Cook & Culinary Blogger',
                'rating' => 5,
                'comment' => 'The Giant Black Tiger Prawns and Squid Tubes made our family reunion dinner unforgettable! Perfectly cleaned, flash-frozen at peak harvest, and completely free of chemical preservatives.',
                'avatar' => null,
                'is_featured' => true,
                'status' => 'approved',
                'sort_order' => 5,
            ],
            [
                'name' => 'Vikram Nair',
                'role_or_company' => 'F&B Manager, Coastal Grill & Bar (Bangsar)',
                'rating' => 5,
                'comment' => 'Transparent wholesale pricing, live inventory visibility, and professional cold logistics. MST IMPORT AND EXPORT SDN BHD eliminates the headaches typically associated with wet market purchasing.',
                'avatar' => null,
                'is_featured' => true,
                'status' => 'approved',
                'sort_order' => 6,
            ],
        ];

        foreach ($reviews as $rev) {
            Review::updateOrCreate(
                ['name' => $rev['name']],
                $rev
            );
        }
    }
}
