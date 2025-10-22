<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate required fields
if (empty($_POST['product_id']) || empty($_POST['customer_name']) || 
    empty($_POST['customer_email']) || empty($_POST['rating']) || 
    empty($_POST['review_text'])) {
    echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
    exit;
}

$product_id = (int)$_POST['product_id'];
$customer_name = trim($_POST['customer_name']);
$customer_email = trim($_POST['customer_email']);
$rating = (int)$_POST['rating'];
$review_title = trim($_POST['review_title'] ?? '');
$review_text = trim($_POST['review_text']);

// Validate rating
if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5']);
    exit;
}

// Validate email
if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Check if product exists
$product_check = $pdo->prepare("SELECT id FROM products WHERE id = ?");
$product_check->execute([$product_id]);
if (!$product_check->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Check if customer has already reviewed this product
$existing_review = $pdo->prepare("
    SELECT id FROM product_reviews 
    WHERE product_id = ? AND customer_email = ?
");
$existing_review->execute([$product_id, $customer_email]);
if ($existing_review->fetch()) {
    echo json_encode(['success' => false, 'message' => 'You have already reviewed this product']);
    exit;
}

try {
    // Insert the review
    $stmt = $pdo->prepare("
        INSERT INTO product_reviews 
        (product_id, customer_name, customer_email, rating, review_title, review_text, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $result = $stmt->execute([
        $product_id,
        $customer_name,
        $customer_email,
        $rating,
        $review_title,
        $review_text
    ]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit review']);
    }
    
} catch (PDOException $e) {
    error_log('Review submission error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>