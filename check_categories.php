<?php
require 'db_connect.php';

try {
    // Check total categories
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM categories');
    $total = $stmt->fetch();
    echo "Total categories: " . $total['count'] . "\n";
    
    // Check table structure first
    $stmt = $pdo->query('DESCRIBE categories');
    echo "Table structure:\n";
    while($column = $stmt->fetch()) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    echo "\n";
    
    // List all categories
    $stmt = $pdo->query('SELECT * FROM categories ORDER BY name');
    echo "Categories list:\n";
    while($row = $stmt->fetch()) {
        echo "- " . $row['name'] . " (" . $row['slug'] . ")";
        if (isset($row['image']) && $row['image']) {
            echo " - Image: " . $row['image'];
        }
        echo "\n";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>