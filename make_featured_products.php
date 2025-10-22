<?php
require 'db_connect.php';

try {
    // Make first 8 products featured
    $stmt = $pdo->prepare('UPDATE products SET is_featured = 1 WHERE id <= 8 AND status = "active"');
    $stmt->execute();
    echo "Products made featured: " . $stmt->rowCount() . "\n";
    
    // Verify featured products
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM products WHERE is_featured = 1');
    $result = $stmt->fetch();
    echo "Featured products now: " . $result['count'] . "\n";
    
    // List featured products
    $stmt = $pdo->query('SELECT id, name FROM products WHERE is_featured = 1 ORDER BY id');
    echo "\nFeatured products:\n";
    while($row = $stmt->fetch()) {
        echo "- " . $row['name'] . " (ID: " . $row['id'] . ")\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>