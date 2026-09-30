<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=oceanfresh', 'root', '');
$stmt = $pdo->query("SELECT * FROM settings WHERE `key` LIKE '%whatsapp%' OR `key` LIKE '%phone%' OR `key` LIKE '%name%' OR `key` LIKE '%slogan%' OR `key` LIKE '%tagline%'");
while($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "{$r['key']} => {$r['value']}\n";
}
