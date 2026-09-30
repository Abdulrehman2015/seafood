<?php

function showSections($file, $patterns) {
    $content = file_get_contents($file);
    echo "\n##############################################################\n";
    echo "FILE: $file\n";
    echo "##############################################################\n";
    foreach ($patterns as $name => $regex) {
        preg_match_all($regex, $content, $matches, PREG_OFFSET_CAPTURE);
        echo "=== PATTERN: $name ===\n";
        if (empty($matches[0])) {
            echo "  (No match)\n";
        } else {
            foreach ($matches[0] as $m) {
                $start = max(0, $m[1] - 100);
                $snippet = substr($content, $start, 350);
                echo "--------------------------------------------------\n";
                echo trim(strip_tags($snippet)) . "\n";
            }
        }
    }
}

$patterns = [
    '12-hour' => '/.{0,50}(?:12\s*(?:hours?|jam|小时)|delivery\s*completed|pengambilan\s*sendiri|到店自提).{0,50}/ui',
    'failed_delivery' => '/.{0,50}(?:failed\s*delivery|kegagalan\s*penghantaran|配送失败|redelivery|caj|penghantaran\s*semula).{0,50}/ui',
    'walk_in' => '/.{0,50}(?:walk-in|self-collection|kaunter|counter|2号|柜台|门店选购|Masuk Sendiri).{0,50}/ui',
    'international' => '/.{0,50}(?:international|cross-border|rentas\s*sempadan|antarabangsa|国际|跨境).{0,50}/ui',
    'payment' => '/.{0,50}(?:payment|pembayaran|kad|card|transaksi|交易|支付|银行卡).{0,50}/ui',
    'marketing' => '/.{0,50}(?:marketing|pemasaran|consent|persetujuan|pemasaran|营销|同意|WhatsApp|Email).{0,50}/ui',
];

showSections(__DIR__ . '/policy_shipping-policy_en.html', $patterns);
showSections(__DIR__ . '/policy_shipping-policy_zh.html', $patterns);
showSections(__DIR__ . '/policy_shipping-policy_bm.html', $patterns);

showSections(__DIR__ . '/policy_privacy-policy_en.html', $patterns);
showSections(__DIR__ . '/policy_privacy-policy_zh.html', $patterns);
showSections(__DIR__ . '/policy_privacy-policy_bm.html', $patterns);

showSections(__DIR__ . '/policy_terms-and-conditions_en.html', $patterns);
showSections(__DIR__ . '/policy_terms-and-conditions_zh.html', $patterns);
showSections(__DIR__ . '/policy_terms-and-conditions_bm.html', $patterns);
