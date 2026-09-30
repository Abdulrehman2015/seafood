<?php
$pdo = new PDO('sqlite:database/database.sqlite');
$stmt = $pdo->query("SELECT id, slug, title FROM policies");
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "SQLite Policy: {$r['id']} | {$r['slug']} | {$r['title']}\n";
}
