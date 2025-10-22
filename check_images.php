<?php
require_once 'config.php';

try {
    $stmt = $pdo->query("SELECT id, product_id, image_path, is_primary FROM product_images LIMIT 5");
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Product Images in Database:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Product ID</th><th>Image URL</th><th>Is Primary</th></tr>";
    
    foreach ($images as $img) {
        echo "<tr>";
        echo "<td>" . $img['id'] . "</td>";
        echo "<td>" . $img['product_id'] . "</td>";
        echo "<td>" . $img['image_path'] . "</td>";
        echo "<td>" . ($img['is_primary'] ? 'Yes' : 'No') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check if image files exist
    echo "<h3>File Existence Check:</h3>";
    foreach ($images as $img) {
        $fullPath = $img['image_path'];
        $exists = file_exists($fullPath) ? 'EXISTS' : 'NOT FOUND';
        echo "<p>{$fullPath} - {$exists}</p>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>