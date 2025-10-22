<?php
/**
 * Populate Categories Script
 * This script adds sample categories to the database
 */

require_once 'db_connect.php';

try {
    // Check if categories table exists and has data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        echo "Adding sample categories...\n";
        
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
        
        foreach ($categories as $category) {
            $stmt->execute($category);
            echo "Added category: {$category[0]}\n";
        }
        
        echo "\nCategories populated successfully!\n";
        
        // Verify insertion
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories WHERE status = 'active'");
        $result = $stmt->fetch();
        echo "Total active categories: {$result['count']}\n";
        
    } else {
        echo "Categories table already has {$result['count']} entries.\n";
        
        // Show existing categories
        $stmt = $pdo->query("SELECT name, status FROM categories ORDER BY name");
        $categories = $stmt->fetchAll();
        
        echo "\nExisting categories:\n";
        foreach ($categories as $category) {
            echo "- {$category['name']} ({$category['status']})\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
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
        echo "Categories table created successfully!\n";
        echo "Please run this script again to populate the categories.\n";
        
    } catch (PDOException $e2) {
        echo "Error creating table: " . $e2->getMessage() . "\n";
    }
}
?>