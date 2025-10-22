<?php
/**
 * Test script to verify admin login functionality
 */

require_once 'includes/db_connect.php';
require_once 'admin/includes/admin_auth.php';

echo "<h2>Admin Login Test</h2>";

// Test 1: Check if admin user exists
try {
    $stmt = $pdo->prepare("SELECT id, email, first_name, last_name, is_admin FROM users WHERE email = ? AND is_admin = 1");
    $stmt->execute(['admin@velvetvogue.com']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "<p style='color: green;'>✓ Admin user exists in database</p>";
        echo "<p>Admin Details:</p>";
        echo "<ul>";
        echo "<li>ID: " . $admin['id'] . "</li>";
        echo "<li>Email: " . $admin['email'] . "</li>";
        echo "<li>Name: " . $admin['first_name'] . " " . $admin['last_name'] . "</li>";
        echo "<li>Is Admin: " . ($admin['is_admin'] ? 'Yes' : 'No') . "</li>";
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>✗ Admin user not found in database</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database error: " . $e->getMessage() . "</p>";
}

// Test 2: Test password verification
if ($admin) {
    $test_password = 'admin123';
    $stmt = $pdo->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->execute(['admin@velvetvogue.com']);
    $stored_password = $stmt->fetchColumn();
    
    if (password_verify($test_password, $stored_password)) {
        echo "<p style='color: green;'>✓ Password verification works correctly</p>";
    } else {
        echo "<p style='color: red;'>✗ Password verification failed</p>";
    }
}

// Test 3: Test authentication function
echo "<h3>Testing Authentication Function</h3>";
$auth_result = authenticateAdmin($pdo, 'admin@velvetvogue.com', 'admin123');
if ($auth_result['success']) {
    echo "<p style='color: green;'>✓ Authentication function works correctly</p>";
    echo "<p>Message: " . $auth_result['message'] . "</p>";
} else {
    echo "<p style='color: red;'>✗ Authentication function failed</p>";
    echo "<p>Error: " . $auth_result['message'] . "</p>";
}

echo "<hr>";
echo "<h3>Login Credentials</h3>";
echo "<p><strong>Username:</strong> admin@velvetvogue.com</p>";
echo "<p><strong>Password:</strong> admin123</p>";
echo "<p><a href='admin/login.php'>Go to Admin Login</a></p>";
?>