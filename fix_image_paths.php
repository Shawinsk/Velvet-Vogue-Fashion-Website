<?php
require 'db_connect.php';

try {
    // Update image paths to correct folder
    $stmt = $pdo->prepare("UPDATE product_images SET image_path = REPLACE(image_path, 'assets/images/products/', 'assets/images/product_images/')");
    $stmt->execute();
    echo "Image paths updated: " . $stmt->rowCount() . "\n";
    
    // Verify updated paths
    $stmt = $pdo->query('SELECT product_id, image_path FROM product_images WHERE is_primary = 1 LIMIT 10');
    echo "\nUpdated image paths:\n";
    while($row = $stmt->fetch()) {
        echo "- Product ID: " . $row['product_id'] . " - Path: " . $row['image_path'] . "\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>