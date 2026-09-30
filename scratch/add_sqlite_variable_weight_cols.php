<?php
$sqlite = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
$sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check products columns
$stmt = $sqlite->query("PRAGMA table_info(products)");
$cols = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $cols[] = $row['name'];
}

if (!in_array('pricing_model', $cols)) {
    echo "Adding pricing_model, reference_weight, actual_weight_unit, unit_price_per_weight to SQLite products...\n";
    $sqlite->exec("ALTER TABLE products ADD COLUMN pricing_model VARCHAR(50) DEFAULT 'fixed_unit'");
    $sqlite->exec("ALTER TABLE products ADD COLUMN reference_weight VARCHAR(100) NULL");
    $sqlite->exec("ALTER TABLE products ADD COLUMN actual_weight_unit VARCHAR(20) NULL");
    $sqlite->exec("ALTER TABLE products ADD COLUMN unit_price_per_weight DECIMAL(10,2) NULL");
    echo "Done for products.\n";
} else {
    echo "SQLite products already has pricing_model.\n";
}

// Check order_items columns
$stmt = $sqlite->query("PRAGMA table_info(order_items)");
$cols = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $cols[] = $row['name'];
}

if (!in_array('pricing_model', $cols)) {
    echo "Adding variable weight columns to SQLite order_items...\n";
    $sqlite->exec("ALTER TABLE order_items ADD COLUMN pricing_model VARCHAR(50) DEFAULT 'fixed_unit'");
    $sqlite->exec("ALTER TABLE order_items ADD COLUMN reference_weight VARCHAR(100) NULL");
    $sqlite->exec("ALTER TABLE order_items ADD COLUMN actual_final_weight DECIMAL(8,3) NULL");
    $sqlite->exec("ALTER TABLE order_items ADD COLUMN unit_price_per_weight DECIMAL(10,2) NULL");
    $sqlite->exec("ALTER TABLE order_items ADD COLUMN final_calculated_amount DECIMAL(10,2) NULL");
    echo "Done for order_items.\n";
} else {
    echo "SQLite order_items already has pricing_model.\n";
}
