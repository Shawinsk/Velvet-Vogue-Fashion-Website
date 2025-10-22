<?php
require 'db_connect.php';

try {
    // Check if product_images table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'product_images'");
    $table_exists = $stmt->fetch();
    
    if ($table_exists) {
        echo "Product_images table exists\n";
        
        // Check table structure
        $stmt = $pdo->query('DESCRIBE product_images');
        echo "\nProduct_images table structure:\n";
        while($column = $stmt->fetch()) {
            echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
        }
        
        // Check total images
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM product_images');
        $total = $stmt->fetch();
        echo "\nTotal product images: " . $total['count'] . "\n";
        
        // Check primary images
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM product_images WHERE is_primary = 1');
        $primary = $stmt->fetch();
        echo "Primary images: " . $primary['count'] . "\n";
        
        // Check images for featured products
        $stmt = $pdo->query('SELECT COUNT(DISTINCT pi.product_id) as count FROM product_images pi JOIN products p ON pi.product_id = p.id WHERE p.is_featured = 1 AND pi.is_primary = 1');
        $featured_with_images = $stmt->fetch();
        echo "Featured products with primary images: " . $featured_with_images['count'] . "\n";
        
        // List some product images
        $stmt = $pdo->query('SELECT pi.product_id, pi.image_path, pi.is_primary, p.name FROM product_images pi JOIN products p ON pi.product_id = p.id WHERE p.is_featured = 1 LIMIT 10');
        echo "\nSample featured product images:\n";
        while($row = $stmt->fetch()) {
            echo "- Product: " . $row['name'] . " (ID: " . $row['product_id'] . ") - Image: " . $row['image_path'] . " - Primary: " . ($row['is_primary'] ? 'Yes' : 'No') . "\n";
        }
        
    } else {
        echo "Product_images table does not exist\n";
        
        // Check if products table has image column
        $stmt = $pdo->query('SELECT id, name, image FROM products WHERE is_featured = 1 LIMIT 5');
        echo "\nFeatured products with image column:\n";
        while($row = $stmt->fetch()) {
            echo "- " . $row['name'] . " (ID: " . $row['id'] . ") - Image: " . ($row['image'] ?: 'No image') . "\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>