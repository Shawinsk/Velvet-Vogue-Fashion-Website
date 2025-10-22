<?php
/**
 * Check users table structure
 */

require_once 'db_connect.php';

try {
    // Check if users table exists and show its structure
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    
    echo "Users table structure:\n";
    echo "=====================\n";
    foreach ($columns as $column) {
        echo $column['Field'] . " - " . $column['Type'] . "\n";
    }
    
    // Check if is_admin column exists
    $has_is_admin = false;
    $has_email_verified = false;
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'is_admin') {
            $has_is_admin = true;
        }
        if ($column['Field'] === 'email_verified') {
            $has_email_verified = true;
        }
    }
    
    echo "\nColumn status:\n";
    echo "is_admin: " . ($has_is_admin ? "EXISTS" : "MISSING") . "\n";
    echo "email_verified: " . ($has_email_verified ? "EXISTS" : "MISSING") . "\n";
    
    // Add missing columns if needed
    if (!$has_is_admin) {
        echo "\nAdding is_admin column...\n";
        $pdo->exec("ALTER TABLE users ADD COLUMN is_admin BOOLEAN DEFAULT FALSE");
        echo "is_admin column added successfully!\n";
    }
    
    if (!$has_email_verified) {
        echo "\nAdding email_verified column...\n";
        $pdo->exec("ALTER TABLE users ADD COLUMN email_verified BOOLEAN DEFAULT FALSE");
        echo "email_verified column added successfully!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>