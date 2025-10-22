<?php
require_once 'includes/db_connect.php';

// Debug products and images
echo "<h1>Debug Products and Images</h1>";

// Check products table
echo "<h2>Products Table:</h2>";
try {
    $stmt = $pdo->query("SELECT id, name, status, is_featured, created_at FROM products ORDER BY id DESC LIMIT 10");
    $products = $stmt->fetchAll();
    
    if ($products) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Status</th><th>Featured</th><th>Created</th></tr>";
        foreach ($products as $product) {
            echo "<tr>";
            echo "<td>" . $product['id'] . "</td>";
            echo "<td>" . htmlspecialchars($product['name']) . "</td>";
            echo "<td>" . $product['status'] . "</td>";
            echo "<td>" . ($product['is_featured'] ? 'Yes' : 'No') . "</td>";
            echo "<td>" . $product['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No products found</p>";
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Check product_images table
echo "<h2>Product Images Table:</h2>";
try {
    $stmt = $pdo->query("SELECT pi.id, pi.product_id, pi.image_path, pi.is_primary, p.name as product_name FROM product_images pi LEFT JOIN products p ON pi.product_id = p.id ORDER BY pi.id DESC LIMIT 10");
    $images = $stmt->fetchAll();
    
    if ($images) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Product ID</th><th>Product Name</th><th>Image URL</th><th>Primary</th><th>File Exists</th></tr>";
        foreach ($images as $image) {
            $file_path = $image['image_path'];
            $file_exists = file_exists($file_path) ? 'Yes' : 'No';
            
            echo "<tr>";
            echo "<td>" . $image['id'] . "</td>";
            echo "<td>" . $image['product_id'] . "</td>";
            echo "<td>" . htmlspecialchars($image['product_name']) . "</td>";
            echo "<td>" . htmlspecialchars($image['image_path']) . "</td>";
            echo "<td>" . ($image['is_primary'] ? 'Yes' : 'No') . "</td>";
            echo "<td>" . $file_exists . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No product images found</p>";
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Test featured products query
echo "<h2>Featured Products Query Test:</h2>";
try {
    $products_query = "
        SELECT 
            p.*,
            c.name as category_name,
            c.slug as category_slug,
            (SELECT image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) as primary_image
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.status = 'active' AND p.is_featured = 1
        ORDER BY p.created_at DESC
        LIMIT 8
    ";
    
    $products_stmt = $pdo->prepare($products_query);
    $products_stmt->execute();
    $featured_products = $products_stmt->fetchAll();
    
    if ($featured_products) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Category</th><th>Primary Image</th><th>Image Exists</th></tr>";
        foreach ($featured_products as $product) {
            $image_exists = 'No';
            if ($product['primary_image']) {
                $image_exists = file_exists($product['primary_image']) ? 'Yes' : 'No';
            }
            
            echo "<tr>";
            echo "<td>" . $product['id'] . "</td>";
            echo "<td>" . htmlspecialchars($product['name']) . "</td>";
            echo "<td>" . htmlspecialchars($product['category_name']) . "</td>";
            echo "<td>" . htmlspecialchars($product['primary_image'] ?: 'None') . "</td>";
            echo "<td>" . $image_exists . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No featured products found</p>";
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Check upload directory
echo "<h2>Upload Directory Check:</h2>";
$upload_dir = 'assets/images/product_images/';
echo "<p>Directory: " . $upload_dir . "</p>";
echo "<p>Exists: " . (file_exists($upload_dir) ? 'Yes' : 'No') . "</p>";
echo "<p>Writable: " . (is_writable($upload_dir) ? 'Yes' : 'No') . "</p>";

if (file_exists($upload_dir)) {
    $files = scandir($upload_dir);
    $image_files = array_filter($files, function($file) {
        return !in_array($file, ['.', '..']) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file);
    });
    
    echo "<p>Image files in directory: " . count($image_files) . "</p>";
    if ($image_files) {
        echo "<ul>";
        foreach (array_slice($image_files, 0, 10) as $file) {
            echo "<li>" . htmlspecialchars($file) . "</li>";
        }
        if (count($image_files) > 10) {
            echo "<li>... and " . (count($image_files) - 10) . " more files</li>";
        }
        echo "</ul>";
    }
}
?>