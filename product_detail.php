<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header('Location: products.php');
    exit;
}

// Get product details
$query = "
    SELECT 
        p.id,
        p.name,
        p.description,
        p.price,
        p.sale_price,
        p.sku,
        p.stock_quantity,
        p.is_featured,
        p.created_at,
        c.name as category_name,
        c.slug as category_slug
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.id = ?
";

$stmt = $pdo->prepare($query);
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit;
}

// Get product images
$images_query = "SELECT image_path, alt_text, is_primary FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, sort_order";
$images_stmt = $pdo->prepare($images_query);
$images_stmt->execute([$product_id]);
$product_images = $images_stmt->fetchAll();

// Get related products
$related_query = "
    SELECT 
        p.id,
        p.name,
        p.price,
        p.sale_price,
        pi.image_path,
        pi.alt_text
    FROM products p
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
    WHERE p.category_id = (SELECT category_id FROM products WHERE id = ?) 
    AND p.id != ?
    ORDER BY RAND()
    LIMIT 4
";
$related_stmt = $pdo->prepare($related_query);
$related_stmt->execute([$product_id, $product_id]);
$related_products = $related_stmt->fetchAll();

// Get product reviews (create table if not exists)
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        customer_name VARCHAR(255) NOT NULL,
        customer_email VARCHAR(255) NOT NULL,
        rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        review_title VARCHAR(255),
        review_text TEXT,
        is_verified BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )");
} catch (PDOException $e) {
    // Table might already exist
}

// Get reviews for this product
$reviews_query = "SELECT * FROM product_reviews WHERE product_id = ? ORDER BY created_at DESC";
$reviews_stmt = $pdo->prepare($reviews_query);
$reviews_stmt->execute([$product_id]);
$reviews = $reviews_stmt->fetchAll();

// Calculate average rating
$avg_rating = 0;
$total_reviews = count($reviews);
if ($total_reviews > 0) {
    $total_rating = array_sum(array_column($reviews, 'rating'));
    $avg_rating = round($total_rating / $total_reviews, 1);
}

// Calculate discount percentage
$discount_percentage = 0;
if ($product['sale_price'] && $product['sale_price'] < $product['price']) {
    $discount_percentage = round((($product['price'] - $product['sale_price']) / $product['price']) * 100);
}

$final_price = $product['sale_price'] ?: $product['price'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Velvet Vogue</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/products.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .product-detail-page {
            padding-top: 80px;
        }
        
        .product-detail {
            padding: 3rem 0;
            background: #f8f9fa;
        }
        
        .product-detail-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .product-gallery {
            position: sticky;
            top: 100px;
            height: fit-content;
        }
        
        .main-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .thumbnail-gallery {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
        }
        
        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        
        .thumbnail.active,
        .thumbnail:hover {
            opacity: 1;
        }
        
        .product-info {
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .breadcrumb {
            margin-bottom: 2rem;
            font-size: 0.9rem;
            color: #666;
        }
        
        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .product-title {
            font-size: 2.5rem;
            font-family: var(--font-primary);
            margin-bottom: 1rem;
            color: #333;
        }
        
        .product-price {
            margin-bottom: 2rem;
        }
        
        .current-price {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .original-price {
            font-size: 1.5rem;
            color: #999;
            text-decoration: line-through;
            margin-left: 1rem;
        }
        
        .discount-badge {
            background: #e74c3c;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-left: 1rem;
        }
        
        .product-description {
            line-height: 1.6;
            margin-bottom: 2rem;
            color: #666;
        }
        
        /* Size and Color Selection Styles */
        .product-options {
            margin-bottom: 2rem;
        }
        
        .option-group {
            margin-bottom: 1.5rem;
        }
        
        .option-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #333;
        }
        
        .size-options {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .size-option {
            padding: 0.75rem 1rem;
            border: 2px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s ease;
            min-width: 50px;
            text-align: center;
        }
        
        .size-option:hover {
            border-color: var(--primary-color);
        }
        
        .size-option.selected {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }
        
        .color-options {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        
        .color-option {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .color-option:hover {
            transform: scale(1.1);
        }
        
        .color-option.selected {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.3);
        }
        
        .color-option::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .color-option.selected::after {
            opacity: 1;
        }
        
        .color-name {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.5rem;
            text-align: center;
        }
        
        .product-meta {
            margin-bottom: 2rem;
        }
        
        .meta-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .quantity-input {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .quantity-btn {
            background: none;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
            font-size: 1.2rem;
        }
        
        .quantity-value {
            padding: 0.5rem 1rem;
            border: none;
            text-align: center;
            width: 60px;
        }
        
        .add-to-cart-section {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .add-to-cart-btn {
            flex: 1;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 4px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .add-to-cart-btn:hover {
            background: var(--accent-color);
        }
        
        .wishlist-btn {
            background: none;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .wishlist-btn:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .related-products {
            padding: 3rem 0;
            background: white;
        }
        
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .related-product {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .related-product:hover {
            transform: translateY(-5px);
        }
        
        .related-product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .related-product-info {
            padding: 1rem;
        }
        
        .related-product-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .related-product-price {
            color: var(--primary-color);
            font-weight: 700;
        }
        
        /* Product Details Tabs */
        .product-tabs {
            margin-top: 3rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .tab-navigation {
            display: flex;
            border-bottom: 1px solid #eee;
        }
        
        .tab-btn {
            background: none;
            border: none;
            padding: 1rem 2rem;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .tab-btn.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }
        
        .tab-content {
            padding: 2rem;
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Reviews Section */
        .reviews-summary {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #eee;
        }
        
        .rating-overview {
            text-align: center;
        }
        
        .avg-rating {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .rating-stars {
            font-size: 1.5rem;
            color: #ffd700;
            margin-bottom: 0.5rem;
        }
        
        .total-reviews {
            color: #666;
            font-size: 0.9rem;
        }
        
        .rating-breakdown {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .rating-bar {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .rating-bar-fill {
            flex: 1;
            height: 8px;
            background: #f0f0f0;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .rating-bar-progress {
            height: 100%;
            background: var(--primary-color);
            transition: width 0.3s ease;
        }
        
        .review-item {
            padding: 1.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .reviewer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .review-rating {
            color: #ffd700;
        }
        
        .review-date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .review-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .review-text {
            line-height: 1.6;
            color: #666;
        }
        
        /* Review Form */
        .review-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            margin-top: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .form-group textarea {
            height: 120px;
            resize: vertical;
        }
        
        .rating-input {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .star-input {
            font-size: 1.5rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s ease;
        }
        
        .star-input:hover,
        .star-input.active {
            color: #ffd700;
        }
        
        .submit-review-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }
        
        .submit-review-btn:hover {
            background: var(--accent-color);
        }
        
        /* Enhanced Gallery */
        .gallery-container {
            position: relative;
        }
        
        .main-image {
            cursor: zoom-in;
        }
        
        .image-zoom-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .zoomed-image {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }
        
        .close-zoom {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .product-detail-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .product-gallery {
                position: static;
            }
            
            .product-title {
                font-size: 2rem;
            }
            
            .add-to-cart-section {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="product-detail-page">
        <section class="product-detail">
            <div class="product-detail-container">
                <!-- Product Gallery -->
                <div class="product-gallery">
                    <?php if (!empty($product_images)): ?>
                        <img src="<?php echo htmlspecialchars($product_images[0]['image_path'] ?: 'assets/images/placeholder.jpg'); ?>" 
                             alt="<?php echo htmlspecialchars($product_images[0]['alt_text'] ?: $product['name']); ?>" 
                             class="main-image" id="mainImage">
                        
                        <?php if (count($product_images) > 1): ?>
                            <div class="thumbnail-gallery">
                                <?php foreach ($product_images as $index => $image): ?>
                                    <img src="<?php echo htmlspecialchars($image['image_path'] ?: 'assets/images/placeholder.jpg'); ?>" 
                                         alt="<?php echo htmlspecialchars($image['alt_text'] ?: $product['name']); ?>" 
                                         class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" 
                                         onclick="changeMainImage(this)">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <img src="assets/images/placeholder.jpg" alt="<?php echo htmlspecialchars($product['name']); ?>" class="main-image">
                    <?php endif; ?>
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <nav class="breadcrumb">
                        <a href="index.php"><i class="fas fa-home"></i> Home</a>
                        <span class="separator"> / </span>
                        <a href="products.php">Products</a>
                        <span class="separator"> / </span>
                        <?php if ($product['category_name']): ?>
                            <a href="products.php?category=<?php echo urlencode($product['category_slug']); ?>"><?php echo htmlspecialchars($product['category_name']); ?></a>
                            <span class="separator"> / </span>
                        <?php endif; ?>
                        <span class="current"><?php echo htmlspecialchars($product['name']); ?></span>
                    </nav>

                    <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                    <!-- Product Rating -->
                    <?php if ($total_reviews > 0): ?>
                        <div class="product-rating" style="margin-bottom: 1rem;">
                            <div class="rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star" style="color: <?php echo $i <= $avg_rating ? '#ffd700' : '#ddd'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="rating-text"><?php echo $avg_rating; ?>/5 (<?php echo $total_reviews; ?> reviews)</span>
                        </div>
                    <?php endif; ?>

                    <div class="product-price">
                        <span class="current-price">LKR <?php echo number_format($final_price, 2); ?></span>
                        <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                            <span class="original-price">LKR <?php echo number_format($product['price'], 2); ?></span>
                            <span class="discount-badge"><?php echo $discount_percentage; ?>% OFF</span>
                        <?php endif; ?>
                    </div>

                    <div class="product-description">
                        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    </div>

                    <!-- Size and Color Selection -->
                    <div class="product-options">
                        <!-- Size Selection -->
                        <div class="option-group">
                            <label class="option-label">Size:</label>
                            <div class="size-options">
                                <div class="size-option" data-size="XS" onclick="selectSize(this)">XS</div>
                                <div class="size-option" data-size="S" onclick="selectSize(this)">S</div>
                                <div class="size-option selected" data-size="M" onclick="selectSize(this)">M</div>
                                <div class="size-option" data-size="L" onclick="selectSize(this)">L</div>
                                <div class="size-option" data-size="XL" onclick="selectSize(this)">XL</div>
                                <div class="size-option" data-size="XXL" onclick="selectSize(this)">XXL</div>
                            </div>
                        </div>

                        <!-- Color Selection -->
                        <div class="option-group">
                            <label class="option-label">Color:</label>
                            <div class="color-options">
                                <div class="color-option selected" data-color="black" style="background-color: #000000;" onclick="selectColor(this)" title="Black"></div>
                                <div class="color-option" data-color="white" style="background-color: #ffffff; border-color: #ccc;" onclick="selectColor(this)" title="White"></div>
                                <div class="color-option" data-color="navy" style="background-color: #1e3a8a;" onclick="selectColor(this)" title="Navy Blue"></div>
                                <div class="color-option" data-color="red" style="background-color: #dc2626;" onclick="selectColor(this)" title="Red"></div>
                                <div class="color-option" data-color="green" style="background-color: #16a34a;" onclick="selectColor(this)" title="Green"></div>
                                <div class="color-option" data-color="brown" style="background-color: #a16207;" onclick="selectColor(this)" title="Brown"></div>
                                <div class="color-option" data-color="gray" style="background-color: #6b7280;" onclick="selectColor(this)" title="Gray"></div>
                                <div class="color-option" data-color="pink" style="background-color: #ec4899;" onclick="selectColor(this)" title="Pink"></div>
                            </div>
                        </div>
                    </div>

                    <div class="product-meta">
                        <div class="meta-item">
                            <span>Category:</span>
                            <span><?php echo htmlspecialchars($product['category_name'] ?: 'Uncategorized'); ?></span>
                        </div>
                        <div class="meta-item">
                            <span>Stock:</span>
                            <span class="<?php echo $product['stock_quantity'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                                <?php echo $product['stock_quantity'] > 0 ? 'In Stock (' . $product['stock_quantity'] . ')' : 'Out of Stock'; ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($product['stock_quantity'] > 0): ?>
                        <div class="quantity-selector">
                            <label for="quantity">Quantity:</label>
                            <div class="quantity-input">
                                <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">-</button>
                                <input type="number" id="quantity" class="quantity-value" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                                <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
                            </div>
                        </div>

                        <div class="add-to-cart-section">
                            <button class="add-to-cart-btn" onclick="addToCart(<?php echo $product['id']; ?>)">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                            <button class="wishlist-btn" onclick="addToWishlist(<?php echo $product['id']; ?>)">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="out-of-stock-notice">
                            <p style="color: #e74c3c; font-weight: 600;">This product is currently out of stock.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Product Details Tabs -->
        <section class="product-tabs">
            <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                <div class="tab-navigation">
                    <button class="tab-btn active" onclick="openTab(event, 'description')">Description</button>
                    <button class="tab-btn" onclick="openTab(event, 'specifications')">Specifications</button>
                    <button class="tab-btn" onclick="openTab(event, 'reviews')">Reviews (<?php echo $total_reviews; ?>)</button>
                </div>

                <!-- Description Tab -->
                <div id="description" class="tab-content active">
                    <h3>Product Description</h3>
                    <div class="detailed-description">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                        
                        <h4 style="margin-top: 2rem; margin-bottom: 1rem;">Features & Benefits</h4>
                        <ul style="line-height: 1.8; color: #666;">
                            <li>Premium quality materials for lasting durability</li>
                            <li>Comfortable fit designed for everyday wear</li>
                            <li>Easy care and maintenance instructions</li>
                            <li>Available in multiple sizes and colors</li>
                            <li>Perfect for both casual and formal occasions</li>
                        </ul>
                        
                        <h4 style="margin-top: 2rem; margin-bottom: 1rem;">Care Instructions</h4>
                        <ul style="line-height: 1.8; color: #666;">
                            <li>Machine wash cold with like colors</li>
                            <li>Do not bleach or use harsh chemicals</li>
                            <li>Tumble dry low heat or hang to dry</li>
                            <li>Iron on low heat if needed</li>
                            <li>Store in a cool, dry place</li>
                        </ul>
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div id="specifications" class="tab-content">
                    <h3>Product Specifications</h3>
                    <div class="specifications-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                        <div class="spec-section">
                            <h4>General Information</h4>
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 0.5rem 0; font-weight: 500;">Category:</td>
                                    <td style="padding: 0.5rem 0;"><?php echo htmlspecialchars($product['category_name'] ?: 'Uncategorized'); ?></td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 0.5rem 0; font-weight: 500;">Stock Status:</td>
                                    <td style="padding: 0.5rem 0;"><?php echo $product['stock_quantity'] > 0 ? 'In Stock (' . $product['stock_quantity'] . ')' : 'Out of Stock'; ?></td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 0.5rem 0; font-weight: 500;">Date Added:</td>
                                    <td style="padding: 0.5rem 0;"><?php echo date('F j, Y', strtotime($product['created_at'])); ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="spec-section">
                            <h4>Product Details</h4>
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 0.5rem 0; font-weight: 500;">Material:</td>
                                    <td style="padding: 0.5rem 0;">Premium Cotton Blend</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 0.5rem 0; font-weight: 500;">Fit:</td>
                                    <td style="padding: 0.5rem 0;">Regular Fit</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 0.5rem 0; font-weight: 500;">Season:</td>
                                    <td style="padding: 0.5rem 0;">All Season</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 0.5rem 0; font-weight: 500;">Origin:</td>
                                    <td style="padding: 0.5rem 0;">Sri Lanka</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div id="reviews" class="tab-content">
                    <h3>Customer Reviews</h3>
                    
                    <?php if ($total_reviews > 0): ?>
                        <!-- Reviews Summary -->
                        <div class="reviews-summary">
                            <div class="rating-overview">
                                <div class="avg-rating"><?php echo $avg_rating; ?></div>
                                <div class="rating-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star" style="color: <?php echo $i <= $avg_rating ? '#ffd700' : '#ddd'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <div class="total-reviews"><?php echo $total_reviews; ?> review<?php echo $total_reviews > 1 ? 's' : ''; ?></div>
                            </div>
                            
                            <div class="rating-breakdown">
                                <?php 
                                $rating_counts = array_count_values(array_column($reviews, 'rating'));
                                for ($i = 5; $i >= 1; $i--): 
                                    $count = isset($rating_counts[$i]) ? $rating_counts[$i] : 0;
                                    $percentage = $total_reviews > 0 ? ($count / $total_reviews) * 100 : 0;
                                ?>
                                    <div class="rating-bar">
                                        <span><?php echo $i; ?> star</span>
                                        <div class="rating-bar-fill">
                                            <div class="rating-bar-progress" style="width: <?php echo $percentage; ?>%"></div>
                                        </div>
                                        <span><?php echo $count; ?></span>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <!-- Individual Reviews -->
                        <div class="reviews-list">
                            <?php foreach ($reviews as $review): ?>
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <div class="reviewer-avatar">
                                                <?php echo strtoupper(substr($review['customer_name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="reviewer-name"><?php echo htmlspecialchars($review['customer_name']); ?></div>
                                                <div class="review-rating">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star" style="color: <?php echo $i <= $review['rating'] ? '#ffd700' : '#ddd'; ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="review-date"><?php echo date('M j, Y', strtotime($review['created_at'])); ?></div>
                                    </div>
                                    
                                    <?php if ($review['review_title']): ?>
                                        <div class="review-title"><?php echo htmlspecialchars($review['review_title']); ?></div>
                                    <?php endif; ?>
                                    
                                    <div class="review-text"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 3rem; color: #666;">
                            <i class="fas fa-star" style="font-size: 3rem; margin-bottom: 1rem; color: #ddd;"></i>
                            <h4>No reviews yet</h4>
                            <p>Be the first to review this product!</p>
                        </div>
                    <?php endif; ?>

                    <!-- Review Form -->
                    <div class="review-form">
                        <h4>Write a Review</h4>
                        <form id="reviewForm" onsubmit="submitReview(event)">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            
                            <div class="form-group">
                                <label for="customer_name">Your Name *</label>
                                <input type="text" id="customer_name" name="customer_name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="customer_email">Your Email *</label>
                                <input type="email" id="customer_email" name="customer_email" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Rating *</label>
                                <div class="rating-input">
                                    <span class="star-input" data-rating="1">★</span>
                                    <span class="star-input" data-rating="2">★</span>
                                    <span class="star-input" data-rating="3">★</span>
                                    <span class="star-input" data-rating="4">★</span>
                                    <span class="star-input" data-rating="5">★</span>
                                </div>
                                <input type="hidden" id="rating" name="rating" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="review_title">Review Title</label>
                                <input type="text" id="review_title" name="review_title" placeholder="Give your review a title">
                            </div>
                            
                            <div class="form-group">
                                <label for="review_text">Your Review *</label>
                                <textarea id="review_text" name="review_text" placeholder="Tell others what you think about this product..." required></textarea>
                            </div>
                            
                            <button type="submit" class="submit-review-btn">Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Products -->
        <?php if (!empty($related_products)): ?>
            <section class="related-products">
                <div class="container">
                    <h2 style="text-align: center; margin-bottom: 2rem; font-family: var(--font-primary);">Related Products</h2>
                    <div class="related-grid">
                        <?php foreach ($related_products as $related): ?>
                            <div class="related-product">
                                <a href="product_detail.php?id=<?php echo $related['id']; ?>">
                                    <img src="<?php echo htmlspecialchars($related['image_path'] ?: 'assets/images/placeholder.jpg'); ?>" 
                                         alt="<?php echo htmlspecialchars($related['alt_text'] ?: $related['name']); ?>">
                                    <div class="related-product-info">
                                        <div class="related-product-name"><?php echo htmlspecialchars($related['name']); ?></div>
                                        <div class="related-product-price">
                                            LKR <?php echo number_format($related['sale_price'] ?: $related['price'], 2); ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        function changeMainImage(thumbnail) {
            const mainImage = document.getElementById('mainImage');
            const thumbnails = document.querySelectorAll('.thumbnail');
            
            mainImage.src = thumbnail.src;
            mainImage.alt = thumbnail.alt;
            
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            thumbnail.classList.add('active');
        }

        function changeQuantity(change) {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            const newValue = currentValue + change;
            const maxValue = parseInt(quantityInput.max);
            
            if (newValue >= 1 && newValue <= maxValue) {
                quantityInput.value = newValue;
            }
        }

        function addToCart(productId) {
            const quantity = document.getElementById('quantity').value;
            const selectedOptions = getSelectedOptions();
            
            // Check if size and color are selected
            if (!selectedOptions.size) {
                alert('Please select a size before adding to cart.');
                return;
            }
            
            if (!selectedOptions.color) {
                alert('Please select a color before adding to cart.');
                return;
            }
            
            fetch('cart_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=add&product_id=${productId}&quantity=${quantity}&size=${selectedOptions.size}&color=${selectedOptions.color}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product added to cart successfully!');
                    // Redirect to cart.php after successful addition
                    setTimeout(() => {
                        window.location.href = 'cart.php';
                    }, 1500);
                } else {
                    // Check if redirect is needed (user not logged in)
                    if (data.redirect) {
                        alert(data.message);
                        // Redirect to login page
                        window.location.href = data.redirect;
                    } else {
                        alert('Error adding product to cart: ' + data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding product to cart');
            });
        }

        function addToWishlist(productId) {
            // Implement wishlist functionality
            alert('Wishlist functionality coming soon!');
        }

        // Tab functionality
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            
            // Hide all tab content
            tabcontent = document.getElementsByClassName('tab-content');
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove('active');
            }
            
            // Remove active class from all tab buttons
            tablinks = document.getElementsByClassName('tab-btn');
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove('active');
            }
            
            // Show the selected tab content and mark button as active
            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }

        // Star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            const starInputs = document.querySelectorAll('.star-input');
            const ratingInput = document.getElementById('rating');
            
            starInputs.forEach((star, index) => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-rating');
                    ratingInput.value = rating;
                    
                    // Update visual feedback
                    starInputs.forEach((s, i) => {
                        if (i < rating) {
                            s.style.color = '#ffd700';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                });
                
                star.addEventListener('mouseover', function() {
                    const rating = this.getAttribute('data-rating');
                    starInputs.forEach((s, i) => {
                        if (i < rating) {
                            s.style.color = '#ffd700';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                });
            });
            
            // Reset stars on mouse leave
            document.querySelector('.rating-input').addEventListener('mouseleave', function() {
                const currentRating = ratingInput.value;
                starInputs.forEach((s, i) => {
                    if (i < currentRating) {
                        s.style.color = '#ffd700';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });
        });

        // Submit review functionality
        function submitReview(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            
            fetch('submit_review.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Review submitted successfully!');
                    location.reload(); // Reload to show new review
                } else {
                    alert('Error submitting review: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting review');
            });
        }

        // Size selection functionality
        function selectSize(element) {
            // Remove selected class from all size options
            const sizeOptions = document.querySelectorAll('.size-option');
            sizeOptions.forEach(option => option.classList.remove('selected'));
            
            // Add selected class to clicked option
            element.classList.add('selected');
            
            // Store selected size (you can use this for cart functionality)
            const selectedSize = element.getAttribute('data-size');
            console.log('Selected size:', selectedSize);
        }

        // Color selection functionality
        function selectColor(element) {
            // Remove selected class from all color options
            const colorOptions = document.querySelectorAll('.color-option');
            colorOptions.forEach(option => option.classList.remove('selected'));
            
            // Add selected class to clicked option
            element.classList.add('selected');
            
            // Store selected color (you can use this for cart functionality)
            const selectedColor = element.getAttribute('data-color');
            console.log('Selected color:', selectedColor);
        }

        // Get selected options function (for cart functionality)
        function getSelectedOptions() {
            const selectedSize = document.querySelector('.size-option.selected')?.getAttribute('data-size');
            const selectedColor = document.querySelector('.color-option.selected')?.getAttribute('data-color');
            
            return {
                size: selectedSize,
                color: selectedColor
            };
        }
    </script>
</body>
</html>