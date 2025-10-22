<?php
require_once 'includes/db_connect.php';

// Make some existing products featured
if (isset($_POST['make_featured'])) {
    echo "<h2>Making Products Featured</h2>";
    
    try {
        // Get all active products
        $stmt = $pdo->query("SELECT id, name, status FROM products WHERE status = 'active' ORDER BY created_at DESC LIMIT 10");
        $products = $stmt->fetchAll();
        
        if ($products) {
            echo "<p>Found " . count($products) . " active products. Making them featured...</p>";
            
            $update_stmt = $pdo->prepare("UPDATE products SET is_featured = 1 WHERE id = ?");
            $updated_count = 0;
            
            foreach ($products as $product) {
                $result = $update_stmt->execute([$product['id']]);
                if ($result) {
                    echo "<p>✓ Made product '" . htmlspecialchars($product['name']) . "' (ID: " . $product['id'] . ") featured</p>";
                    $updated_count++;
                } else {
                    echo "<p>✗ Failed to update product '" . htmlspecialchars($product['name']) . "' (ID: " . $product['id'] . ")</p>";
                }
            }
            
            echo "<p><strong>Updated " . $updated_count . " products to featured status.</strong></p>";
            
            // Verify the update
            echo "<h3>Verification - Featured Products:</h3>";
            $verify_stmt = $pdo->query("SELECT id, name, is_featured, status FROM products WHERE is_featured = 1 ORDER BY created_at DESC");
            $featured_products = $verify_stmt->fetchAll();
            
            if ($featured_products) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Name</th><th>Featured</th><th>Status</th></tr>";
                foreach ($featured_products as $fp) {
                    echo "<tr>";
                    echo "<td>" . $fp['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($fp['name']) . "</td>";
                    echo "<td>" . ($fp['is_featured'] ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . $fp['status'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='color: red;'>No featured products found after update!</p>";
            }
            
        } else {
            echo "<p style='color: red;'>No active products found in database</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
}

// Add sample images to products that don't have any
if (isset($_POST['add_sample_images'])) {
    echo "<h2>Adding Sample Images to Products</h2>";
    
    try {
        // Get products without images
        $stmt = $pdo->query("
            SELECT p.id, p.name, p.sku 
            FROM products p 
            LEFT JOIN product_images pi ON p.id = pi.product_id 
            WHERE pi.id IS NULL 
            AND p.status = 'active'
            LIMIT 5
        ");
        $products_without_images = $stmt->fetchAll();
        
        if ($products_without_images) {
            echo "<p>Found " . count($products_without_images) . " products without images. Adding sample images...</p>";
            
            // Sample image files that exist in the directory
            $sample_images = [
                'assets/images/product_images/handbag-1.jpg',
                'assets/images/product_images/handbag-2.jpg',
                'assets/images/product_images/mens-shirt-1.jpg',
                'assets/images/product_images/mens-shirt-2.jpg',
                'assets/images/product_images/mens-suit-1.jpg',
                'assets/images/product_images/sunglasses-1.jpg'
            ];
            
            $img_stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_url, alt_text, is_primary, sort_order) VALUES (?, ?, ?, ?, ?)");
            
            foreach ($products_without_images as $index => $product) {
                $image_url = $sample_images[$index % count($sample_images)];
                $alt_text = $product['name'] . ' - Sample Image';
                $is_primary = 1; // First image is primary
                $sort_order = 1;
                
                $result = $img_stmt->execute([$product['id'], $image_url, $alt_text, $is_primary, $sort_order]);
                
                if ($result) {
                    echo "<p>✓ Added sample image to '" . htmlspecialchars($product['name']) . "' (ID: " . $product['id'] . ")</p>";
                } else {
                    echo "<p>✗ Failed to add image to '" . htmlspecialchars($product['name']) . "' (ID: " . $product['id'] . ")</p>";
                }
            }
            
        } else {
            echo "<p>All active products already have images</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Make Products Featured</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; cursor: pointer; margin: 10px 0; }
        table { margin: 20px 0; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>Make Products Featured & Add Sample Images</h1>
    
    <form method="POST">
        <button type="submit" name="make_featured" value="1">Make Existing Products Featured</button>
    </form>
    
    <form method="POST">
        <button type="submit" name="add_sample_images" value="1">Add Sample Images to Products</button>
    </form>
    
    <hr>
    
    <h2>Current Database Status:</h2>
    
    <?php
    try {
        // Show current products status
        echo "<h3>Products Summary:</h3>";
        $summary_stmt = $pdo->query("
            SELECT 
                COUNT(*) as total_products,
                SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured_products,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_products
            FROM products
        ");
        $summary = $summary_stmt->fetch();
        
        echo "<ul>";
        echo "<li>Total Products: " . $summary['total_products'] . "</li>";
        echo "<li>Featured Products: " . $summary['featured_products'] . "</li>";
        echo "<li>Active Products: " . $summary['active_products'] . "</li>";
        echo "</ul>";
        
        // Show products with images
        echo "<h3>Products with Images:</h3>";
        $images_stmt = $pdo->query("
            SELECT 
                p.id, 
                p.name, 
                p.is_featured, 
                p.status,
                COUNT(pi.id) as image_count,
                GROUP_CONCAT(pi.image_url) as images
            FROM products p 
            LEFT JOIN product_images pi ON p.id = pi.product_id 
            GROUP BY p.id 
            ORDER BY p.created_at DESC 
            LIMIT 10
        ");
        $products_with_images = $images_stmt->fetchAll();
        
        if ($products_with_images) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Name</th><th>Featured</th><th>Status</th><th>Images</th></tr>";
            foreach ($products_with_images as $product) {
                echo "<tr>";
                echo "<td>" . $product['id'] . "</td>";
                echo "<td>" . htmlspecialchars($product['name']) . "</td>";
                echo "<td>" . ($product['is_featured'] ? 'Yes' : 'No') . "</td>";
                echo "<td>" . $product['status'] . "</td>";
                echo "<td>" . $product['image_count'] . " images</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>