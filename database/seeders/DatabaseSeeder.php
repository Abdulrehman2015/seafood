<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin User ────────────────────────────────────────────────────────
        User::create([
            'name'            => 'Admin',
            'email'           => 'admin@mst.my',
            'password'        => Hash::make('password'),
            'customer_group'  => 'admin',
            'approval_status' => 'approved',
            'phone'           => '+60123456789',
            'address'         => '123 Seafood Street',
            'city'            => 'Kuala Lumpur',
            'state'           => 'Wilayah Persekutuan',
            'postcode'        => '50000',
        ]);

        // ─── Test Customers ────────────────────────────────────────────────────
        User::create([
            'name'            => 'Retail Customer',
            'email'           => 'retail@test.com',
            'password'        => Hash::make('password'),
            'customer_group'  => 'retail',
            'approval_status' => 'approved',
            'phone'           => '+60111111111',
            'address'         => '1 Jalan Test',
            'city'            => 'Kuala Lumpur',
            'state'           => 'Wilayah Persekutuan',
            'postcode'        => '50000',
        ]);

        User::create([
            'name'            => 'Wholesale Trading Sdn Bhd',
            'email'           => 'wholesale@test.com',
            'password'        => Hash::make('password'),
            'customer_group'  => 'wholesale',
            'approval_status' => 'approved',
            'phone'           => '+60122222222',
            'company_name'    => 'Wholesale Trading Sdn Bhd',
            'company_reg_no'  => '202300012345',
            'business_type'   => 'Restaurant & Catering',
            'address'         => '2 Jalan Wholesale',
            'city'            => 'Petaling Jaya',
            'state'           => 'Selangor',
            'postcode'        => '47810',
        ]);

        User::create([
            'name'            => 'Trading Corp Sdn Bhd',
            'email'           => 'trading@test.com',
            'password'        => Hash::make('password'),
            'customer_group'  => 'trading',
            'approval_status' => 'approved',
            'phone'           => '+60133333333',
            'company_name'    => 'Trading Corp Sdn Bhd',
            'company_reg_no'  => '202300054321',
            'business_type'   => 'Seafood Importer',
            'address'         => '3 Jalan Trading',
            'city'            => 'Shah Alam',
            'state'           => 'Selangor',
            'postcode'        => '40150',
        ]);

        // ─── Categories ────────────────────────────────────────────────────────
        $categories = [
            ['name' => 'Fish',        'slug' => 'fish',        'sort_order' => 1],
            ['name' => 'Shellfish',   'slug' => 'shellfish',   'sort_order' => 2],
            ['name' => 'Crustaceans', 'slug' => 'crustaceans', 'sort_order' => 3],
            ['name' => 'Cephalopods', 'slug' => 'cephalopods', 'sort_order' => 4],
            ['name' => 'Value Added', 'slug' => 'value-added', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            Category::create(array_merge($cat, ['is_active' => true]));
        }

        // ─── Products ──────────────────────────────────────────────────────────
        $fishCat  = Category::where('slug', 'fish')->first();
        $shellCat = Category::where('slug', 'shellfish')->first();
        $crustCat = Category::where('slug', 'crustaceans')->first();

        $products = [
            [
                'name'                => 'Atlantic Salmon Fillet (500g)',
                'slug'                => 'atlantic-salmon-fillet-500g',
                'short_description'   => 'Premium Norwegian Atlantic salmon fillet, skin-on, vacuum packed.',
                'description'         => 'Sustainably sourced Atlantic salmon from the cold waters of Norway. Rich in Omega-3 fatty acids. Ideal for grilling, baking, or pan-frying. Each pack contains one fillet of approximately 500g.',
                'sku'                 => 'FISH-SAL-500',
                'category_id'         => $fishCat->id,
                'retail_price'        => 28.90,
                'walkin_price'        => 25.90,
                'wholesale_price'     => 22.00,
                'trading_price'       => 19.50,
                'weight'              => '500g',
                'unit'                => 'pack',
                'origin'              => 'Norway',
                'storage_temp'        => '-18°C',
                'brand'               => 'MST IMPORT AND EXPORT SDN BHD',
                'stock_quantity'      => 250,
                'moq'                 => 1,
                'moq_wholesale'       => 10,
                'moq_trading'         => 50,
                'is_active'           => true,
                'is_walkin_available' => true,
                'is_featured'         => true,
                'sort_order'          => 1,
            ],
            [
                'name'                => 'Tiger Prawns (1kg)',
                'slug'                => 'tiger-prawns-1kg',
                'short_description'   => 'Wild-caught jumbo tiger prawns, shell-on, head-on.',
                'description'         => 'Succulent wild-caught tiger prawns from the South China Sea. Sweet, firm flesh ideal for BBQ, steaming, or curry. 1kg pack contains approximately 15-20 pieces.',
                'sku'                 => 'CRUST-TPRAWN-1KG',
                'category_id'         => $crustCat->id,
                'retail_price'        => 45.90,
                'walkin_price'        => 42.00,
                'wholesale_price'     => 36.00,
                'trading_price'       => null,
                'is_rfq_only'         => true,
                'weight'              => '1kg',
                'unit'                => 'kg',
                'origin'              => 'Malaysia',
                'storage_temp'        => '-18°C',
                'brand'               => 'MST IMPORT AND EXPORT SDN BHD',
                'stock_quantity'      => 180,
                'moq'                 => 1,
                'moq_wholesale'       => 5,
                'moq_trading'         => 20,
                'is_active'           => true,
                'is_walkin_available' => true,
                'is_featured'         => true,
                'sort_order'          => 2,
            ],
            [
                'name'                => 'Flower Crab (500g)',
                'slug'                => 'flower-crab-500g',
                'short_description'   => 'Fresh flower crabs, individually quick frozen at peak freshness.',
                'description'         => 'Premium flower crabs harvested fresh and individually quick frozen to lock in their natural sweetness. Perfect for steaming with ginger or chilli crab.',
                'sku'                 => 'CRUST-FCRAB-500',
                'category_id'         => $crustCat->id,
                'retail_price'        => 18.90,
                'walkin_price'        => 16.90,
                'wholesale_price'     => 13.50,
                'trading_price'       => 11.00,
                'weight'              => '500g',
                'unit'                => 'pack',
                'origin'              => 'Malaysia',
                'storage_temp'        => '-18°C',
                'brand'               => 'MST IMPORT AND EXPORT SDN BHD',
                'stock_quantity'      => 320,
                'moq'                 => 1,
                'moq_wholesale'       => 20,
                'moq_trading'         => 100,
                'is_active'           => true,
                'is_walkin_available' => true,
                'is_featured'         => false,
                'sort_order'          => 3,
            ],
            [
                'name'                => 'Squid Rings (500g)',
                'slug'                => 'squid-rings-500g',
                'short_description'   => 'Pre-cut squid rings, ready for frying or stir-frying.',
                'description'         => 'Convenient pre-cut squid rings made from fresh squid. Simply thaw and cook. Great for calamari, stir-fry, or hotpot.',
                'sku'                 => 'CEPH-SQUID-500',
                'category_id'         => Category::where('slug', 'cephalopods')->first()->id,
                'retail_price'        => 12.90,
                'walkin_price'        => 11.50,
                'wholesale_price'     => 9.00,
                'trading_price'       => 7.50,
                'weight'              => '500g',
                'unit'                => 'pack',
                'origin'              => 'Thailand',
                'storage_temp'        => '-18°C',
                'brand'               => 'MST IMPORT AND EXPORT SDN BHD',
                'stock_quantity'      => 400,
                'moq'                 => 1,
                'moq_wholesale'       => 20,
                'moq_trading'         => 100,
                'is_active'           => true,
                'is_walkin_available' => true,
                'is_featured'         => true,
                'sort_order'          => 4,
            ],
            [
                'name'                => 'Cockles / Kerang (500g)',
                'slug'                => 'cockles-kerang-500g',
                'short_description'   => 'Fresh Malaysian cockles, cleaned and individually quick frozen.',
                'description'         => 'Locally sourced Malaysian cockles (kerang) that are cleaned and IQF frozen. Ready for noodle dishes, satay kerang, or simply blanched.',
                'sku'                 => 'SHELL-COCK-500',
                'category_id'         => $shellCat->id,
                'retail_price'        => 8.90,
                'walkin_price'        => 7.90,
                'wholesale_price'     => 6.00,
                'trading_price'       => 4.80,
                'weight'              => '500g',
                'unit'                => 'pack',
                'origin'              => 'Malaysia',
                'storage_temp'        => '-18°C',
                'brand'               => 'MST IMPORT AND EXPORT SDN BHD',
                'stock_quantity'      => 500,
                'moq'                 => 1,
                'moq_wholesale'       => 50,
                'moq_trading'         => 200,
                'is_active'           => true,
                'is_walkin_available' => true,
                'is_featured'         => false,
                'sort_order'          => 5,
            ],
        ];

        foreach ($products as $prod) {
            Product::create($prod);
        }
    }
}
