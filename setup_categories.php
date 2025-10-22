<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Categories - Velvet Vogue</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; background: #f0f8f0; padding: 10px; border-radius: 5px; }
        .error { color: red; background: #fff0f0; padding: 10px; border-radius: 5px; }
        .info { color: blue; background: #f0f0ff; padding: 10px; border-radius: 5px; }
        .btn { background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
        .btn:hover { background: #005a87; }
    </style>
</head>
<body>
    <h1>Velvet Vogue - Categories Setup</h1>
    
    <?php
    require_once 'db_connect.php';
    
    if (isset($_GET['action']) && $_GET['action'] === 'populate') {
        try {
            // Check if categories table exists and has data
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
            $result = $stmt->fetch();
            
            if ($result['count'] == 0) {
                echo '<div class="info">Adding sample categories...</div>';
                
                // Insert sample categories
                $categories = [
                    ['Women\'s Dresses', 'womens-dresses', 'Elegant collection of dresses for all occasions', 'active'],
                    ['Women\'s Tops', 'womens-tops', 'Stylish tops and blouses', 'active'],
                    ['Women\'s Bottoms', 'womens-bottoms', 'Pants, skirts, and shorts', 'active'],
                    ['Men\'s Shirts', 'mens-shirts', 'Casual and formal shirts', 'active'],
                    ['Men\'s Pants', 'mens-pants', 'Trousers and jeans', 'active'],
                    ['Accessories', 'accessories', 'Bags, jewelry, and more', 'active'],
                    ['Footwear', 'footwear', 'Shoes and sandals', 'active'],
                    ['New Arrivals', 'new-arrivals', 'Latest fashion collection', 'active'],
                    ['Outerwear', 'outerwear', 'Jackets, coats, and blazers', 'active'],
                    ['Athleisure', 'athleisure', 'Athletic and leisure wear', 'active']
                ];
                
                $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description, status) VALUES (?, ?, ?, ?)");
                
                $added = 0;
                foreach ($categories as $category) {
                    try {
                        $stmt->execute($category);
                        echo '<div class="success">✓ Added category: ' . htmlspecialchars($category[0]) . '</div>';
                        $added++;
                    } catch (PDOException $e) {
                        echo '<div class="error">✗ Failed to add ' . htmlspecialchars($category[0]) . ': ' . $e->getMessage() . '</div>';
                    }
                }
                
                echo '<div class="success"><strong>Categories populated successfully! Added ' . $added . ' categories.</strong></div>';
                
                // Verify insertion
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories WHERE status = 'active'");
                $result = $stmt->fetch();
                echo '<div class="info">Total active categories: ' . $result['count'] . '</div>';
                
            } else {
                echo '<div class="info">Categories table already has ' . $result['count'] . ' entries.</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="error">Database Error: ' . $e->getMessage() . '</div>';
            
            // Try to create the categories table if it doesn't exist
            try {
                $createTable = "
                CREATE TABLE IF NOT EXISTS categories (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    name varchar(255) NOT NULL,
                    slug varchar(255) NOT NULL,
                    description text,
                    image varchar(255) DEFAULT NULL,
                    status enum('active','inactive') DEFAULT 'active',
                    parent_id int(11) DEFAULT NULL,
                    sort_order int(11) DEFAULT 0,
                    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (id),
                    UNIQUE KEY slug (slug),
                    KEY parent_id (parent_id),
                    KEY status (status)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                ";
                
                $pdo->exec($createTable);
                echo '<div class="success">Categories table created successfully!</div>';
                echo '<div class="info">Please click "Populate Categories" again to add sample data.</div>';
                
            } catch (PDOException $e2) {
                echo '<div class="error">Error creating table: ' . $e2->getMessage() . '</div>';
            }
        }
    }
    
    // Show current status
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total, COUNT(CASE WHEN status = 'active' THEN 1 END) as active FROM categories");
        $result = $stmt->fetch();
        
        echo '<h2>Current Status</h2>';
        echo '<div class="info">Total Categories: ' . $result['total'] . '</div>';
        echo '<div class="info">Active Categories: ' . $result['active'] . '</div>';
        
        if ($result['active'] > 0) {
            echo '<div class="success">✓ Categories are available! Your homepage should now show the Shop by Category section.</div>';
            echo '<a href="index.php" class="btn">View Homepage</a>';
            
            // Show existing categories
            $stmt = $pdo->query("SELECT name, status FROM categories ORDER BY name");
            $categories = $stmt->fetchAll();
            
            echo '<h3>Existing Categories:</h3><ul>';
            foreach ($categories as $category) {
                $statusColor = $category['status'] === 'active' ? 'green' : 'red';
                echo '<li>' . htmlspecialchars($category['name']) . ' <span style="color: ' . $statusColor . '">(' . $category['status'] . ')</span></li>';
            }
            echo '</ul>';
        } else {
            echo '<div class="error">No active categories found. Please populate the database.</div>';
        }
        
    } catch (PDOException $e) {
        echo '<div class="error">Could not check categories table: ' . $e->getMessage() . '</div>';
        echo '<div class="info">The categories table might not exist yet.</div>';
    }
    ?>
    
    <h2>Actions</h2>
    <a href="?action=populate" class="btn">Populate Categories</a>
    <a href="index.php" class="btn">View Homepage</a>
    <a href="admin/manage_categories.php" class="btn">Manage Categories (Admin)</a>
    
    <h2>Instructions</h2>
    <ol>
        <li>Click "Populate Categories" to add sample categories to your database</li>
        <li>Once categories are added, visit the homepage to see the "Shop by Category" section</li>
        <li>Use the admin panel to manage categories (add, edit, delete)</li>
    </ol>
    
</body>
</html>