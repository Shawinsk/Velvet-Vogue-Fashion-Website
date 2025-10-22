<?php
require_once 'db_connect.php';

try {
    echo "<h2>Fixing Orders Table Structure</h2>";
    
    // Check if orders table exists
    $result = $pdo->query("SHOW TABLES LIKE 'orders'");
    if ($result->rowCount() > 0) {
        echo "Orders table exists. Checking structure...<br>";
        
        // Get current columns
        $columns = [];
        $columnInfo = $pdo->query("DESCRIBE orders")->fetchAll();
        foreach ($columnInfo as $col) {
            $columns[] = $col['Field'];
        }
        
        echo "Current columns: " . implode(', ', $columns) . "<br><br>";
        
        // Add missing columns one by one
        if (!in_array('order_number', $columns)) {
            echo "Adding order_number column...<br>";
            $pdo->exec("ALTER TABLE orders ADD COLUMN order_number VARCHAR(50) AFTER user_id");
            // Generate order numbers for existing records
            $pdo->exec("UPDATE orders SET order_number = CONCAT('VV', DATE_FORMAT(created_at, '%Y%m%d'), LPAD(id, 4, '0')) WHERE order_number IS NULL");
            $pdo->exec("ALTER TABLE orders ADD UNIQUE KEY unique_order_number (order_number)");
        }
        
        if (!in_array('subtotal', $columns)) {
            echo "Adding subtotal column...<br>";
            $pdo->exec("ALTER TABLE orders ADD COLUMN subtotal DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER payment_method");
            // Set subtotal to total_amount for existing records
            $pdo->exec("UPDATE orders SET subtotal = total_amount WHERE subtotal = 0");
        }
        
        if (!in_array('tax_amount', $columns)) {
            echo "Adding tax_amount column...<br>";
            $pdo->exec("ALTER TABLE orders ADD COLUMN tax_amount DECIMAL(10,2) DEFAULT 0 AFTER subtotal");
        }
        
        if (!in_array('shipping_amount', $columns)) {
            echo "Adding shipping_amount column...<br>";
            $pdo->exec("ALTER TABLE orders ADD COLUMN shipping_amount DECIMAL(10,2) DEFAULT 0 AFTER tax_amount");
        }
        
        if (!in_array('discount_amount', $columns)) {
            echo "Adding discount_amount column...<br>";
            $pdo->exec("ALTER TABLE orders ADD COLUMN discount_amount DECIMAL(10,2) DEFAULT 0 AFTER shipping_amount");
        }
        
        if (!in_array('currency', $columns)) {
            echo "Adding currency column...<br>";
            $pdo->exec("ALTER TABLE orders ADD COLUMN currency VARCHAR(3) DEFAULT 'LKR' AFTER total_amount");
        }
        
        // Handle address columns - convert TEXT to JSON safely
        $addressColumns = $pdo->query("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'orders' AND COLUMN_NAME IN ('billing_address', 'shipping_address')")->fetchAll();
        
        foreach ($addressColumns as $col) {
            if ($col['DATA_TYPE'] === 'text') {
                echo "Converting {$col['COLUMN_NAME']} from TEXT to JSON...<br>";
                
                // First, update invalid JSON data
                $invalidRows = $pdo->query("SELECT id, {$col['COLUMN_NAME']} FROM orders WHERE {$col['COLUMN_NAME']} IS NOT NULL AND {$col['COLUMN_NAME']} != '' AND JSON_VALID({$col['COLUMN_NAME']}) = 0")->fetchAll();
                
                foreach ($invalidRows as $row) {
                    $address = $row[$col['COLUMN_NAME']];
                    // Try to create a valid JSON from the text
                    $jsonAddress = json_encode([
                        'address' => $address,
                        'city' => '',
                        'country' => 'Sri Lanka'
                    ]);
                    $pdo->prepare("UPDATE orders SET {$col['COLUMN_NAME']} = ? WHERE id = ?")->execute([$jsonAddress, $row['id']]);
                }
                
                // Now convert the column type
                $pdo->exec("ALTER TABLE orders MODIFY COLUMN {$col['COLUMN_NAME']} JSON");
            }
        }
        
        echo "<br><strong>Orders table structure updated successfully!</strong><br><br>";
        
        // Show final structure
        $finalStructure = $pdo->query("DESCRIBE orders")->fetchAll();
        echo "<h3>Final Orders Table Structure:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach ($finalStructure as $row) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>{$row['Key']}</td>";
            echo "<td>{$row['Default']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "Orders table does not exist. Creating new table...<br>";
        
        $pdo->exec("
            CREATE TABLE orders (
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
        
        echo "Orders table created successfully!<br>";
    }
    
    // Also ensure order_items table exists with correct structure
    $result = $pdo->query("SHOW TABLES LIKE 'order_items'");
    if ($result->rowCount() == 0) {
        echo "<br>Creating order_items table...<br>";
        $pdo->exec("
            CREATE TABLE order_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                product_name VARCHAR(255) NOT NULL,
                product_sku VARCHAR(100) NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                total DECIMAL(10,2) NOT NULL,
                attributes JSON DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                INDEX idx_order_id (order_id),
                INDEX idx_product_id (product_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "Order_items table created successfully!<br>";
    } else {
        echo "<br>Order_items table already exists.<br>";
    }
    
} catch (Exception $e) {
    echo "<h2>Error:</h2>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}
?>