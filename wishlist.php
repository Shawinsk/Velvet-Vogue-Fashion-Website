<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: account_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle remove from wishlist
if (isset($_POST['remove_from_wishlist'])) {
    $product_id = $_POST['product_id'];
    $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    header('Location: wishlist.php');
    exit();
}

// Get wishlist items
$stmt = $pdo->prepare("
    SELECT w.*, p.name, p.price, p.sale_price, p.description,
           (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image_path
    FROM wishlist w 
    JOIN products p ON w.product_id = p.id 
    WHERE w.user_id = ?
    ORDER BY w.created_at DESC
");
$stmt->execute([$user_id]);
$wishlist_items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - Velvet Vogue</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #d4af37;
            --accent-color: #f4d03f;
            --text-dark: #2c2c2c;
            --text-light: #666;
            --bg-light: #f8f9fa;
            --white: #ffffff;
            --border-light: #e9ecef;
            --shadow-light: 0 2px 10px rgba(0,0,0,0.1);
            --shadow-medium: 0 5px 20px rgba(0,0,0,0.15);
            --transition: all 0.3s ease;
            --font-primary: 'Inter', sans-serif;
            --font-secondary: 'Playfair Display', serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-primary);
            line-height: 1.6;
            color: var(--text-dark);
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        /* Header styles will come from header.php */

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            margin-top: 80px; /* Account for fixed header */
        }

        .page-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .page-title {
            font-family: var(--font-secondary);
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
            position: relative;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 2px;
        }

        .page-subtitle {
            color: var(--text-light);
            font-size: 1.1rem;
            margin-top: 20px;
        }

        /* Wishlist Grid */
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .wishlist-item {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            position: relative;
        }

        .wishlist-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }

        .item-image {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .wishlist-item:hover .item-image img {
            transform: scale(1.05);
        }

        .remove-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            color: #e74c3c;
        }

        .remove-btn:hover {
            background: #e74c3c;
            color: var(--white);
            transform: scale(1.1);
        }

        .item-content {
            padding: 25px;
        }

        .item-name {
            font-family: var(--font-secondary);
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .item-description {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .item-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .current-price {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .original-price {
            font-size: 1.1rem;
            color: var(--text-light);
            text-decoration: line-through;
        }

        .item-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            font-family: var(--font-primary);
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: var(--white);
            flex: 1;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            flex: 1;
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: translateY(-2px);
        }

        /* Empty State */
        .empty-wishlist {
            text-align: center;
            padding: 80px 20px;
            background: var(--white);
            border-radius: 15px;
            box-shadow: var(--shadow-light);
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .empty-title {
            font-family: var(--font-secondary);
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .empty-text {
            color: var(--text-light);
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .btn-large {
            padding: 15px 30px;
            font-size: 1.1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                padding: 20px 15px;
            }

            .page-title {
                font-size: 2rem;
            }

            .wishlist-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .item-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">My Wishlist</h1>
            <p class="page-subtitle">Your favorite items saved for later</p>
        </div>

        <?php if (empty($wishlist_items)): ?>
            <div class="empty-wishlist">
                <div class="empty-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h2 class="empty-title">Your wishlist is empty</h2>
                <p class="empty-text">Start adding items you love to your wishlist!</p>
                <a href="products.php" class="btn btn-primary btn-large">
                    <i class="fas fa-shopping-bag"></i>
                    Browse Products
                </a>
            </div>
        <?php else: ?>
            <div class="wishlist-grid">
                <?php foreach ($wishlist_items as $item): ?>
                    <div class="wishlist-item">
                        <div class="item-image">
                            <img src="<?php echo htmlspecialchars($item['image_path'] ?? 'assets/images/placeholder-product.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                <button type="submit" name="remove_from_wishlist" class="remove-btn" 
                                        onclick="return confirm('Remove this item from your wishlist?')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                        <div class="item-content">
                            <h3 class="item-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="item-description"><?php echo htmlspecialchars(substr($item['description'], 0, 100)) . '...'; ?></p>
                            <div class="item-price">
                                <?php if ($item['sale_price'] && $item['sale_price'] < $item['price']): ?>
                                    <span class="current-price">$<?php echo number_format($item['sale_price'], 2); ?></span>
                                    <span class="original-price">$<?php echo number_format($item['price'], 2); ?></span>
                                <?php else: ?>
                                    <span class="current-price">$<?php echo number_format($item['price'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="item-actions">
                                <a href="product_detail.php?id=<?php echo $item['product_id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-eye"></i>
                                    View Details
                                </a>
                                <button class="btn btn-outline" onclick="addToCart(<?php echo $item['product_id']; ?>)">
                                    <i class="fas fa-shopping-cart"></i>
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script>
        function addToCart(productId) {
            fetch('cart_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=add&product_id=${productId}&quantity=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product added to cart successfully!');
                } else {
                    alert('Error adding product to cart: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the product to cart.');
            });
        }
    </script>
</body>
</html>