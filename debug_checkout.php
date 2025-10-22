<?php
require_once 'includes/db_connect.php';

// Test database connection
try {
    echo "<h2>Database Connection Test</h2>";
    echo "Database connection: OK<br>";
    
    // Check if orders table exists
    $result = $pdo->query("SHOW TABLES LIKE 'orders'");
    if ($result->rowCount() > 0) {
        echo "Orders table: EXISTS<br>";
        
        // Show orders table structure
        $structure = $pdo->query("DESCRIBE orders");
        echo "<h3>Orders Table Structure:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = $structure->fetch()) {
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
        echo "Orders table: MISSING<br>";
    }
    
    // Check if order_items table exists
    $result = $pdo->query("SHOW TABLES LIKE 'order_items'");
    if ($result->rowCount() > 0) {
        echo "Order_items table: EXISTS<br>";
        
        // Show order_items table structure
        $structure = $pdo->query("DESCRIBE order_items");
        echo "<h3>Order_items Table Structure:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = $structure->fetch()) {
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
        echo "Order_items table: MISSING<br>";
    }
    
    // Test a simple insert to orders table
    echo "<h3>Testing Order Insert</h3>";
    $test_order_number = 'TEST' . date('Ymd') . rand(1000, 9999);
    $test_billing = json_encode([
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'phone' => '1234567890',
        'address' => 'Test Address',
        'city' => 'Test City',
        'postal_code' => '12345',
        'country' => 'Sri Lanka'
    ]);
    
    $order_sql = "INSERT INTO orders (order_number, user_id, subtotal, tax_amount, shipping_amount, 
                 total_amount, currency, billing_address, shipping_address, notes, payment_method, status, payment_status, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', NOW())";
    
    $order_stmt = $pdo->prepare($order_sql);
    $success = $order_stmt->execute([
        $test_order_number,
        null, // user_id
        100.00, // subtotal
        13.00, // tax
        10.00, // shipping
        123.00, // total
        'LKR',
        $test_billing,
        $test_billing,
        'Test order',
        'cod'
    ]);
    
    if ($success) {
        echo "Test order insert: SUCCESS (Order #$test_order_number)<br>";
        $order_id = $pdo->lastInsertId();
        echo "Order ID: $order_id<br>";
        
        // Clean up test order
        $pdo->prepare("DELETE FROM orders WHERE id = ?")->execute([$order_id]);
        echo "Test order cleaned up<br>";
    } else {
        echo "Test order insert: FAILED<br>";
        print_r($order_stmt->errorInfo());
    }
    
} catch (Exception $e) {
    echo "<h2>Error Details:</h2>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>