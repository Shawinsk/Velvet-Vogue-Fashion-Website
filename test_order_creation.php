<?php
require_once 'includes/db_connect.php';

echo "<h2>Testing Order Creation Process</h2>";

try {
    // Test data similar to what checkout.php would send
    $test_data = [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'phone' => '1234567890',
        'address' => 'Test Address 123',
        'city' => 'Colombo',
        'postal_code' => '10100',
        'country' => 'Sri Lanka',
        'payment_method' => 'cod',
        'notes' => 'Test order'
    ];
    
    $cartTotals = [
        'subtotal' => 1000.00,
        'tax' => 130.00,
        'shipping' => 500.00,
        'total' => 1630.00
    ];
    
    echo "<h3>Step 1: Testing Database Transaction</h3>";
    $pdo->beginTransaction();
    echo "Transaction started successfully.<br>";
    
    echo "<h3>Step 2: Generating Order Number</h3>";
    $order_number = 'VV' . date('Ymd') . rand(1000, 9999);
    echo "Order number generated: $order_number<br>";
    
    echo "<h3>Step 3: Creating Address JSON</h3>";
    $billing_address = json_encode([
        'first_name' => $test_data['first_name'],
        'last_name' => $test_data['last_name'],
        'email' => $test_data['email'],
        'phone' => $test_data['phone'],
        'address' => $test_data['address'],
        'city' => $test_data['city'],
        'postal_code' => $test_data['postal_code'],
        'country' => $test_data['country']
    ]);
    echo "Billing address JSON created successfully.<br>";
    
    echo "<h3>Step 4: Testing Order Insert</h3>";
    $order_sql = "INSERT INTO orders (order_number, user_id, subtotal, tax_amount, shipping_amount, 
                 total_amount, currency, billing_address, shipping_address, notes, payment_method, status, payment_status, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', NOW())";
    
    $order_stmt = $pdo->prepare($order_sql);
    $success = $order_stmt->execute([
        $order_number,
        null, // user_id
        $cartTotals['subtotal'],
        $cartTotals['tax'],
        $cartTotals['shipping'],
        $cartTotals['total'],
        'LKR',
        $billing_address,
        $billing_address, // shipping same as billing
        $test_data['notes'],
        $test_data['payment_method']
    ]);
    
    if ($success) {
        $order_id = $pdo->lastInsertId();
        echo "Order inserted successfully! Order ID: $order_id<br>";
        
        echo "<h3>Step 5: Testing Order Items Insert</h3>";
        // Test order items
        $item_sql = "INSERT INTO order_items (order_id, product_id, product_name, product_sku, quantity, price, total) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $item_stmt = $pdo->prepare($item_sql);
        
        $test_item_success = $item_stmt->execute([
            $order_id,
            1, // product_id
            'Test Product',
            'TEST-001',
            2, // quantity
            500.00, // price
            1000.00 // total
        ]);
        
        if ($test_item_success) {
            echo "Order item inserted successfully!<br>";
            
            echo "<h3>Step 6: Committing Transaction</h3>";
            $pdo->commit();
            echo "Transaction committed successfully!<br>";
            
            echo "<h3>✅ Order Creation Test: PASSED</h3>";
            echo "<p style='color: green; font-weight: bold;'>The checkout process should work correctly now!</p>";
            
            // Show the created order
            echo "<h3>Created Order Details:</h3>";
            $order_details = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
            $order_details->execute([$order_id]);
            $order = $order_details->fetch();
            
            echo "<table border='1' style='border-collapse: collapse;'>";
            foreach ($order as $key => $value) {
                echo "<tr><td><strong>$key</strong></td><td>$value</td></tr>";
            }
            echo "</table>";
            
        } else {
            echo "❌ Order item insert failed!<br>";
            print_r($item_stmt->errorInfo());
            $pdo->rollBack();
        }
        
    } else {
        echo "❌ Order insert failed!<br>";
        print_r($order_stmt->errorInfo());
        $pdo->rollBack();
    }
    
} catch (Exception $e) {
    echo "<h3>❌ Test Failed with Exception:</h3>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
        echo "Transaction rolled back.<br>";
    }
}
?>