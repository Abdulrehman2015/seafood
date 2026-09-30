<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=oceanfresh', 'root', '');
$stmt = $pdo->query("SELECT id, slug, title, content, content_zh, content_bm FROM policies");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "=== Policy: {$row['slug']} ===\n";
    foreach (['content' => 'EN', 'content_zh' => 'ZH', 'content_bm' => 'BM'] as $field => $lang) {
        $text = $row[$field] ?? '';
        if (preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/i', $text, $matches)) {
            foreach ($matches[1] as $idx => $href) {
                echo "  [$lang] Link: href='{$href}' text='{$matches[2][$idx]}'\n";
            }
        }
    }
}
