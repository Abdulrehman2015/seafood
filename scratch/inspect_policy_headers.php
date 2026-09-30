<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=oceanfresh', 'root', '');
$stmt = $pdo->query("SELECT id, slug, title, updated_at, content, content_zh, content_bm FROM policies");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "=========================================================\n";
    echo "ID: {$row['id']} | Slug: {$row['slug']} | Title: {$row['title']} | Updated: {$row['updated_at']}\n";
    echo "--- EN (first 400 chars) ---\n" . substr(strip_tags($row['content']), 0, 400) . "\n";
    echo "--- ZH (first 400 chars) ---\n" . mb_substr(strip_tags($row['content_zh'] ?? ''), 0, 400) . "\n";
    echo "--- BM (first 400 chars) ---\n" . substr(strip_tags($row['content_bm'] ?? ''), 0, 400) . "\n";
}
