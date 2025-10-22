<?php
require_once 'includes/db_connect.php';

// Simple test to add a product with featured flag
if ($_POST && isset($_POST['test_add'])) {
    echo "<h2>Testing Simple Product Add</h2>";
    
    try {
        $name = 'Test Product ' . date('Y-m-d H:i:s');
        $description = 'This is a test product';
        $price = 100.00;
        $category_id = 1; // Assuming category 1 exists
        $stock_quantity = 10;
        $slug = strtolower(str_replace(' ', '-', $name));
        $sku = 'TEST-' . rand(1000, 9999);
        $is_featured = 1; // Set as featured
        $status = 'active';
        
        echo "<p>Inserting product with data:</p>";
        echo "<ul>";
        echo "<li>Name: " . htmlspecialchars($name) . "</li>";
        echo "<li>Description: " . htmlspecialchars($description) . "</li>";
        echo "<li>Price: " . $price . "</li>";
        echo "<li>Category ID: " . $category_id . "</li>";
        echo "<li>Stock: " . $stock_quantity . "</li>";
        echo "<li>SKU: " . $sku . "</li>";
        echo "<li>Featured: " . ($is_featured ? 'Yes' : 'No') . "</li>";
        echo "<li>Status: " . $status . "</li>";
        echo "</ul>";
        
        $stmt = $pdo->prepare("INSERT INTO products (name, slug, description, sku, price, category_id, stock_quantity, is_featured, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $result = $stmt->execute([$name, $slug, $description, $sku, $price, $category_id, $stock_quantity, $is_featured, $status]);
        
        if ($result) {
            $product_id = $pdo->lastInsertId();
            echo "<p style='color: green;'><strong>Product added successfully with ID: " . $product_id . "</strong></p>";
            
            // Verify the product was inserted correctly
            $verify_stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $verify_stmt->execute([$product_id]);
            $product = $verify_stmt->fetch();
            
            if ($product) {
                echo "<h3>Verification - Product Data in Database:</h3>";
                echo "<table border='1' style='border-collapse: collapse;'>";
                echo "<tr><th>Field</th><th>Value</th></tr>";
                foreach ($product as $key => $value) {
                    if (!is_numeric($key)) {
                        echo "<tr><td>" . htmlspecialchars($key) . "</td><td>" . htmlspecialchars($value) . "</td></tr>";
                    }
                }
                echo "</table>";
                
                // Test if this product shows up in featured products query
                echo "<h3>Featured Products Query Test:</h3>";
                $featured_query = "SELECT id, name, is_featured, status FROM products WHERE status = 'active' AND is_featured = 1 ORDER BY created_at DESC";
                $featured_stmt = $pdo->prepare($featured_query);
                $featured_stmt->execute();
                $featured_products = $featured_stmt->fetchAll();
                
                if ($featured_products) {
                    echo "<table border='1' style='border-collapse: collapse;'>";
                    echo "<tr><th>ID</th><th>Name</th><th>Featured</th><th>Status</th></tr>";
                    foreach ($featured_products as $fp) {
                        $highlight = ($fp['id'] == $product_id) ? 'style="background-color: yellow;"' : '';
                        echo "<tr {$highlight}>";
                        echo "<td>" . $fp['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($fp['name']) . "</td>";
                        echo "<td>" . ($fp['is_featured'] ? 'Yes' : 'No') . "</td>";
                        echo "<td>" . $fp['status'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    
                    $found = false;
                    foreach ($featured_products as $fp) {
                        if ($fp['id'] == $product_id) {
                            $found = true;
                            break;
                        }
                    }
                    
                    if ($found) {
                        echo "<p style='color: green;'><strong>✓ Product appears in featured products query!</strong></p>";
                    } else {
                        echo "<p style='color: red;'><strong>✗ Product does NOT appear in featured products query!</strong></p>";
                    }
                } else {
                    echo "<p style='color: red;'>No featured products found in database</p>";
                }
            } else {
                echo "<p style='color: red;'>Error: Could not verify product insertion</p>";
            }
        } else {
            echo "<p style='color: red;'>Error: Failed to insert product</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Exception: " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
}

// Get categories for the form
try {
    $categories_stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    $categories = $categories_stmt->fetchAll();
} catch (Exception $e) {
    $categories = [];
    echo "<p style='color: red;'>Error loading categories: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Simple Product Add</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 300px; padding: 8px; border: 1px solid #ddd; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        table { margin: 20px 0; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>Test Simple Product Add</h1>
    
    <form method="POST">
        <button type="submit" name="test_add" value="1">Add Test Product (Featured)</button>
    </form>
    
    <hr>
    
    <h2>Current Featured Products in Database:</h2>
    <?php
    try {
        $current_featured = $pdo->query("SELECT id, name, is_featured, status, created_at FROM products WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 10");
        $featured_list = $current_featured->fetchAll();
        
        if ($featured_list) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Name</th><th>Featured</th><th>Status</th><th>Created</th></tr>";
            foreach ($featured_list as $item) {
                echo "<tr>";
                echo "<td>" . $item['id'] . "</td>";
                echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                echo "<td>" . ($item['is_featured'] ? 'Yes' : 'No') . "</td>";
                echo "<td>" . $item['status'] . "</td>";
                echo "<td>" . $item['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No featured products found</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>