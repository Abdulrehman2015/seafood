<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=oceanfresh', 'root', '');
$stmt = $pdo->query('SHOW TABLES');
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo "Connected to MySQL successfully!\n";
echo "Tables in oceanfresh: " . implode(', ', $tables) . "\n\n";

if (in_array('products', $tables)) {
    $count = $pdo->query('SELECT count(*) FROM products')->fetchColumn();
    echo "Products count: $count\n";
}
if (in_array('users', $tables)) {
    $count = $pdo->query('SELECT count(*) FROM users')->fetchColumn();
    echo "Users count: $count\n";
}
if (in_array('categories', $tables)) {
    $count = $pdo->query('SELECT count(*) FROM categories')->fetchColumn();
    echo "Categories count: $count\n";
}
