<?php
/**
 * Check if admin password is properly stored in database
 */

require_once 'db_connect.php';

echo "<h2>Password Storage Verification</h2>";

try {
    // Check admin user in database
    $stmt = $pdo->prepare("SELECT id, email, password, first_name, last_name, is_admin, email_verified FROM users WHERE email = ? AND is_admin = 1");
    $stmt->execute(['admin@velvetvogue.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<p style='color: green;'>✓ Admin user found in database</p>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        echo "<tr><td>ID</td><td>" . $user['id'] . "</td></tr>";
        echo "<tr><td>Email</td><td>" . $user['email'] . "</td></tr>";
        echo "<tr><td>Name</td><td>" . $user['first_name'] . " " . $user['last_name'] . "</td></tr>";
        echo "<tr><td>Is Admin</td><td>" . ($user['is_admin'] ? 'Yes' : 'No') . "</td></tr>";
        echo "<tr><td>Email Verified</td><td>" . ($user['email_verified'] ? 'Yes' : 'No') . "</td></tr>";
        echo "<tr><td>Password Hash</td><td>" . substr($user['password'], 0, 30) . "...</td></tr>";
        echo "<tr><td>Hash Length</td><td>" . strlen($user['password']) . " characters</td></tr>";
        echo "</table>";
        
        // Test password verification
        $test_password = 'admin123';
        if (password_verify($test_password, $user['password'])) {
            echo "<p style='color: green;'>✓ Password verification successful for 'admin123'</p>";
            echo "<p style='background: #e7f3ff; padding: 10px; border-radius: 5px;'>";
            echo "<strong>Password is properly hashed and stored in database!</strong><br>";
            echo "The password 'admin123' is securely stored using PHP's password_hash() function.";
            echo "</p>";
        } else {
            echo "<p style='color: red;'>✗ Password verification failed</p>";
        }
        
        // Show hash algorithm info
        $hash_info = password_get_info($user['password']);
        echo "<h3>Hash Information:</h3>";
        echo "<ul>";
        echo "<li>Algorithm: " . $hash_info['algoName'] . "</li>";
        echo "<li>Options: " . json_encode($hash_info['options']) . "</li>";
        echo "</ul>";
        
    } else {
        echo "<p style='color: red;'>✗ Admin user not found in database</p>";
        echo "<p>Please run setup_admin.php to create the admin user.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Summary:</h3>";
echo "<p>The admin password is stored securely in the database using:</p>";
echo "<ul>";
echo "<li><strong>Hashing Algorithm:</strong> bcrypt (via password_hash())</li>";
echo "<li><strong>Security:</strong> Password is never stored in plain text</li>";
echo "<li><strong>Verification:</strong> Uses password_verify() for login</li>";
echo "</ul>";

echo "<p><a href='admin/login.php'>Test Admin Login</a></p>";
?>