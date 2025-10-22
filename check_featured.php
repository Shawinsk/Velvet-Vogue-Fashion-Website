<?php
require_once 'includes/db_connect.php';

// Check featured products in database
echo "<h1>Featured Products Check</h1>";

try {
    // Check total products
    $total_stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
    $total = $total_stmt->fetch();
    echo "<p>Total products: " . $total['total'] . "</p>";
    
    // Check active products
    $active_stmt = $pdo->query("SELECT COUNT(*) as active FROM products WHERE status = 'active'");
    $active = $active_stmt->fetch();
    echo "<p>Active products: " . $active['active'] . "</p>";
    
    // Check featured products
    $featured_stmt = $pdo->query("SELECT COUNT(*) as featured FROM products WHERE is_featured = 1");
    $featured = $featured_stmt->fetch();
    echo "<p>Featured products: " . $featured['featured'] . "</p>";
    
    // Check active featured products
    $active_featured_stmt = $pdo->query("SELECT COUNT(*) as active_featured FROM products WHERE status = 'active' AND is_featured = 1");
    $active_featured = $active_featured_stmt->fetch();
    echo "<p>Active featured products: " . $active_featured['active_featured'] . "</p>";
    
    // Check products with images
    $with_images_stmt = $pdo->query("
        SELECT COUNT(DISTINCT p.id) as with_images 
        FROM products p 
        INNER JOIN product_images pi ON p.id = pi.product_id 
        WHERE p.status = 'active' AND p.is_featured = 1
    ");
    $with_images = $with_images_stmt->fetch();
    echo "<p>Active featured products with images: " . $with_images['with_images'] . "</p>";
    
    // Show actual featured products
    echo "<h2>Featured Products List:</h2>";
    $list_stmt = $pdo->query("
        SELECT 
            p.id, 
            p.name, 
            p.status, 
            p.is_featured,
            COUNT(pi.id) as image_count,
            GROUP_CONCAT(pi.image_path) as images
        FROM products p 
        LEFT JOIN product_images pi ON p.id = pi.product_id 
        WHERE p.is_featured = 1
        GROUP BY p.id 
        ORDER BY p.created_at DESC
    ");
    $featured_products = $list_stmt->fetchAll();
    
    if ($featured_products) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Status</th><th>Featured</th><th>Images</th></tr>";
        foreach ($featured_products as $product) {
            echo "<tr>";
            echo "<td>" . $product['id'] . "</td>";
            echo "<td>" . htmlspecialchars($product['name']) . "</td>";
            echo "<td>" . $product['status'] . "</td>";
            echo "<td>" . ($product['is_featured'] ? 'Yes' : 'No') . "</td>";
            echo "<td>" . $product['image_count'] . " images</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>No featured products found!</p>";
    }
    
    // Test the exact query from featured_products.php
    echo "<h2>Testing Featured Products Query:</h2>";
    $test_query = "
        SELECT 
            p.*,
            c.name as category_name,
            c.slug as category_slug,
            (SELECT image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) as primary_image
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.status = 'active' AND p.is_featured = 1
        AND EXISTS (
            SELECT 1 FROM product_images pi 
            WHERE pi.product_id = p.id AND pi.is_primary = 1
            AND (pi.image_path LIKE '%.jpg' OR pi.image_path LIKE '%.jpeg' OR pi.image_path LIKE '%.png')
        )
        ORDER BY p.created_at DESC
        LIMIT 8
    ";
    
    $test_stmt = $pdo->prepare($test_query);
    $test_stmt->execute();
    $test_results = $test_stmt->fetchAll();
    
    echo "<p>Query returned " . count($test_results) . " products</p>";
    
    if ($test_results) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Category</th><th>Primary Image</th></tr>";
        foreach ($test_results as $product) {
            echo "<tr>";
            echo "<td>" . $product['id'] . "</td>";
            echo "<td>" . htmlspecialchars($product['name']) . "</td>";
            echo "<td>" . htmlspecialchars($product['category_name']) . "</td>";
            echo "<td>" . ($product['primary_image'] ? htmlspecialchars($product['primary_image']) : 'No image') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

<hr>
<p><a href="make_products_featured.php">Make Products Featured</a></p>
<p><a href="index.php">Back to Homepage</a></p>