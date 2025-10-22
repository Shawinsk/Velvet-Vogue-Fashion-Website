<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'classes/Cart.php';
require_once 'includes/functions.php';

// Initialize cart
$cart = new Cart($pdo);
$cart->refreshSession();

// Get cart items and totals
$allCartItems = $cart->getCartItems();
$cartTotals = $cart->getCartTotals();
$cartCount = $cart->getItemCount();

// Filter items based on selection (if coming from cart with selected items)
$cartItems = $allCartItems;
if (isset($_GET['selected_items'])) {
    $selectedIds = explode(',', $_GET['selected_items']);
    $cartItems = array_filter($allCartItems, function($item) use ($selectedIds) {
        return in_array($item['product_id'], $selectedIds);
    });
    
    // Recalculate totals for selected items only
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['display_price'] * $item['quantity'];
    }
    
    $tax = $subtotal * 0.13; // 13% tax
    $shipping = $subtotal >= 5000 ? 0 : 500; // Free shipping over LKR 5000
    $total = $subtotal + $tax + $shipping;
    
    $cartTotals = [
        'subtotal' => $subtotal,
        'tax' => $tax,
        'shipping' => $shipping,
        'total' => $total,
        'freeShippingEligible' => $subtotal >= 5000,
        'freeShippingThreshold' => 5000
    ];
}

// Redirect to cart if empty
if (empty($cartItems)) {
    header('Location: Cart.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $errors = [];
    
    // Validate required fields
    $required_fields = ['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'postal_code', 'payment_method'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
        }
    }
    
    // Validate payment method
    $valid_payment_methods = ['cod', 'card', 'bank'];
    if (!in_array($_POST['payment_method'], $valid_payment_methods)) {
        $errors[] = 'Please select a valid payment method.';
    }
    
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            // Generate order number
            $order_number = 'VV' . date('Ymd') . rand(1000, 9999);
            
            // Create billing and shipping address JSON
            $billing_address = json_encode([
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'postal_code' => $_POST['postal_code'],
                'country' => $_POST['country'] ?? 'Sri Lanka'
            ]);
            
            $shipping_address = $billing_address; // Same as billing for now
            
            // Create order
            $order_sql = "INSERT INTO orders (order_number, user_id, subtotal, tax_amount, shipping_amount, 
                         total_amount, currency, billing_address, shipping_address, notes, payment_method, status, payment_status, created_at) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', NOW())";
            
            $order_stmt = $pdo->prepare($order_sql);
            $order_stmt->execute([
                $order_number,
                $_SESSION['user_id'] ?? null,
                $cartTotals['subtotal'],
                $cartTotals['tax'],
                $cartTotals['shipping'],
                $cartTotals['total'],
                'LKR',
                $billing_address,
                $shipping_address,
                $_POST['notes'] ?? null,
                $_POST['payment_method']
            ]);
            
            $order_id = $pdo->lastInsertId();
            
            // Add order items
            $item_sql = "INSERT INTO order_items (order_id, product_id, product_name, product_sku, quantity, price, total) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $item_stmt = $pdo->prepare($item_sql);
            
            foreach ($cartItems as $item) {
                $item_total = $item['display_price'] * $item['quantity'];
                $item_stmt->execute([
                    $order_id,
                    $item['product_id'],
                    $item['name'],
                    $item['sku'] ?? 'N/A',
                    $item['quantity'],
                    $item['display_price'],
                    $item_total
                ]);
            }
            
            // Clear only selected items from cart (or entire cart if no selection)
            if (isset($_POST['selected_items'])) {
                $selectedIds = explode(',', $_POST['selected_items']);
                foreach ($selectedIds as $productId) {
                    $cart->removeFromCart((int)$productId);
                }
            } else {
                $cart->clearCart();
            }
            
            $pdo->commit();
            
            // Redirect to success page
            $_SESSION['order_success'] = [
                'order_number' => $order_number,
                'total' => $cartTotals['total']
            ];
            header('Location: order_success.php');
            exit;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            // Log the actual error for debugging
            error_log("Checkout Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
            
            // Show more specific error in development (you can remove this in production)
            if (isset($_GET['debug']) && $_GET['debug'] == '1') {
                $errors[] = 'Debug Error: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')';
            } else {
                $errors[] = 'An error occurred while processing your order. Please try again.';
            }
        }
    }
}

$pageTitle = 'Checkout';
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
    <style>
        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .checkout-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .checkout-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 40px;
        }
        
        .billing-form {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #d4af37;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .order-summary {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .summary-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .summary-item:last-child {
            border-bottom: none;
        }
        
        .item-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            margin-right: 15px;
        }
        
        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-name {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .item-price {
            color: #666;
            font-size: 14px;
        }
        
        .item-quantity {
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 10px;
        }
        
        .summary-totals {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .total-row.final {
            font-weight: 600;
            font-size: 18px;
            color: #d4af37;
            border-top: 1px solid #e0e0e0;
            padding-top: 10px;
            margin-top: 15px;
        }
        
        .btn-place-order {
            width: 100%;
            background: linear-gradient(135deg, #d4af37, #f4d03f);
            color: white;
            border: none;
            padding: 18px 25px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 25px;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }
        
        .btn-place-order:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-place-order:hover:before {
            left: 100%;
        }
        
        .btn-place-order:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.5);
            background: linear-gradient(135deg, #e6c547, #f7dc4f);
        }
        
        .btn-place-order:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
        }
        
        .btn-place-order i {
            margin-right: 10px;
            font-size: 16px;
        }
        
        .btn-place-order .security-text {
             display: block;
             font-size: 12px;
             font-weight: 400;
             margin-top: 3px;
             opacity: 0.9;
             text-transform: none;
             letter-spacing: 0.5px;
         }
         
         @keyframes pulse {
             0% {
                 box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
             }
             50% {
                 box-shadow: 0 4px 20px rgba(212, 175, 55, 0.5);
             }
             100% {
                 box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
             }
         }
         
         .btn-place-order {
             animation: pulse 2s infinite;
         }
         
         .btn-place-order:hover {
             animation: none;
         }
         
         .payment-security {
             background: #f8f9fa;
             border: 1px solid #e9ecef;
             border-radius: 8px;
             padding: 15px;
             margin-top: 20px;
             text-align: center;
         }
         
         .payment-security h4 {
             color: #28a745;
             margin: 0 0 10px 0;
             font-size: 14px;
         }
         
         .payment-security p {
             margin: 0;
             font-size: 12px;
             color: #6c757d;
         }
         
         .security-icons {
             display: flex;
             justify-content: center;
             gap: 15px;
             margin-top: 10px;
         }
         
         .security-icons i {
             font-size: 20px;
             color: #28a745;
         }
        
        .error-messages {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .security-badges {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }
        
        .security-badge {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 12px;
        }
        
        /* Payment Methods Styles */
        .payment-methods {
            margin-bottom: 30px;
        }
        
        .payment-method {
            margin-bottom: 15px;
        }
        
        .payment-method input[type="radio"] {
            display: none;
        }
        
        .payment-label {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fff;
        }
        
        .payment-label:hover {
            border-color: #d4af37;
            box-shadow: 0 2px 10px rgba(212, 175, 55, 0.1);
        }
        
        .payment-method input[type="radio"]:checked + .payment-label {
            border-color: #d4af37;
            background: linear-gradient(135deg, #f8f6f0, #fff);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.2);
        }
        
        .payment-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d4af37, #f4e4bc);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #fff;
            font-size: 20px;
        }
        
        .payment-info h4 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 16px;
            font-weight: 600;
        }
        
        .payment-info p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .checkout-content {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .payment-label {
                padding: 15px;
            }
            
            .payment-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
                margin-right: 12px;
            }
            
            .payment-info h4 {
                font-size: 14px;
            }
            
            .payment-info p {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="main-content">
        <div class="checkout-container">
            <div class="checkout-header">
                <h1><i class="fas fa-credit-card"></i> Checkout</h1>
                <p>Complete your order securely</p>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="checkout-content">
                <div class="billing-form">
                    <h3><i class="fas fa-credit-card"></i> Payment Method</h3>
                    
                    <div class="payment-methods">
                        <div class="payment-method">
                            <input type="radio" id="cod" name="payment_method" value="cod" checked>
                            <label for="cod" class="payment-label">
                                <div class="payment-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="payment-info">
                                    <h4>Cash on Delivery</h4>
                                    <p>Pay when your order is delivered to your doorstep</p>
                                </div>
                            </label>
                        </div>
                        
                        <div class="payment-method">
                            <input type="radio" id="card" name="payment_method" value="card">
                            <label for="card" class="payment-label">
                                <div class="payment-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="payment-info">
                                    <h4>Credit/Debit Card</h4>
                                    <p>Secure online payment with your card</p>
                                </div>
                            </label>
                        </div>
                        
                        <div class="payment-method">
                            <input type="radio" id="bank" name="payment_method" value="bank">
                            <label for="bank" class="payment-label">
                                <div class="payment-icon">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="payment-info">
                                    <h4>Bank Transfer</h4>
                                    <p>Direct bank transfer to our account</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <h3 style="margin-top: 40px;"><i class="fas fa-user"></i> Billing Information</h3>
                    
                    <form method="POST">
                        <?php if (isset($_GET['selected_items'])): ?>
                            <input type="hidden" name="selected_items" value="<?php echo htmlspecialchars($_GET['selected_items']); ?>">
                        <?php endif; ?>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">First Name *</label>
                                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address *</label>
                            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="postal_code">Postal Code *</label>
                                <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($_POST['postal_code'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="country">Country</label>
                            <select id="country" name="country">
                                <option value="Sri Lanka" selected>Sri Lanka</option>
                                <option value="India">India</option>
                                <option value="Maldives">Maldives</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Order Notes (Optional)</label>
                            <textarea id="notes" name="notes" rows="3" style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; resize: vertical;"><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                        </div>
                        
                        <button type="submit" name="place_order" class="btn-place-order" id="place-order-btn">
                            <i class="fas fa-shield-alt"></i> <span id="btn-text">Place Order - Cash on Delivery</span>
                            <span class="security-text" id="security-text">💰 Pay when delivered • 100% Secure</span>
                        </button>
                        
                        <div class="payment-security">
                            <h4><i class="fas fa-check-circle"></i> Your Payment is Protected</h4>
                            <p>We use industry-standard SSL encryption to protect your personal and payment information.</p>
                            <div class="security-icons">
                                <i class="fas fa-lock" title="SSL Encrypted"></i>
                                <i class="fas fa-shield-alt" title="Secure Payment"></i>
                                <i class="fas fa-credit-card" title="Safe Transaction"></i>
                                <i class="fas fa-user-shield" title="Privacy Protected"></i>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="order-summary">
                    <h3><i class="fas fa-shopping-bag"></i> Order Summary</h3>
                    
                    <div class="summary-items">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="summary-item">
                                <div class="item-image">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <?php else: ?>
                                        <div style="background: #f0f0f0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="color: #ccc;"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="item-details">
                                    <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                    <div class="item-price">LKR <?php echo number_format($item['display_price'], 2); ?></div>
                                </div>
                                <div class="item-quantity">×<?php echo $item['quantity']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="summary-totals">
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span>LKR <?php echo number_format($cartTotals['subtotal'], 2); ?></span>
                        </div>
                        <div class="total-row">
                            <span>Tax (13%):</span>
                            <span>LKR <?php echo number_format($cartTotals['tax'], 2); ?></span>
                        </div>
                        <div class="total-row">
                            <span>Shipping:</span>
                            <span>
                                <?php if ($cartTotals['freeShippingEligible']): ?>
                                    <span style="color: #27ae60; font-weight: 500;">FREE</span>
                                <?php else: ?>
                                    LKR <?php echo number_format($cartTotals['shipping'], 2); ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="total-row final">
                            <span>Total:</span>
                            <span>LKR <?php echo number_format($cartTotals['total'], 2); ?></span>
                        </div>
                    </div>
                    
                    <div class="security-badges">
                        <div class="security-badge">
                            <i class="fas fa-shield-alt"></i>
                            <span>Secure Checkout</span>
                        </div>
                        <div class="security-badge">
                            <i class="fas fa-truck"></i>
                            <span>Fast Delivery</span>
                        </div>
                        <div class="security-badge">
                            <i class="fas fa-undo"></i>
                            <span>Easy Returns</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = ['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'postal_code'];
            let hasErrors = false;
            
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    input.style.borderColor = '#e74c3c';
                    hasErrors = true;
                } else {
                    input.style.borderColor = '#e0e0e0';
                }
            });
            
            if (hasErrors) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
        
        // Email validation
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#e74c3c';
            } else {
                this.style.borderColor = '#e0e0e0';
            }
        });
        
        // Phone validation
        document.getElementById('phone').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+\-\s]/g, '');
        });
        
        // Payment method change handler
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const btnText = document.getElementById('btn-text');
        const securityText = document.getElementById('security-text');
        
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                switch(this.value) {
                    case 'cod':
                        btnText.textContent = 'Place Order - Cash on Delivery';
                        securityText.textContent = '💰 Pay when delivered • 100% Secure';
                        break;
                    case 'card':
                        btnText.textContent = 'Place Order - Card Payment';
                        securityText.textContent = '🔒 SSL Encrypted • 100% Secure Payment';
                        break;
                    case 'bank':
                        btnText.textContent = 'Place Order - Bank Transfer';
                        securityText.textContent = '🏦 Bank transfer details will be provided • Secure';
                        break;
                }
            });
        });
    </script>
</body>
</html>