<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'summary',
        'status',
        'sort_order',
        'title_zh',
        'content_zh',
        'title_bm',
        'content_bm',
        'meta_title',
        'meta_description',
    ];

    /**
     * Scope a query to only include published policies.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Dynamic title based on active locale.
     */
    public function getTitleForLocaleAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'zh' && !empty($this->title_zh)) {
            return $this->title_zh;
        }
        if ($locale === 'bm' && !empty($this->title_bm)) {
            return $this->title_bm;
        }
        return $this->title;
    }

    /**
     * Dynamic content based on active locale.
     */
    public function getContentForLocaleAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'zh' && !empty($this->content_zh)) {
            return $this->content_zh;
        }
        if ($locale === 'bm' && !empty($this->content_bm)) {
            return $this->content_bm;
        }
        return $this->content;
    }

    /**
     * Dynamic summary based on active locale.
     */
    public function getSummaryForLocaleAttribute(): string
    {
        $locale = app()->getLocale();
        $summaries = [
            'privacy-policy' => [
                'en' => 'Our commitment to protecting your personal data, customer accounts, and order records.',
                'zh' => '我们致力于保护您的个人数据、客户账户及订单记录。',
                'bm' => 'Komitmen kami untuk melindungi data peribadi anda, akaun pelanggan dan rekod pesanan.',
            ],
            'terms-and-conditions' => [
                'en' => 'Clear commercial terms, quotation guidelines, order fulfillment, delivery standards, and store usage policies.',
                'zh' => '明确的商业条款、报价规范、订单履行、配送标准及商城使用政策。',
                'bm' => 'Terma komersial yang jelas, garis panduan sebut harga, pelaksanaan pesanan, standard penghantaran dan polisi penggunaan kedai.',
            ],
            'refund-policy' => [
                'en' => 'Perishable food quality guarantee, 12-hour notification requirement, and return/refund procedures.',
                'zh' => '生鲜易腐食品品质保障、12小时内通知要求及退款退换流程。',
                'bm' => 'Jaminan kualiti makanan mudah rosak, keperluan pemberitahuan 12 jam dan prosedur pemulangan/bayaran balik.',
            ],
            'shipping-policy' => [
                'en' => 'Frozen cold-chain delivery arrangements, delivery thresholds, and logistics coordination terms.',
                'zh' => '冷冻温控配送安排、起送与运费标准及物流协调条款。',
                'bm' => 'Susunan penghantaran kawalan suhu sejuk beku, ambang penghantaran dan terma penyelarasan logistik.',
            ],
            'cookie-policy' => [
                'en' => 'Explanation of essential cookies, optional preferences, and technical services used on the MST website.',
                'zh' => '关于 MST 网站使用的必要 Cookie、偏好设置及技术服务的说明。',
                'bm' => 'Penerangan mengenai kuki penting, pilihan kuki dan perkhidmatan teknikal yang digunakan di laman web MST.',
            ],
        ];

        return $summaries[$this->slug][$locale] ?? ($this->summary ?? '');
    }

    /**
     * Helper to generate unique slug.
     */
    public static function generateSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }
}
