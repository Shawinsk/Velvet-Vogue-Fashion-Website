<?php
/**
 * Setup admin user for Velvet Vogue
 * This script creates the database tables and admin user
 */

require_once 'db_connect.php';

try {
    // Create database tables
    createDatabaseTables($pdo);
    echo "Database tables created successfully!\n";
    
    // Check if admin user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND is_admin = 1");
    $stmt->execute(['admin@velvetvogue.com']);
    $existing_admin = $stmt->fetch();
    
    if ($existing_admin) {
        // Update existing admin user
        $stmt = $pdo->prepare("
            UPDATE users 
            SET password = ?, email_verified = 1, is_admin = 1 
            WHERE email = ?
        ");
        $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt->execute([$password_hash, 'admin@velvetvogue.com']);
        echo "Admin user updated successfully!\n";
    } else {
        // Create new admin user
        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, password, is_admin, email_verified, created_at) 
            VALUES (?, ?, ?, ?, 1, 1, NOW())
        ");
        $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt->execute(['Admin', 'User', 'admin@velvetvogue.com', $password_hash]);
        echo "Admin user created successfully!\n";
    }
    
    echo "\n=== Admin Login Credentials ===\n";
    echo "Username: admin@velvetvogue.com\n";
    echo "Password: admin123\n";
    echo "==============================\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>