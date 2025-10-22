<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once 'classes/Cart.php';

// Check if user is logged in for cart operations
if (!is_logged_in()) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Please login to add items to cart',
        'redirect' => 'account_login.php'
    ]);
    exit;
}

$cart = new Cart($pdo);
$cart->refreshSession(); // Refresh session and handle cart merging

header('Content-Type: application/json');

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$action = $_POST['action'] ?? '';
$productId = (int)($_POST['product_id'] ?? 0);
$quantity = max(1, min((int)($_POST['quantity'] ?? 1), 10));

try {
    switch ($action) {
        case 'add':
            $validation = $cart->validateCartItem($productId, $quantity);
            if (!$validation['valid']) {
                echo json_encode([
                    'success' => false,
                    'message' => $validation['message']
                ]);
                exit;
            }
            
            $result = $cart->addToCart($productId, $quantity);
            if ($result) {
                $cartTotals = $cart->getCartTotals();
                echo json_encode([
                    'success' => true,
                    'message' => 'Product added to cart',
                    'cartCount' => $cart->getItemCount(),
                    'cartTotals' => $cartTotals
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to add product to cart'
                ]);
            }
            break;
            
        case 'update':
            $validation = $cart->validateCartItem($productId, $quantity);
            if (!$validation['valid']) {
                echo json_encode([
                    'success' => false,
                    'message' => $validation['message']
                ]);
                exit;
            }
            
            $result = $cart->updateQuantity($productId, $quantity);
            if ($result) {
                $cartTotals = $cart->getCartTotals();
                echo json_encode([
                    'success' => true,
                    'message' => 'Cart updated',
                    'cartTotals' => $cartTotals,
                    'cartCount' => $cart->getItemCount()
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update cart'
                ]);
            }
            break;
            
        case 'remove':
            $result = $cart->removeFromCart($productId);
            if ($result) {
                $cartTotals = $cart->getCartTotals();
                echo json_encode([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'cartTotals' => $cartTotals,
                    'cartCount' => $cart->getItemCount()
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to remove item from cart'
                ]);
            }
            break;
            
        case 'clear':
            $result = $cart->clearCart();
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Cart cleared',
                    'cartCount' => 0,
                    'cartTotals' => [
                        'subtotal' => 0,
                        'tax' => 0,
                        'shipping' => 0,
                        'total' => 0,
                        'freeShippingEligible' => false
                    ]
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to clear cart'
                ]);
            }
            break;
            
        case 'get':
            $items = $cart->getCartItems();
            $cartTotals = $cart->getCartTotals();
            $stockValidation = $cart->validateStock();
            
            echo json_encode([
                'success' => true,
                'items' => $items,
                'cartTotals' => $cartTotals,
                'cartCount' => $cart->getItemCount(),
                'stockValid' => $stockValidation['valid'],
                'invalidItems' => $stockValidation['invalidItems']
            ]);
            break;
            
        case 'get_cart_html':
            ob_start();
            $items = $cart->getCartItems();
            if (empty($items)) {
                echo '<div class="empty-cart"><i class="fas fa-shopping-cart"></i><h3>Your cart is empty</h3><p>Looks like you haven\'t added anything to your cart yet.</p><a href="products.php" class="btn btn-primary">Continue Shopping</a></div>';
            } else {
                foreach ($items as $item) {
                    $image = !empty($item['image']) ? htmlspecialchars($item['image']) : 'assets/images/placeholder.jpg';
                    $name = htmlspecialchars($item['name']);
                    $price = number_format($item['display_price'], 2);
                    $qty = (int)$item['quantity'];
                    echo "<div class='cart-item'>
                        <img src='{$image}' class='cart-item-image' alt='{$name}'>
                        <div class='cart-item-details'>
                            <div class='cart-item-title'>{$name}</div>
                            <div class='cart-item-price'>LKR {$price}</div>
                            <div class='cart-item-quantity'>Qty: {$qty}</div>
                        </div>
                    </div>";
                }
            }
            $html = ob_get_clean();
            echo $html;
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Invalid action'
            ]);
    }
} catch (Exception $e) {
    error_log("Cart handler error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred'
    ]);
}