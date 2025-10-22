<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if order success data exists
if (!isset($_SESSION['order_success'])) {
    header('Location: index.php');
    exit;
}

$orderData = $_SESSION['order_success'];
unset($_SESSION['order_success']); // Clear the session data

$pageTitle = 'Order Confirmation';
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
        .success-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
            text-align: center;
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: successPulse 2s ease-in-out infinite;
        }
        
        .success-icon i {
            font-size: 40px;
            color: white;
        }
        
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .success-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .success-subtitle {
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 40px;
        }
        
        .order-details {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .order-number {
            font-size: 1.5rem;
            font-weight: 600;
            color: #d4af37;
            margin-bottom: 15px;
        }
        
        .order-total {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .info-item {
            text-align: left;
        }
        
        .info-label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #7f8c8d;
        }
        
        .action-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #d4af37, #f4d03f);
            color: white;
        }
        
        .btn-secondary {
            background: #ecf0f1;
            color: #2c3e50;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .next-steps {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-top: 30px;
        }
        
        .next-steps h3 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .steps-list {
            text-align: left;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .step-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #555;
        }
        
        .step-item i {
            color: #d4af37;
            margin-right: 10px;
            width: 20px;
        }
        
        @media (max-width: 768px) {
            .success-title {
                font-size: 2rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="main-content">
        <div class="success-container">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            
            <h1 class="success-title">Order Confirmed!</h1>
            <p class="success-subtitle">Thank you for your purchase. Your order has been successfully placed.</p>
            
            <div class="order-details">
                <div class="order-number">
                    Order #<?php echo htmlspecialchars($orderData['order_number']); ?>
                </div>
                
                <div class="order-total">
                    Total: <strong>LKR <?php echo number_format($orderData['total'], 2); ?></strong>
                </div>
                
                <div class="order-info">
                    <div class="info-item">
                        <div class="info-label">Order Status</div>
                        <div class="info-value">Pending Processing</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Payment Status</div>
                        <div class="info-value">Pending</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Estimated Delivery</div>
                        <div class="info-value">3-5 Business Days</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Order Date</div>
                        <div class="info-value"><?php echo date('F j, Y'); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="products.php" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i>
                    Continue Shopping
                </a>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i>
                    Back to Home
                </a>
            </div>
            
            <div class="next-steps">
                <h3><i class="fas fa-info-circle"></i> What's Next?</h3>
                <div class="steps-list">
                    <div class="step-item">
                        <i class="fas fa-envelope"></i>
                        <span>You'll receive an order confirmation email shortly</span>
                    </div>
                    <div class="step-item">
                        <i class="fas fa-cog"></i>
                        <span>We'll process your order within 24 hours</span>
                    </div>
                    <div class="step-item">
                        <i class="fas fa-shipping-fast"></i>
                        <span>Your order will be shipped within 1-2 business days</span>
                    </div>
                    <div class="step-item">
                        <i class="fas fa-bell"></i>
                        <span>You'll receive tracking information once shipped</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        // Auto-redirect after 30 seconds (optional)
        // setTimeout(() => {
        //     window.location.href = 'products.php';
        // }, 30000);
        
        // Confetti effect (optional)
        function createConfetti() {
            const colors = ['#d4af37', '#f4d03f', '#27ae60', '#3498db', '#e74c3c'];
            
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'fixed';
                    confetti.style.left = Math.random() * 100 + 'vw';
                    confetti.style.top = '-10px';
                    confetti.style.width = '10px';
                    confetti.style.height = '10px';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.borderRadius = '50%';
                    confetti.style.pointerEvents = 'none';
                    confetti.style.zIndex = '9999';
                    confetti.style.animation = 'fall 3s linear forwards';
                    
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => {
                        confetti.remove();
                    }, 3000);
                }, i * 100);
            }
        }
        
        // Add CSS for confetti animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fall {
                to {
                    transform: translateY(100vh) rotate(360deg);
                }
            }
        `;
        document.head.appendChild(style);
        
        // Trigger confetti on page load
        window.addEventListener('load', createConfetti);
    </script>
</body>
</html>