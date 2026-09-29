<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
$stmt = $db->query('SELECT count(*) as count FROM products');
echo 'Products in SQLite: ' . $stmt->fetch(PDO::FETCH_ASSOC)['count'] . PHP_EOL;
$stmt2 = $db->query('SELECT count(*) as count FROM categories');
echo 'Categories in SQLite: ' . $stmt2->fetch(PDO::FETCH_ASSOC)['count'] . PHP_EOL;
$stmt3 = $db->query('SELECT count(*) as count FROM users');
echo 'Users in SQLite: ' . $stmt3->fetch(PDO::FETCH_ASSOC)['count'] . PHP_EOL;
