<?php
require_once 'db_connect.php';

try {
    // First, check if orders table exists and add missing columns
    $result = $pdo->query("SHOW TABLES LIKE 'orders'");
    if ($result->rowCount() > 0) {
        // Table exists, check for missing columns and add them
        $columns = $pdo->query("DESCRIBE orders")->fetchAll(PDO::FETCH_COLUMN);
        
        if (!in_array('order_number', $columns)) {
            $pdo->exec("ALTER TABLE orders ADD COLUMN order_number VARCHAR(50) UNIQUE AFTER user_id");
        }
        if (!in_array('subtotal', $columns)) {
            $pdo->exec("ALTER TABLE orders ADD COLUMN subtotal DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER payment_method");
        }
        if (!in_array('tax_amount', $columns)) {
            $pdo->exec("ALTER TABLE orders ADD COLUMN tax_amount DECIMAL(10,2) DEFAULT 0 AFTER subtotal");
        }
        if (!in_array('shipping_amount', $columns)) {
            $pdo->exec("ALTER TABLE orders ADD COLUMN shipping_amount DECIMAL(10,2) DEFAULT 0 AFTER tax_amount");
        }
        if (!in_array('discount_amount', $columns)) {
            $pdo->exec("ALTER TABLE orders ADD COLUMN discount_amount DECIMAL(10,2) DEFAULT 0 AFTER shipping_amount");
        }
        if (!in_array('currency', $columns)) {
            $pdo->exec("ALTER TABLE orders ADD COLUMN currency VARCHAR(3) DEFAULT 'LKR' AFTER total_amount");
        }
        
        // Update billing_address and shipping_address to JSON if they're TEXT
        $pdo->exec("ALTER TABLE orders MODIFY COLUMN billing_address JSON");
        $pdo->exec("ALTER TABLE orders MODIFY COLUMN shipping_address JSON");
        
        echo "Orders table updated with missing columns!\n";
    } else {
        // Create orders table if it doesn't exist
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT DEFAULT NULL,
                order_number VARCHAR(50) UNIQUE NOT NULL,
                status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending',
                payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
                payment_method VARCHAR(50),
                subtotal DECIMAL(10,2) NOT NULL,
                tax_amount DECIMAL(10,2) DEFAULT 0,
                shipping_amount DECIMAL(10,2) DEFAULT 0,
                discount_amount DECIMAL(10,2) DEFAULT 0,
                total_amount DECIMAL(10,2) NOT NULL,
                currency VARCHAR(3) DEFAULT 'LKR',
                billing_address JSON NOT NULL,
                shipping_address JSON NOT NULL,
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
                INDEX idx_user_id (user_id),
                INDEX idx_order_number (order_number),
                INDEX idx_status (status),
                INDEX idx_payment_status (payment_status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "Orders table created successfully!\n";
    }
    
    echo "Orders table created successfully!\n";
    
    // Check if table exists and show structure
    $result = $pdo->query("DESCRIBE orders");
    echo "\nOrders table structure:\n";
    while ($row = $result->fetch()) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
    
    // Insert sample order if table is empty
    $count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    if ($count == 0) {
        echo "\nInserting sample order...\n";
        $pdo->exec("
            INSERT INTO orders (order_number, total_amount, billing_address, shipping_address) 
            VALUES (
                'ORD-001', 
                99.99, 
                '{\"name\": \"John Doe\", \"address\": \"123 Main St\", \"city\": \"Colombo\", \"country\": \"Sri Lanka\"}',
                '{\"name\": \"John Doe\", \"address\": \"123 Main St\", \"city\": \"Colombo\", \"country\": \"Sri Lanka\"}'
            )
        ");
        echo "Sample order inserted successfully!\n";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>