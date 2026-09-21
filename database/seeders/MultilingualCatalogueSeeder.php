<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class MultilingualCatalogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ─── Categories Translations ───────────────────────────────────────
        $categoryTranslations = [
            'fish'                 => ['zh' => '野生及养殖鱼类', 'bm' => 'Ikan Segar & Beku'],
            'prawns-shrimps'       => ['zh' => '深海大虾 / 鲜虾', 'bm' => 'Udang Laut & Harimau'],
            'squid'                => ['zh' => '鱿鱼 / 乌贼 / 鱿鱼圈', 'bm' => 'Sotong Segar & Cincin Sotong'],
            'crab'                 => ['zh' => '活肉蟹 / 花蟹 / 软壳蟹', 'bm' => 'Ketam Nipah & Ketam Bunga'],
            'shellfish'            => ['zh' => '贝类 / 扇贝 / 贻贝', 'bm' => 'Kerang-kerangan & Skalop'],
            'seafood-products'     => ['zh' => '特色海产加工调理品', 'bm' => 'Produk Makanan Laut Berproses'],
            'fish-fillet'          => ['zh' => '精选无刺鱼柳鱼片', 'bm' => 'Flet Ikan Tanpa Tulang'],
            'other-frozen-seafood' => ['zh' => '其他进口急冻海产', 'bm' => 'Makanan Laut Beku Import Lain'],
            'steamboat'            => ['zh' => '火锅严选海鲜食材', 'bm' => 'Pilihan Makanan Laut Steamboat'],
            'meat-chicken'         => ['zh' => '冷冻鸡肉调理品', 'bm' => 'Daging Ayam Beku'],
            'meat-lamb'            => ['zh' => '澳洲进口法式羊排', 'bm' => 'Daging Kambing Import Australia'],
            'meat-beef'            => ['zh' => '进口高级雪花牛肉', 'bm' => 'Daging Lembu Premium'],
            'meat-duck'            => ['zh' => '特级烟熏鸭胸肉', 'bm' => 'Daging Itik Asap Gourmet'],
            'frozen-product-food'  => ['zh' => '冷冻美味调理熟食', 'bm' => 'Produk Makanan Beku Terpilih'],
            'dimsum'               => ['zh' => '传统手工粤式点心', 'bm' => 'Dim Sum Tradisional Halal'],
            'ready-to-eat'         => ['zh' => '解冻加热即食佳肴', 'bm' => 'Sedia Untuk Dimakan'],
            'snack-food'           => ['zh' => '日式开胃小食及海藻', 'bm' => 'Makanan Ringan & Rumpai Laut'],
            'dessert'              => ['zh' => '日式手作大福冰淇淋麻薯', 'bm' => 'Pencuci Mulut Mochi Aiskrim'],
        ];

        foreach ($categoryTranslations as $slug => $trans) {
            Category::where('slug', $slug)->update([
                'name_zh' => $trans['zh'],
                'name_bm' => $trans['bm'],
            ]);
        }

        // ─── Products Translations ─────────────────────────────────────────
        $productTranslations = [
            1 => [
                'name_zh' => '挪威大西洋三文鱼柳 (500g 独立真空包)',
                'name_bm' => 'Flet Salmon Atlantik Norway (500g)',
                'short_zh' => '源自挪威纯净深海冷水，带皮三文鱼柳，富含高含量 Omega-3 优质脂肪酸。',
                'short_bm' => 'Flet salmon Atlantik Norway premium, dengan kulit, dibungkus vakum.',
            ],
            2 => [
                'name_zh' => '特级特大黑虎虾 / 草虾 (1kg 急冻装)',
                'name_bm' => 'Udang Harimau Hitam Jumbo (1kg)',
                'short_zh' => '特大规格黑虎虾，单冻保鲜，肉质紧实弹牙，鲜甜爽脆。',
                'short_bm' => 'Udang harimau hitam saiz jumbo, beku IQF secara individu.',
            ],
            3 => [
                'name_zh' => '深海鲜捕花蟹 (500g 冷冻保鲜)',
                'name_bm' => 'Ketam Bunga Laut Dalam (500g)',
                'short_zh' => '深海捕捞新鲜花蟹，蟹肉洁白细腻清甜，适合清蒸与火锅。',
                'short_bm' => 'Ketam bunga segar tangkapan laut, isi manis dan lembut.',
            ],
            4 => [
                'name_zh' => '特选深海鱿鱼圈 (500g 独立速冻)',
                'name_bm' => 'Cincin Sotong Bersih (500g)',
                'short_zh' => '严选深海厚肉鱿鱼清洗切圈，无碎冰包冰，口感Q弹不缩水。',
                'short_bm' => 'Cincin sotong laut dalam telah dibersihkan dan dipotong sedia masak.',
            ],
            5 => [
                'name_zh' => '鲜甜血蚶 / 泥蚶 (500g 冷冻净肉)',
                'name_bm' => 'Kerang Segar Bersih (500g)',
                'short_zh' => '精选鲜活肥美血蚶，鲜香爽口，无泥沙无腥味。',
                'short_bm' => 'Kerang laut segar dibersihkan tanpa pasir, isi tebal dan manis.',
            ],
            7 => [
                'name_zh' => '整条金目鲈 / 盲曹鱼 (750g 去鳞内脏去腮)',
                'name_bm' => 'Ikan Siakap Segar Bersih Seekor (750g)',
                'short_zh' => '去鳞去内脏去腮急冻，解冻后即可清蒸或油炸，肉质鲜嫩洁白。',
                'short_bm' => 'Ikan siakap segar siap disiang, buang sisik dan insang.',
            ],
            8 => [
                'name_zh' => '红鲷鱼 / 红鱼纯肉切片 (500g)',
                'name_bm' => 'Flet Ikan Merah Segar (500g)',
                'short_zh' => '精选新鲜野生红鱼取肉切片，肉质紧实，适合香煎、煮汤或煮粥。',
                'short_bm' => 'Flet ikan merah asli tanpa tulang, sesuai untuk sup atau goreng.',
            ],
            9 => [
                'name_zh' => '野生马鲛鱼 / 鲛鱼切厚排 (500g)',
                'name_bm' => 'Kepingan Ikan Tenggiri Tebal (500g)',
                'short_zh' => '野生马鲛鱼中段厚切，油脂丰厚，香煎或咖喱烹饪风味极佳。',
                'short_bm' => 'Potongan stik ikan tenggiri tebal berkualiti tinggi.',
            ],
            12 => [
                'name_zh' => '海捕白虾 / 明虾 (1kg 急冻装)',
                'name_bm' => 'Udang Kertas Laut Segar (1kg)',
                'short_zh' => '天然海捕白虾，外壳晶莹，肉质甘甜，适合白灼或蒜蓉清蒸。',
                'short_bm' => 'Udang kertas laut segar tangkapan liar, manis semulajadi.',
            ],
            13 => [
                'name_zh' => '手工去壳抽肠大虾仁 (500g)',
                'name_bm' => 'Isi Udang Raja Bersih & Buang Urat (500g)',
                'short_zh' => '纯虾仁无添加，去壳去虾线，解冻即烹，炒菜炒饭绝配。',
                'short_bm' => 'Isi udang raja siap kopek dan buang urat, sedia dimasak.',
            ],
            14 => [
                'name_zh' => '鲜捕长尾透抽 / 火管鱿鱼 (1kg)',
                'name_bm' => 'Sotong Jarum / Loligo Segar (1kg)',
                'short_zh' => '船冻新鲜透抽，保留天然体表色素与完整脆度，爆炒极脆。',
                'short_bm' => 'Sotong jarum loligo segar beku di atas kapal.',
            ],
            15 => [
                'name_zh' => '香脆裹粉炸鱿鱼圈 (500g IQF)',
                'name_bm' => 'Cincin Sotong Calamari Rangup (500g)',
                'short_zh' => '特调黄金面衣裹粉，气炸或油炸5分钟即出锅，金黄酥脆。',
                'short_bm' => 'Cincin sotong calamari disalut tepung rangup sedia digoreng.',
            ],
            16 => [
                'name_zh' => '严选活冻青蟹 / 肉蟹 (约800g 1对装)',
                'name_bm' => 'Ketam Nipah Segar (~800g Sepasang)',
                'short_zh' => '肉质饱满实肉青蟹，蟹钳巨大，适合黑胡椒炒蟹或辣椒螃蟹。',
                'short_bm' => 'Ketam nipah berkualiti dengan sepit padu dan isi pejal.',
            ],
            17 => [
                'name_zh' => '蓝花蟹 / 远海梭子蟹 (500g 冷冻)',
                'name_bm' => 'Ketam Renjong Biru (500g)',
                'short_zh' => '天然野生花蟹，肉质鲜美清甜，无腥味，煮粥或下汤极佳。',
                'short_bm' => 'Ketam renjong biru tangkapan liar lautan dalam.',
            ],
            18 => [
                'name_zh' => '香酥软壳蟹 (1kg 整箱装)',
                'name_bm' => 'Ketam Kulit Lembut Rangup (1kg)',
                'short_zh' => '换壳期采收深冻，连壳可食，油炸酥香化渣，日料餐厅指定。',
                'short_bm' => 'Ketam kulit lembut premium boleh dimakan sepenuhnya bersama kulit.',
            ],
            19 => [
                'name_zh' => '加拿大野生刺身级带子 / 大扇贝肉 (500g)',
                'name_bm' => 'Skalop Laut Liar Kanada Gred Sashimi (500g)',
                'short_zh' => '加拿大北极冰冷水域野生采捕，刺身级干带子，煎烤鲜甜多汁。',
                'short_bm' => 'Skalop laut asli Kanada gred sashimi, isi tebal dan manis.',
            ],
            20 => [
                'name_zh' => '新西兰半壳青口贻贝 (1kg)',
                'name_bm' => 'Mussel Hijau Separuh Kulit New Zealand (1kg)',
                'short_zh' => '新西兰纯净海水养殖，半壳预熟速冻，芝士焗烤或白葡萄酒煮绝配。',
                'short_bm' => 'Mussel hijau New Zealand separuh kulit, sesuai untuk bakar keju.',
            ],
            21 => [
                'name_zh' => '特级多利鱼柳 / 龙利鱼片 (1kg 单冻无骨无刺)',
                'name_bm' => 'Flet Ikan Dory Premium Tanpa Tulang (1kg)',
                'short_zh' => '无骨无刺无腥味，肉质雪白滑嫩，适合香煎、炸鱼薯条或做鱼汤。',
                'short_bm' => 'Flet ikan dory tanpa tulang dan tanpa bau hanyir.',
            ],
            22 => [
                'name_zh' => '日式蒲烧活烤鳗鱼 (200g 浓郁蒲烧汁)',
                'name_bm' => 'Unagi Kabayaki Bakar Sos Jepun (200g)',
                'short_zh' => '秘制传统日式蒲烧酱汁碳烤，加热即食，鳗鱼饭招牌。',
                'short_bm' => 'Belut bakar unagi gaya Jepun dengan sos kabayaki asli.',
            ],
            23 => [
                'name_zh' => '豪华丰盛火锅海鲜拼盘组合 (1kg 大包装)',
                'name_bm' => 'Kombo Makanan Laut Steamboat Mewah (1kg)',
                'short_zh' => '集合虾肉、带子、鱼丸、鱿鱼圈等优质食材，家庭聚餐与火锅首选。',
                'short_bm' => 'Pilihan kombo pelbagai makanan laut premium untuk hidangan steamboat.',
            ],
            24 => [
                'name_zh' => '雪花西冷牛排 (1kg / 5块装 独立分装)',
                'name_bm' => 'Stik Striploin Lembu Meltique (1kg / 5x200g)',
                'short_zh' => '均匀大理石油花，肉质细嫩多汁，香煎5至7成熟口感极其美妙。',
                'short_bm' => 'Stik daging lembu striploin meltique empuk dan berjus.',
            ],
            25 => [
                'name_zh' => '带骨特选牛小排 / 牛仔骨 (1kg)',
                'name_bm' => 'Tulang Rusuk Daging Lembu Pendek (1kg)',
                'short_zh' => '带骨切片牛肋排，肉香浓郁带筋，适合黑椒香煎或韩式烧烤。',
                'short_bm' => 'Potongan rusuk lembu bertulang sesuai untuk panggang atau BBQ.',
            ],
            26 => [
                'name_zh' => '澳洲法式精切羊小排 (1kg)',
                'name_bm' => 'Kepingan Rusuk Kambing Perancis Australia (1kg)',
                'short_zh' => '澳洲天然草饲羔羊，法式精修骨柄无杂膻味，肉质鲜嫩细腻。',
                'short_bm' => 'Potongan rusuk kambing muda Australia dipotong gaya Perancis.',
            ],
            27 => [
                'name_zh' => '去骨无皮鲜冻鸡腿肉扒 (2kg 餐饮装)',
                'name_bm' => 'Flet Paha Ayam Tanpa Tulang & Kulit (2kg)',
                'short_zh' => '纯净去骨去皮鸡腿肉，鲜嫩不柴，适合做鸡排、日式烧鸟及咖喱鸡。',
                'short_bm' => 'Paha ayam tanpa tulang sedia untuk digoreng atau dibakar.',
            ],
            28 => [
                'name_zh' => '法式果木冷熏鸭胸肉 (200g 解冻即切)',
                'name_bm' => 'Dada Itik Asap Gourmet (200g)',
                'short_zh' => '天然木屑熏制，鸭皮油香四溢，肉质红润入味，解冻切片即食。',
                'short_bm' => 'Dada itik salai herba wangi boleh dimakan terus setelah dinyahbeku.',
            ],
            29 => [
                'name_zh' => '日式特级帝王蟹肉棒 / 蟹柳 (500g)',
                'name_bm' => 'Jejari Ketam Jepun Premium Kanikama (500g)',
                'short_zh' => '高级白身鱼肉制成，丝丝蟹肉纤维纹理，适合沙拉、寿司或火锅。',
                'short_bm' => 'Jejari ketam Kanikama gred tinggi untuk salad dan sushi.',
            ],
            30 => [
                'name_zh' => '手工透亮水晶鲜虾饺 (10粒装 纯虾肉馅)',
                'name_bm' => 'Har Kow Udang Kristal Buatan Tangan (10 biji)',
                'short_zh' => '皮薄晶莹剔透，包入满满大颗爽脆鲜虾仁，蒸熟鲜美多汁。',
                'short_bm' => 'Dim sum har kow udang kristal dengan isi udang segar padat.',
            ],
            31 => [
                'name_zh' => '传统金黄海鲜干蒸烧卖 (12粒装)',
                'name_bm' => 'Siew Mai Makanan Laut Tradisional (12 biji)',
                'short_zh' => '金黄面皮包裹鲜虾与鱼肉馅，顶缀蟹子，口感Q弹扎实。',
                'short_bm' => 'Siew mai makanan laut tradisional dengan udang dan ikan.',
            ],
            32 => [
                'name_zh' => '日式风味调味中华海草 / 裙带菜 (1kg)',
                'name_bm' => 'Rumpai Laut Perisa Jepun Chuka Wakame (1kg)',
                'short_zh' => '经典日料前菜，加入香油芝麻与红辣椒调味，酸甜脆嫩解腻。',
                'short_bm' => 'Salad rumpai laut wakame gaya Jepun dengan bijan wangi.',
            ],
            33 => [
                'name_zh' => '黄金海鲜海虾春卷 (20条 / 500g)',
                'name_bm' => 'Popia Makanan Laut Rangup (20 gulung / 500g)',
                'short_zh' => '外皮薄脆酥香，内馅精调鲜虾蔬菜，油炸后金黄酥脆。',
                'short_bm' => 'Popia mini inti makanan laut rangup kegemaran seisi keluarga.',
            ],
            34 => [
                'name_zh' => '手作抹茶与芒果冰淇淋大福麻薯 (6粒盒装)',
                'name_bm' => 'Aiskrim Mochi Mangga & Matcha Jepun (6 biji)',
                'short_zh' => '软糯糯米外皮包裹浓郁抹茶与芒果冰淇淋，清爽甜而不腻。',
                'short_bm' => 'Mochi berisi aiskrim matcha dan mangga yang lembut dan manis.',
            ],
            35 => [
                'name_zh' => '纯手工生打鲜虾滑 / 虾胶 (200g 挤管装)',
                'name_bm' => 'Pes Udang Segar Buatan Tangan (Tiub 200g)',
                'short_zh' => '高达95%纯青虾仁反复摔打，无杂质淀粉，火锅下锅极其爽脆鲜弹。',
                'short_bm' => 'Pes udang segar tulen 95% dalam tiub mudah dipicit untuk steamboat.',
            ],
        ];

        foreach ($productTranslations as $id => $data) {
            Product::where('id', $id)->update([
                'name_zh'              => $data['name_zh'],
                'name_bm'              => $data['name_bm'],
                'short_description_zh' => $data['short_zh'],
                'short_description_bm' => $data['short_bm'],
            ]);
        }
    }
}
