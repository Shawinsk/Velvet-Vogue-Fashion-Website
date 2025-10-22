<?php
require 'db_connect.php';

try {
    // Activate all categories
    $stmt = $pdo->prepare('UPDATE categories SET is_active = 1');
    $stmt->execute();
    echo "Categories activated: " . $stmt->rowCount() . "\n";
    
    // Verify activation
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM categories WHERE is_active = 1');
    $result = $stmt->fetch();
    echo "Active categories now: " . $result['count'] . "\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>