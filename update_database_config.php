<?php
/**
 * Database Configuration Update Script
 * This script allows you to update database credentials across all configuration files
 */

// Check if form is submitted
if ($_POST && isset($_POST['update_config'])) {
    $new_host = $_POST['host'] ?? 'localhost';
    $new_dbname = $_POST['dbname'] ?? 'velvet_vogue';
    $new_username = $_POST['username'] ?? 'root';
    $new_password = $_POST['password'] ?? '';
    
    $success = true;
    $messages = [];
    
    // Update includes/db_connect.php
    $db_connect_content = "<?php\n";
    $db_connect_content .= "\$host = '$new_host';\n";
    $db_connect_content .= "\$dbname = '$new_dbname';\n";
    $db_connect_content .= "\$username = '$new_username';\n";
    $db_connect_content .= "\$password = '$new_password';\n\n";
    $db_connect_content .= "try {\n";
    $db_connect_content .= "    \$pdo = new PDO(\"mysql:host=\$host;dbname=\$dbname;charset=utf8mb4\", \$username, \$password);\n";
    $db_connect_content .= "    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n";
    $db_connect_content .= "    \$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);\n";
    $db_connect_content .= "} catch(PDOException \$e) {\n";
    $db_connect_content .= "    die(\"Connection failed: \" . \$e->getMessage());\n";
    $db_connect_content .= "}\n?>";
    
    if (file_put_contents('includes/db_connect.php', $db_connect_content)) {
        $messages[] = "✅ Updated includes/db_connect.php";
    } else {
        $messages[] = "❌ Failed to update includes/db_connect.php";
        $success = false;
    }
    
    // Update db_connect.php (main file)
    $main_db_content = file_get_contents('db_connect.php');
    if ($main_db_content) {
        // Replace the db_config array
        $pattern = '/\$db_config\s*=\s*\[[^\]]+\];/s';
        $replacement = "\$db_config = [\n";
        $replacement .= "    'host' => '$new_host',\n";
        $replacement .= "    'dbname' => '$new_dbname',\n";
        $replacement .= "    'username' => '$new_username',\n";
        $replacement .= "    'password' => '$new_password',\n";
        $replacement .= "    'charset' => 'utf8mb4',\n";
        $replacement .= "    'options' => [\n";
        $replacement .= "        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,\n";
        $replacement .= "        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,\n";
        $replacement .= "        PDO::ATTR_EMULATE_PREPARES => false,\n";
        $replacement .= "    ]\n";
        $replacement .= "];";
        
        $updated_content = preg_replace($pattern, $replacement, $main_db_content);
        
        if (file_put_contents('db_connect.php', $updated_content)) {
            $messages[] = "✅ Updated db_connect.php";
        } else {
            $messages[] = "❌ Failed to update db_connect.php";
            $success = false;
        }
    }
    
    // Update includes/config.php
    $config_content = file_get_contents('includes/config.php');
    if ($config_content) {
        // Replace the db_config array in config.php
        $pattern = '/\$db_config\s*=\s*\[[^\]]+\];/s';
        $replacement = "\$db_config = [\n";
        $replacement .= "    'host' => '$new_host',\n";
        $replacement .= "    'dbname' => '$new_dbname',\n";
        $replacement .= "    'username' => '$new_username',\n";
        $replacement .= "    'password' => '$new_password',\n";
        $replacement .= "    'charset' => 'utf8mb4',\n";
        $replacement .= "    'options' => [\n";
        $replacement .= "        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,\n";
        $replacement .= "        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,\n";
        $replacement .= "        PDO::ATTR_EMULATE_PREPARES => false,\n";
        $replacement .= "    ]\n";
        $replacement .= "];";
        
        $updated_content = preg_replace($pattern, $replacement, $config_content);
        
        if (file_put_contents('includes/config.php', $updated_content)) {
            $messages[] = "✅ Updated includes/config.php";
        } else {
            $messages[] = "❌ Failed to update includes/config.php";
            $success = false;
        }
    }
    
    // Test the new connection
    try {
        $test_pdo = new PDO("mysql:host=$new_host;dbname=$new_dbname;charset=utf8mb4", $new_username, $new_password);
        $messages[] = "✅ Database connection test successful!";
    } catch (Exception $e) {
        $messages[] = "❌ Database connection test failed: " . $e->getMessage();
        $success = false;
    }
}

// Get current configuration
try {
    include 'includes/db_connect.php';
    $current_host = $host ?? 'localhost';
    $current_dbname = $dbname ?? 'velvet_vogue';
    $current_username = $username ?? 'root';
    $current_password = $password ?? '';
} catch (Exception $e) {
    $current_host = 'localhost';
    $current_dbname = 'velvet_vogue';
    $current_username = 'root';
    $current_password = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Configuration Update</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .container { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .message { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .current-config { background: #e7f3ff; padding: 15px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>
    <h1>🔧 Database Configuration Update</h1>
    
    <?php if (isset($messages) && !empty($messages)): ?>
        <div class="container">
            <h3>Update Results:</h3>
            <?php foreach ($messages as $message): ?>
                <div class="message <?php echo (strpos($message, '✅') !== false) ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="current-config">
        <h3>📋 Current Database Configuration:</h3>
        <p><strong>Host:</strong> <?php echo htmlspecialchars($current_host); ?></p>
        <p><strong>Database:</strong> <?php echo htmlspecialchars($current_dbname); ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($current_username); ?></p>
        <p><strong>Password:</strong> <?php echo empty($current_password) ? '(empty)' : '(set)'; ?></p>
    </div>
    
    <div class="container">
        <h3>🔄 Update Database Credentials</h3>
        <div class="warning message">
            <strong>⚠️ Warning:</strong> This will update database credentials in all configuration files. Make sure the new credentials are correct!
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="host">Database Host:</label>
                <input type="text" id="host" name="host" value="<?php echo htmlspecialchars($current_host); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="dbname">Database Name:</label>
                <input type="text" id="dbname" name="dbname" value="<?php echo htmlspecialchars($current_dbname); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="username">Database Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($current_username); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Database Password:</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($current_password); ?>" placeholder="Enter new password (leave empty for no password)">
            </div>
            
            <button type="submit" name="update_config" onclick="return confirm('Are you sure you want to update the database configuration?')">Update Configuration</button>
        </form>
    </div>
    
    <div class="container">
        <h3>📁 Files that will be updated:</h3>
        <ul>
            <li><code>includes/db_connect.php</code></li>
            <li><code>db_connect.php</code></li>
            <li><code>includes/config.php</code></li>
        </ul>
    </div>
    
    <div class="container">
        <h3>🔗 Quick Links:</h3>
        <p><a href="database_credentials_guide.php">📖 Database Credentials Guide</a></p>
        <p><a href="admin/login.php">🔐 Admin Login</a></p>
        <p><a href="check_password_storage.php">🔍 Check Password Storage</a></p>
    </div>
</body>
</html>