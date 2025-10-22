<?php
/**
 * Database Credentials Configuration Guide
 * This file explains how database connection and admin credentials work
 */

require_once 'db_connect.php';

echo "<h1>Database Credentials Configuration</h1>";

echo "<h2>1. Database Connection Credentials</h2>";
echo "<div style='background: #f0f8ff; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
echo "<h3>Current Database Connection Settings:</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Setting</th><th>Value</th><th>File Location</th></tr>";
echo "<tr><td>Host</td><td>localhost</td><td rowspan='4'>includes/db_connect.php</td></tr>";
echo "<tr><td>Database Name</td><td>velvet_vogue</td></tr>";
echo "<tr><td>Username</td><td>root</td></tr>";
echo "<tr><td>Password</td><td>(empty)</td></tr>";
echo "</table>";
echo "</div>";

echo "<h2>2. Admin User Credentials (Stored in Database)</h2>";

try {
    // Check admin user in database
    $stmt = $pdo->prepare("SELECT id, email, first_name, last_name, is_admin, email_verified, created_at FROM users WHERE is_admin = 1");
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($admins) {
        echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
        echo "<h3>Admin Users in Database:</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Email (Username)</th><th>Name</th><th>Status</th><th>Created</th></tr>";
        
        foreach ($admins as $admin) {
            echo "<tr>";
            echo "<td>" . $admin['id'] . "</td>";
            echo "<td><strong>" . $admin['email'] . "</strong></td>";
            echo "<td>" . $admin['first_name'] . " " . $admin['last_name'] . "</td>";
            echo "<td>" . ($admin['email_verified'] ? 'Active' : 'Pending') . "</td>";
            echo "<td>" . date('Y-m-d H:i', strtotime($admin['created_at'])) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
        
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
        echo "<h3>🔐 Current Admin Login Credentials:</h3>";
        echo "<p><strong>Username:</strong> admin@velvetvogue.com</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "<p><em>These credentials are stored securely in the database with password hashing.</em></p>";
        echo "</div>";
        
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
        echo "<p>❌ No admin users found in database!</p>";
        echo "<p><a href='setup_admin.php'>Click here to create admin user</a></p>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "<h2>3. Configuration Files</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
echo "<h3>Database Configuration Files:</h3>";
echo "<ul>";
echo "<li><strong>includes/db_connect.php</strong> - Simple database connection</li>";
echo "<li><strong>db_connect.php</strong> - Main database connection with table creation</li>";
echo "<li><strong>includes/config.php</strong> - Full configuration with site settings</li>";
echo "</ul>";
echo "<p><em>Note: All files currently use the same database credentials (root with empty password)</em></p>";
echo "</div>";

echo "<h2>4. How to Change Database Credentials</h2>";
echo "<div style='background: #e2e3e5; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
echo "<h3>Steps to Update Database Connection:</h3>";
echo "<ol>";
echo "<li>Create a new MySQL user in phpMyAdmin or MySQL command line</li>";
echo "<li>Grant necessary permissions to the new user</li>";
echo "<li>Update the following files with new credentials:</li>";
echo "<ul>";
echo "<li>includes/db_connect.php</li>";
echo "<li>db_connect.php</li>";
echo "<li>includes/config.php</li>";
echo "</ul>";
echo "<li>Test the connection</li>";
echo "</ol>";
echo "</div>";

echo "<h2>5. Security Recommendations</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
echo "<h3>🔒 Security Best Practices:</h3>";
echo "<ul>";
echo "<li><strong>Database User:</strong> Create a dedicated MySQL user instead of using 'root'</li>";
echo "<li><strong>Password:</strong> Use a strong password for database connection</li>";
echo "<li><strong>Permissions:</strong> Grant only necessary permissions to the database user</li>";
echo "<li><strong>Admin Password:</strong> Already using secure password hashing (bcrypt)</li>";
echo "<li><strong>Environment Variables:</strong> Consider using .env file for sensitive data</li>";
echo "</ul>";
echo "</div>";

echo "<h2>6. Quick Actions</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
echo "<p><a href='admin/login.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>Admin Login</a></p>";
echo "<p><a href='check_password_storage.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>Check Password Storage</a></p>";
echo "<p><a href='test_admin_login.php' style='background: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>Test Admin Login</a></p>";
echo "</div>";

echo "<hr>";
echo "<p><em>Generated on: " . date('Y-m-d H:i:s') . "</em></p>";
?>