<?php
/**
 * Update existing admin user credentials
 */

require_once 'db_connect.php';

try {
    // Update existing admin user
    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("
        UPDATE users 
        SET password = ?, is_admin = 1, email_verified = 1, first_name = 'Admin', last_name = 'User'
        WHERE email = 'admin@velvetvogue.com'
    ");
    
    $result = $stmt->execute([$password_hash]);
    
    if ($result && $stmt->rowCount() > 0) {
        echo "Admin user updated successfully!\n";
    } else {
        echo "No admin user found to update. Creating new one...\n";
        
        // Try to create new admin user
        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, password, is_admin, email_verified, created_at) 
            VALUES ('Admin', 'User', 'admin@velvetvogue.com', ?, 1, 1, NOW())
        ");
        
        $stmt->execute([$password_hash]);
        echo "New admin user created successfully!\n";
    }
    
    // Verify the admin user
    $stmt = $pdo->prepare("SELECT email, first_name, last_name, is_admin, email_verified FROM users WHERE email = 'admin@velvetvogue.com'");
    $stmt->execute();
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "\n=== Admin User Details ===\n";
        echo "Email: " . $admin['email'] . "\n";
        echo "Name: " . $admin['first_name'] . " " . $admin['last_name'] . "\n";
        echo "Is Admin: " . ($admin['is_admin'] ? 'Yes' : 'No') . "\n";
        echo "Email Verified: " . ($admin['email_verified'] ? 'Yes' : 'No') . "\n";
        echo "\n=== Login Credentials ===\n";
        echo "Username: admin@velvetvogue.com\n";
        echo "Password: admin123\n";
        echo "========================\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>