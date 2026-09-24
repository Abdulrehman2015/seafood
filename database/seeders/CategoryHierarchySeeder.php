<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryHierarchySeeder extends Seeder
{
    public function run(): void
    {
        $structure = [
            [
                'name' => 'Seafood',
                'name_zh' => '精选海鲜',
                'name_bm' => 'Makanan Laut',
                'slug' => 'seafood',
                'icon' => '🦐',
                'sort_order' => 1,
                'children' => [
                    ['name' => 'Fish', 'name_zh' => '鱼类', 'name_bm' => 'Ikan', 'slug' => 'fish', 'icon' => '🐟'],
                    ['name' => 'Fish Fillet', 'name_zh' => '鱼柳 / 鱼片', 'name_bm' => 'Flet Ikan', 'slug' => 'fish-fillet', 'icon' => '🐟'],
                    ['name' => 'Prawns / Shrimps', 'name_zh' => '虾类 / 明虾', 'name_bm' => 'Udang', 'slug' => 'prawns-shrimps', 'icon' => '🦐'],
                    ['name' => 'Crab', 'name_zh' => '蟹类 / 螃蟹', 'name_bm' => 'Ketam', 'slug' => 'crab', 'icon' => '🦀'],
                    ['name' => 'Squid / Cuttlefish', 'name_zh' => '鱿鱼 / 乌贼', 'name_bm' => 'Sotong', 'slug' => 'squid', 'icon' => '🦑'],
                    ['name' => 'Shellfish', 'name_zh' => '贝类 / 扇贝', 'name_bm' => 'Kerang-kerangan', 'slug' => 'shellfish', 'icon' => '🦪'],
                    ['name' => 'Other Seafood', 'name_zh' => '其他海产', 'name_bm' => 'Makanan Laut Lain', 'slug' => 'other-frozen-seafood', 'icon' => '🌊'],
                ]
            ],
            [
                'name' => 'Meat',
                'name_zh' => '精选肉类',
                'name_bm' => 'Daging',
                'slug' => 'meat',
                'icon' => '🥩',
                'sort_order' => 2,
                'children' => [
                    ['name' => 'Chicken', 'name_zh' => '鸡肉', 'name_bm' => 'Ayam', 'slug' => 'meat-chicken', 'icon' => '🍗'],
                    ['name' => 'Beef', 'name_zh' => '牛肉', 'name_bm' => 'Lembu', 'slug' => 'meat-beef', 'icon' => '🥩'],
                    ['name' => 'Pork', 'name_zh' => '猪肉', 'name_bm' => 'Babi', 'slug' => 'meat-pork', 'icon' => '🥓'],
                    ['name' => 'Other Meat', 'name_zh' => '其他肉类', 'name_bm' => 'Daging Lain', 'slug' => 'meat-other', 'icon' => '🍖'],
                ]
            ],
            [
                'name' => 'Food Ingredients',
                'name_zh' => '食品配料 / 原料',
                'name_bm' => 'Bahan Makanan',
                'slug' => 'food-ingredients',
                'icon' => '🧂',
                'sort_order' => 3,
                'children' => [
                    ['name' => 'Fish Paste / Surimi', 'name_zh' => '鱼滑 / 鱼浆', 'name_bm' => 'Pes Ikan / Surimi', 'slug' => 'fish-paste-surimi', 'icon' => '🍥'],
                    ['name' => 'Seafood Ingredients', 'name_zh' => '海鲜原料配料', 'name_bm' => 'Bahan Makanan Laut', 'slug' => 'seafood-ingredients', 'icon' => '🦐'],
                    ['name' => 'Meat Ingredients', 'name_zh' => '肉类配料', 'name_bm' => 'Bahan Daging', 'slug' => 'meat-ingredients', 'icon' => '🥩'],
                    ['name' => 'Cooking Ingredients', 'name_zh' => '烹饪配料', 'name_bm' => 'Bahan Masakan', 'slug' => 'cooking-ingredients', 'icon' => '🥣'],
                    ['name' => 'Sauces & Seasonings', 'name_zh' => '酱料与调味品', 'name_bm' => 'Sos & Perasa', 'slug' => 'sauces-seasonings', 'icon' => '🍶'],
                ]
            ],
            [
                'name' => 'Cuisine Ingredients',
                'name_zh' => '特色菜系食材',
                'name_bm' => 'Bahan Masakan Masakan',
                'slug' => 'cuisine-ingredients',
                'icon' => '🍳',
                'sort_order' => 4,
                'children' => [
                    ['name' => 'Japanese', 'name_zh' => '日料食材', 'name_bm' => 'Jepun', 'slug' => 'japanese-cuisine', 'icon' => '🍱'],
                    ['name' => 'Korean', 'name_zh' => '韩式食材', 'name_bm' => 'Korea', 'slug' => 'korean-cuisine', 'icon' => '🥘'],
                    ['name' => 'Chinese', 'name_zh' => '中式食材', 'name_bm' => 'Cina', 'slug' => 'chinese-cuisine', 'icon' => '🥢'],
                    ['name' => 'Western', 'name_zh' => '西式食材', 'name_bm' => 'Barat', 'slug' => 'western-cuisine', 'icon' => '🍽️'],
                    ['name' => 'Asian', 'name_zh' => '亚洲综合食材', 'name_bm' => 'Asia', 'slug' => 'asian-cuisine', 'icon' => '🍜'],
                ]
            ],
            [
                'name' => 'Frozen Foods',
                'name_zh' => '冷冻调理食品',
                'name_bm' => 'Makanan Beku',
                'slug' => 'frozen-food',
                'icon' => '🥟',
                'sort_order' => 5,
                'children' => [
                    ['name' => 'Steamboat / Hotpot', 'name_zh' => '火锅食材', 'name_bm' => 'Steamboat / Hotpot', 'slug' => 'steamboat', 'icon' => '🍲'],
                    ['name' => 'Ready-to-Cook', 'name_zh' => '免洗即烹食材', 'name_bm' => 'Sedia Dimasak', 'slug' => 'ready-to-cook', 'icon' => '🥘'],
                    ['name' => 'Processed Foods', 'name_zh' => '冷冻调理熟食', 'name_bm' => 'Makanan Berproses', 'slug' => 'frozen-product-food', 'icon' => '🍤'],
                    ['name' => 'Snacks', 'name_zh' => '休闲小食', 'name_bm' => 'Makanan Ringan', 'slug' => 'snack-food', 'icon' => '🍢'],
                    ['name' => 'Desserts', 'name_zh' => '甜品与甜点', 'name_bm' => 'Pencuci Mulut', 'slug' => 'dessert', 'icon' => '🍡'],
                ]
            ],
            [
                'name' => 'Specialty / Other',
                'name_zh' => '特产及其他',
                'name_bm' => 'Keistimewaan & Lain-lain',
                'slug' => 'specialty-other',
                'icon' => '⭐',
                'sort_order' => 6,
                'children' => [
                    ['name' => 'Specialty Products', 'name_zh' => '特色产品', 'name_bm' => 'Produk Istimewa', 'slug' => 'specialty-products', 'icon' => '✨'],
                    ['name' => 'Selected Imported Products', 'name_zh' => '精选进口产品', 'name_bm' => 'Produk Import Terpilih', 'slug' => 'selected-imported-products', 'icon' => '🌐'],
                ]
            ],
        ];

        foreach ($structure as $p) {
            $parent = Category::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'name' => $p['name'],
                    'name_zh' => $p['name_zh'],
                    'name_bm' => $p['name_bm'],
                    'parent_id' => null,
                    'icon' => $p['icon'],
                    'is_active' => true,
                    'sort_order' => $p['sort_order'],
                ]
            );

            $cSort = 1;
            foreach ($p['children'] as $c) {
                Category::updateOrCreate(
                    ['slug' => $c['slug']],
                    [
                        'name' => $c['name'],
                        'name_zh' => $c['name_zh'],
                        'name_bm' => $c['name_bm'],
                        'parent_id' => $parent->id,
                        'icon' => $c['icon'],
                        'is_active' => true,
                        'sort_order' => $cSort++,
                    ]
                );
            }
        }

        // Also map existing product categories if needed
        // Map Lamb and Duck to other meat if needed, or keep them mapped under Meat
        $meatParent = Category::where('slug', 'meat')->first();
        if ($meatParent) {
            Category::whereIn('slug', ['meat-lamb', 'meat-duck'])->update([
                'parent_id' => $meatParent->id,
            ]);
        }

        // Map Dimsum and Ready-to-Eat to Frozen Food
        $frozenParent = Category::where('slug', 'frozen-food')->first();
        if ($frozenParent) {
            Category::whereIn('slug', ['dimsum', 'ready-to-eat'])->update([
                'parent_id' => $frozenParent->id,
            ]);
        }

        // Map Seafood Products to Seafood
        $seafoodParent = Category::where('slug', 'seafood')->first();
        if ($seafoodParent) {
            Category::where('slug', 'seafood-products')->update([
                'parent_id' => $seafoodParent->id,
            ]);
        }
    }
}
