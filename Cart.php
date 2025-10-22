<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'classes/Cart.php';

// Initialize cart
$cart = new Cart($pdo);
$cart->refreshSession();

// Get cart items and totals
$cartItems = $cart->getCartItems();
$cartTotals = $cart->getCartTotals();
$cartCount = $cart->getItemCount();

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    $productId = (int)($_POST['product_id'] ?? 0);
    $quantity = max(1, min((int)($_POST['quantity'] ?? 1), 10));
    
    switch ($action) {
        case 'update':
            $success = $cart->updateQuantity($productId, $quantity);
            break;
        case 'remove':
            $success = $cart->removeFromCart($productId);
            break;
        case 'clear':
            $success = $cart->clearCart();
            break;
        default:
            $success = false;
    }
    
    if ($success) {
        $newTotals = $cart->getCartTotals();
        $newCount = $cart->getItemCount();
        echo json_encode([
            'success' => true,
            'cartCount' => $newCount,
            'subtotal' => $newTotals['subtotal'],
            'tax' => $newTotals['tax'],
            'shipping' => $newTotals['shipping'],
            'total' => $newTotals['total']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Operation failed']);
    }
    exit;
}

$pageTitle = 'Shopping Cart';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Velvet Vogue</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="main-content">
        <div class="container">
            <div class="cart-header">
                <h1><i class="fas fa-shopping-bag"></i> Shopping Cart</h1>
                <p class="cart-subtitle">Review your items before checkout</p>
            </div>
            
            <?php if (empty($cartItems)): ?>
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h2>Your cart is empty</h2>
                    <p>Looks like you haven't added any items to your cart yet.</p>
                    <a href="products.php" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i>
                        Continue Shopping
                    </a>
                </div>
            <?php else: ?>
                <div class="cart-content">
                    <div class="cart-items">
                        <div class="cart-items-header">
                            <div class="header-left">
                                <h3>Items in your cart (<?php echo $cartCount; ?>)</h3>
                                <div class="select-all-container">
                                    <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                                    <label for="select-all">Select All</label>
                                </div>
                            </div>
                            <button class="btn-clear-cart" onclick="clearCart()">
                                <i class="fas fa-trash"></i> Clear Cart
                            </button>
                        </div>
                        
                        <div class="cart-items-list">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="cart-item" data-product-id="<?php echo $item['product_id']; ?>">
                                    <div class="item-checkbox">
                                        <input type="checkbox" 
                                               id="item-<?php echo $item['product_id']; ?>" 
                                               class="item-select" 
                                               data-product-id="<?php echo $item['product_id']; ?>"
                                               data-price="<?php echo $item['display_price']; ?>"
                                               data-quantity="<?php echo $item['quantity']; ?>"
                                               onchange="updateSelectedItems()">
                                        <label for="item-<?php echo $item['product_id']; ?>"></label>
                                    </div>
                                    <div class="item-image">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy">
                                        <?php else: ?>
                                            <div class="image-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="item-details">
                                        <h4 class="item-name"><?php echo htmlspecialchars($item['name']); ?></h4>
                                        <div class="item-price">
                                            <?php if ($item['sale_price'] > 0 && $item['sale_price'] < $item['price']): ?>
                                                <span class="sale-price">LKR <?php echo number_format($item['sale_price'], 2); ?></span>
                                                <span class="original-price">LKR <?php echo number_format($item['price'], 2); ?></span>
                                            <?php else: ?>
                                                <span class="price">LKR <?php echo number_format($item['price'], 2); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="item-stock">
                                            <?php if ($item['stock_quantity'] < 5): ?>
                                                <span class="low-stock">Only <?php echo $item['stock_quantity']; ?> left in stock</span>
                                            <?php else: ?>
                                                <span class="in-stock">In stock</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="item-quantity">
                                        <label for="qty-<?php echo $item['product_id']; ?>">Quantity:</label>
                                        <div class="quantity-controls">
                                            <button class="qty-btn qty-decrease" onclick="decreaseQuantity(<?php echo $item['product_id']; ?>)">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" 
                                                   id="qty-<?php echo $item['product_id']; ?>" 
                                                   class="qty-input"
                                                   data-product-id="<?php echo $item['product_id']; ?>"
                                                   value="<?php echo $item['quantity']; ?>" 
                                                   min="1" 
                                                   max="<?php echo min(10, $item['stock_quantity']); ?>"
                                                   onchange="updateQuantity(<?php echo $item['product_id']; ?>, this.value)">
                                            <button class="qty-btn qty-increase" onclick="increaseQuantity(<?php echo $item['product_id']; ?>)">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="item-total">
                                        <span class="total-price">LKR <?php echo number_format($item['display_price'] * $item['quantity'], 2); ?></span>
                                    </div>
                                    
                                    <div class="item-actions">
                                        <button class="btn-remove" onclick="removeItem(<?php echo $item['product_id']; ?>)" title="Remove item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="cart-summary">
                        <div class="summary-card">
                            <h3>Order Summary</h3>
                            
                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span id="subtotal">LKR <?php echo number_format($cartTotals['subtotal'], 2); ?></span>
                            </div>
                            
                            <div class="summary-row">
                                <span>Tax (13%):</span>
                                <span id="tax">LKR <?php echo number_format($cartTotals['tax'], 2); ?></span>
                            </div>
                            
                            <div class="summary-row">
                                <span>Shipping:</span>
                                <span id="shipping">
                                    <?php if ($cartTotals['freeShippingEligible']): ?>
                                        <span class="free-shipping">FREE</span>
                                    <?php else: ?>
                                        LKR <?php echo number_format($cartTotals['shipping'], 2); ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            
                            <?php if (!$cartTotals['freeShippingEligible']): ?>
                                <div class="free-shipping-notice">
                                    <i class="fas fa-truck"></i>
                                    Add LKR <?php echo number_format($cartTotals['freeShippingThreshold'] - $cartTotals['subtotal'], 2); ?> more for free shipping!
                                </div>
                            <?php endif; ?>
                            
                            <div class="summary-total">
                                <span>Total:</span>
                                <span id="total">LKR <?php echo number_format($cartTotals['total'], 2); ?></span>
                            </div>
                            
                            <div class="checkout-actions">
                                <button id="checkout-btn" class="btn btn-primary btn-checkout" onclick="proceedToCheckout()" disabled>
                                    <i class="fas fa-credit-card"></i>
                                    Proceed to Checkout (<span id="selected-count">0</span> items)
                                </button>
                                <a href="products.php" class="btn btn-outline">
                                    <i class="fas fa-arrow-left"></i>
                                    Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <!-- Loading overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
        </div>
    </div>
    
    <script src="assets/js/cart.js"></script>
</body>
</html>

<style>
/* Cart Page Styles */
.main-content {
    margin-top: 100px;
    padding: 40px 0;
    min-height: calc(100vh - 200px);
}

.cart-header {
    text-align: center;
    margin-bottom: 40px;
}

.cart-header h1 {
    color: var(--primary-color);
    margin-bottom: 10px;
}

.cart-header h1 i {
    margin-right: 15px;
    color: var(--secondary-color);
}

.cart-subtitle {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 80px 20px;
    background: var(--bg-light);
    border-radius: 12px;
    margin: 40px 0;
}

.empty-cart-icon {
    font-size: 4rem;
    color: var(--text-light);
    margin-bottom: 20px;
}

.empty-cart h2 {
    color: var(--text-primary);
    margin-bottom: 15px;
}

.empty-cart p {
    margin-bottom: 30px;
    font-size: 1.1rem;
}

/* Cart Content */
.cart-content {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 40px;
    margin-top: 30px;
}

.cart-items-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--border-color);
}

.header-left {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.select-all-container {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
}

.select-all-container input[type="checkbox"] {
    margin: 0;
}

/* Item Checkbox */
.item-checkbox {
    display: flex;
    align-items: center;
    justify-content: center;
}

.item-select {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--primary-color);
}

.item-select:checked + label {
    color: var(--primary-color);
}

/* Checkout Button States */
.btn-checkout:disabled {
    background: var(--text-light);
    color: white;
    cursor: not-allowed;
    opacity: 0.6;
}

.btn-checkout:disabled:hover {
    background: var(--text-light);
    transform: none;
}

.cart-items-header h3 {
    color: var(--primary-color);
    margin: 0;
}

.btn-clear-cart {
    background: none;
    border: 1px solid var(--text-light);
    color: var(--text-secondary);
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: var(--transition);
    font-size: 0.9rem;
}

.btn-clear-cart:hover {
    border-color: #dc3545;
    color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
}

/* Cart Items */
.cart-item {
    display: grid;
    grid-template-columns: 40px 100px 1fr 120px 100px 40px;
    gap: 20px;
    align-items: center;
    padding: 25px;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    margin-bottom: 20px;
    transition: var(--transition);
}

.cart-item:hover {
    box-shadow: var(--shadow-medium);
    transform: translateY(-2px);
}

.item-image {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
    background: var(--bg-light);
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-light);
    font-size: 2rem;
}

.item-details h4 {
    margin: 0 0 10px 0;
    color: var(--text-primary);
    font-size: 1.1rem;
}

.item-price {
    margin-bottom: 8px;
}

.sale-price {
    color: #dc3545;
    font-weight: 600;
    margin-right: 10px;
}

.original-price {
    color: var(--text-light);
    text-decoration: line-through;
    font-size: 0.9rem;
}

.price {
    color: var(--primary-color);
    font-weight: 600;
}

.item-stock {
    font-size: 0.9rem;
}

.low-stock {
    color: #ff6b35;
    font-weight: 500;
}

.in-stock {
    color: #28a745;
}

/* Quantity Controls */
.item-quantity label {
    display: block;
    margin-bottom: 8px;
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    overflow: hidden;
}

.qty-btn {
    background: var(--bg-light);
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    transition: var(--transition);
    color: var(--text-secondary);
}

.qty-btn:hover {
    background: var(--secondary-color);
    color: white;
}

.quantity-controls input {
    border: none;
    width: 50px;
    text-align: center;
    padding: 8px 4px;
    font-size: 0.9rem;
}

.item-total {
    text-align: right;
}

.total-price {
    font-weight: 600;
    color: var(--primary-color);
    font-size: 1.1rem;
}

.btn-remove {
    background: none;
    border: none;
    color: var(--text-light);
    cursor: pointer;
    padding: 8px;
    border-radius: 4px;
    transition: var(--transition);
}

.btn-remove:hover {
    color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
}

/* Cart Summary */
.summary-card {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 30px;
    position: sticky;
    top: 120px;
}

.summary-card h3 {
    color: var(--primary-color);
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--border-color);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    color: var(--text-secondary);
}

.summary-total {
    display: flex;
    justify-content: space-between;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--primary-color);
    padding-top: 20px;
    border-top: 2px solid var(--border-color);
    margin-top: 20px;
}

.free-shipping {
    color: #28a745;
    font-weight: 600;
}

.free-shipping-notice {
    background: linear-gradient(135deg, #e8f5e8, #d4edda);
    color: #155724;
    padding: 12px;
    border-radius: 6px;
    margin: 15px 0;
    font-size: 0.9rem;
    text-align: center;
}

.free-shipping-notice i {
    margin-right: 8px;
    color: #28a745;
}

.checkout-actions {
    margin-top: 30px;
}

.btn-checkout {
    width: 100%;
    justify-content: center;
    padding: 15px;
    font-size: 1rem;
    margin-bottom: 15px;
}

.checkout-actions .btn-outline {
    width: 100%;
    justify-content: center;
    padding: 12px;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-spinner {
    font-size: 2rem;
    color: var(--primary-color);
}

/* Enhanced Responsive Design */
@media (max-width: 768px) {
    .cart-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .cart-item {
        grid-template-columns: 80px 1fr;
        gap: 15px;
        padding: 20px;
    }
    
    .item-quantity,
    .item-total,
    .item-actions {
        grid-column: 1 / -1;
        margin-top: 15px;
    }
    
    .item-quantity {
        gap: 10px;
    }
    
    .item-quantity label {
        margin: 0;
        white-space: nowrap;
        font-size: 0.85rem;
    }
    
    /* Mobile quantity presets */
    .quantity-presets {
        gap: 4px;
    }
    
    .preset-btn {
        padding: 4px 8px;
        font-size: 0.8rem;
        min-width: 30px;
    }
    
    /* Mobile quantity controls */
    .quantity-controls-advanced {
        flex-direction: column;
        gap: 8px;
        align-items: stretch;
    }
    
    .quantity-controls {
        width: 100%;
        max-width: 200px;
    }
    
    .qty-input {
        width: 80px;
    }
    
    .bulk-actions {
        flex-direction: row;
        gap: 8px;
        justify-content: center;
    }
    
    .bulk-btn {
        flex: 1;
        padding: 6px 12px;
    }
    
    .item-total {
        text-align: left;
    }
    
    .summary-card {
        position: static;
    }
}

@media (max-width: 480px) {
    .cart-items-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .cart-item {
        padding: 15px;
    }
    
    .item-image {
        width: 70px;
        height: 70px;
    }
    
    .item-details h4 {
        font-size: 1rem;
    }
    
    /* Compact mobile layout */
    .quantity-presets {
        gap: 3px;
    }
    
    .preset-btn {
        padding: 3px 6px;
        font-size: 0.75rem;
        min-width: 25px;
    }
    
    .quantity-controls {
        max-width: 160px;
    }
    
    .qty-input {
        width: 60px;
        font-size: 0.9rem;
    }
    
    .qty-btn {
        padding: 8px 10px;
    }
    
    .bulk-btn {
        padding: 4px 8px;
        font-size: 0.75rem;
    }
    
    .quantity-info {
        margin-top: 6px;
    }
    
    .stock-info {
        font-size: 0.75rem;
    }
}

/* Touch-friendly enhancements */
@media (hover: none) and (pointer: coarse) {
    .qty-btn,
    .preset-btn,
    .bulk-btn {
        min-height: 44px;
        min-width: 44px;
    }
    
    .preset-btn {
        padding: 8px 12px;
    }
    
    .bulk-btn {
        padding: 8px 12px;
    }
    
    .qty-input {
        min-height: 44px;
        font-size: 16px; /* Prevents zoom on iOS */
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .quantity-controls,
    .preset-btn,
    .bulk-btn {
        border-width: 2px;
    }
    
    .preset-btn.active {
        border-width: 3px;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .preset-btn,
    .qty-btn,
    .bulk-btn,
    .quantity-controls {
        transition: none;
    }
    
    .quantity-controls::after {
        transition: none;
    }
}
</style>