<?php
require 'db_connect.php';

try {
    // Check products table structure
    $stmt = $pdo->query('DESCRIBE products');
    echo "Products table structure:\n";
    while($column = $stmt->fetch()) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    echo "\n";
    
    // Check total products
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM products');
    $total = $stmt->fetch();
    echo "Total products: " . $total['count'] . "\n";
    
    // Check featured products
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM products WHERE is_featured = 1');
    $featured = $stmt->fetch();
    echo "Featured products: " . $featured['count'] . "\n";
    
    // Check active products
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM products WHERE status = "active"');
    $active = $stmt->fetch();
    echo "Active products: " . $active['count'] . "\n\n";
    
    // List some products
    $stmt = $pdo->query('SELECT id, name, status, is_featured FROM products LIMIT 10');
    echo "Sample products:\n";
    while($row = $stmt->fetch()) {
        echo "- " . $row['name'] . " (ID: " . $row['id'] . ") - Status: " . $row['status'] . ", Featured: " . ($row['is_featured'] ? 'Yes' : 'No') . "\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>