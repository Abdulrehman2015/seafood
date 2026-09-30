<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=oceanfresh', 'root', '');
$stmt = $pdo->query("SELECT id, slug, title, updated_at, content, content_zh, content_bm FROM policies");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "=========================================================\n";
    echo "ID: {$row['id']} | Slug: {$row['slug']} | Title: {$row['title']} | Updated: {$row['updated_at']}\n";
    preg_match_all('/(Effective|Updated|Date|September|Sep|2026|2025|2024)[^<>\n]*/i', $row['content'], $m1);
    echo "EN Date mentions: " . json_encode(array_slice($m1[0], 0, 10)) . "\n";
    preg_match_all('/(生效|更新|日期|2026|9月)[^<>\n]*/u', $row['content_zh'] ?? '', $m2);
    echo "ZH Date mentions: " . json_encode(array_slice($m2[0], 0, 10)) . "\n";
    preg_match_all('/(Berkuat|Kemas kini|Tarikh|September|2026)[^<>\n]*/i', $row['content_bm'] ?? '', $m3);
    echo "BM Date mentions: " . json_encode(array_slice($m3[0], 0, 10)) . "\n";
}
