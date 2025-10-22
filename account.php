<?php
/**
 * Velvet Vogue - User Account Dashboard
 * 
 * This file provides a comprehensive user account management interface
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

// Check if user is logged in
if (!is_logged_in()) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    redirect('account_login.php');
}

$user_id = get_current_user_id();
$error = '';
$success = '';
$active_tab = $_GET['tab'] ?? 'profile';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'update_profile') {
            // Handle profile update
            $first_name = sanitize_input($_POST['first_name'] ?? '');
            $last_name = sanitize_input($_POST['last_name'] ?? '');
            $email = sanitize_input($_POST['email'] ?? '');
            $phone = sanitize_input($_POST['phone'] ?? '');
            $date_of_birth = $_POST['date_of_birth'] ?? '';
            $gender = $_POST['gender'] ?? '';
            
            if (empty($first_name) || empty($last_name) || empty($email)) {
                $error = 'Please fill in all required fields';
            } elseif (!is_valid_email($email)) {
                $error = 'Please enter a valid email address';
            } else {
                try {
                    // Check if email is already taken by another user
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                    $stmt->execute([$email, $user_id]);
                    if ($stmt->fetch()) {
                        $error = 'This email is already taken by another account';
                    } else {
                        // Update user profile
                        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, date_of_birth = ?, gender = ?, updated_at = NOW() WHERE id = ?");
                        if ($stmt->execute([$first_name, $last_name, $email, $phone, $date_of_birth ?: null, $gender ?: null, $user_id])) {
                            // Update session data
                            $_SESSION['first_name'] = $first_name;
                            $_SESSION['last_name'] = $last_name;
                            $_SESSION['email'] = $email;
                            $_SESSION['user_name'] = $first_name . ' ' . $last_name;
                            
                            log_activity($pdo, $user_id, 'profile_update', 'Profile information updated');
                            $success = 'Profile updated successfully!';
                        } else {
                            $error = 'Failed to update profile. Please try again.';
                        }
                    }
                } catch (PDOException $e) {
                    error_log("Profile update error: " . $e->getMessage());
                    $error = 'An error occurred. Please try again.';
                }
            }
        } elseif ($_POST['action'] === 'change_password') {
            // Handle password change
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $error = 'Please fill in all password fields';
            } elseif (strlen($new_password) < 6) {
                $error = 'New password must be at least 6 characters long';
            } elseif ($new_password !== $confirm_password) {
                $error = 'New passwords do not match';
            } else {
                try {
                    // Verify current password
                    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch();
                    
                    if ($user && password_verify($current_password, $user['password'])) {
                        // Update password
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
                        if ($stmt->execute([$hashed_password, $user_id])) {
                            log_activity($pdo, $user_id, 'password_change', 'Password changed');
                            $success = 'Password changed successfully!';
                        } else {
                            $error = 'Failed to change password. Please try again.';
                        }
                    } else {
                        $error = 'Current password is incorrect';
                    }
                } catch (PDOException $e) {
                    error_log("Password change error: " . $e->getMessage());
                    $error = 'An error occurred. Please try again.';
                }
            }
        }
    }
}

// Get user data
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        redirect('account_login.php');
    }
} catch (PDOException $e) {
    error_log("Get user data error: " . $e->getMessage());
    redirect('account_login.php');
}

// Get user orders
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Get orders error: " . $e->getMessage());
    $orders = [];
}

// Get user statistics
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as order_count, COALESCE(SUM(total_amount), 0) as total_spent FROM orders WHERE user_id = ? AND status != 'cancelled'");
    $stmt->execute([$user_id]);
    $stats = $stmt->fetch();
} catch (PDOException $e) {
    error_log("Get stats error: " . $e->getMessage());
    $stats = ['order_count' => 0, 'total_spent' => 0];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Velvet Vogue</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        .account-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }
        
        .account-header {
            background: linear-gradient(135deg, #8B4513, #D4AF37);
            color: white;
            padding: 3rem 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .account-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(50%, -50%);
        }
        
        .account-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            transform: translate(-50%, 50%);
        }
        
        .account-header-content {
            position: relative;
            z-index: 1;
        }
        
        .account-header h1 {
            font-family: var(--font-primary);
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .account-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }
        
        .account-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .stat-card .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .account-content {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 2rem;
            align-items: start;
        }
        
        .account-sidebar {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 2rem;
        }
        
        .sidebar-nav {
            list-style: none;
        }
        
        .sidebar-nav li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: linear-gradient(135deg, #8B4513, #D4AF37);
            color: white;
            transform: translateX(5px);
        }
        
        .account-main {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .section-title {
            font-family: var(--font-primary);
            font-size: 1.8rem;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border-color);
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #8B4513;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        }
        
        .btn {
            padding: 0.875rem 2rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #8B4513, #D4AF37);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 69, 19, 0.3);
        }
        
        .btn-secondary {
            background: var(--bg-light);
            color: var(--text-secondary);
            border: 2px solid var(--border-color);
        }
        
        .btn-secondary:hover {
            background: var(--border-color);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            color: #721c24;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }
        
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .orders-table th,
        .orders-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        .orders-table th {
            background: var(--bg-light);
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: rgba(255, 193, 7, 0.2);
            color: #856404;
        }
        
        .status-processing {
            background: rgba(0, 123, 255, 0.2);
            color: #004085;
        }
        
        .status-shipped {
            background: rgba(23, 162, 184, 0.2);
            color: #0c5460;
        }
        
        .status-delivered {
            background: rgba(40, 167, 69, 0.2);
            color: #155724;
        }
        
        .status-cancelled {
            background: rgba(220, 53, 69, 0.2);
            color: #721c24;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-light);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        @media (max-width: 768px) {
            .account-content {
                grid-template-columns: 1fr;
            }
            
            .account-sidebar {
                position: static;
            }
            
            .sidebar-nav {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 0.5rem;
            }
            
            .account-header h1 {
                font-size: 2rem;
            }
            
            .account-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="account-container">
        <!-- Account Header -->
        <div class="account-header">
            <div class="account-header-content">
                <h1>Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>!</h1>
                <p>Manage your account, view orders, and update your preferences</p>
                
                <div class="account-stats">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['order_count']; ?></div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">LKR <?php echo number_format($stats['total_spent'], 2); ?></div>
                        <div class="stat-label">Total Spent</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo date('M Y', strtotime($user['created_at'])); ?></div>
                        <div class="stat-label">Member Since</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Account Content -->
        <div class="account-content">
            <!-- Sidebar Navigation -->
            <div class="account-sidebar">
                <ul class="sidebar-nav">
                    <li>
                        <a href="?tab=profile" class="<?php echo $active_tab === 'profile' ? 'active' : ''; ?>">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a href="?tab=orders" class="<?php echo $active_tab === 'orders' ? 'active' : ''; ?>">
                            <i class="fas fa-shopping-bag"></i> Orders
                        </a>
                    </li>
                    <li>
                        <a href="?tab=security" class="<?php echo $active_tab === 'security' ? 'active' : ''; ?>">
                            <i class="fas fa-shield-alt"></i> Security
                        </a>
                    </li>
                    <li>
                        <a href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Shopping Cart
                        </a>
                    </li>
                    <li>
                        <a href="products.php">
                            <i class="fas fa-store"></i> Continue Shopping
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" style="color: #dc3545;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="account-main">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Profile Tab -->
                <div class="tab-content <?php echo $active_tab === 'profile' ? 'active' : ''; ?>">
                    <h2 class="section-title">Profile Information</h2>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="first_name">First Name *</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="last_name">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" 
                                       value="<?php echo $user['date_of_birth'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select id="gender" name="gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="male" <?php echo ($user['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="female" <?php echo ($user['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Female</option>
                                    <option value="other" <?php echo ($user['gender'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </form>
                </div>
                
                <!-- Orders Tab -->
                <div class="tab-content <?php echo $active_tab === 'orders' ? 'active' : ''; ?>">
                    <h2 class="section-title">Order History</h2>
                    
                    <?php if (empty($orders)): ?>
                        <div class="empty-state">
                            <i class="fas fa-shopping-bag"></i>
                            <h3>No orders yet</h3>
                            <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                            <a href="products.php" class="btn btn-primary">
                                <i class="fas fa-store"></i> Start Shopping
                            </a>
                        </div>
                    <?php else: ?>
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                                        <td><?php echo format_date($order['created_at']); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td><strong>LKR <?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                        <td>
                                            <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-secondary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                
                <!-- Security Tab -->
                <div class="tab-content <?php echo $active_tab === 'security' ? 'active' : ''; ?>">
                    <h2 class="section-title">Security Settings</h2>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="change_password">
                        
                        <div class="form-group">
                            <label for="current_password">Current Password *</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password *</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password *</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php require_once 'includes/footer.php'; ?>
    
    <script>
        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            // Password confirmation validation
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');
            
            if (newPassword && confirmPassword) {
                confirmPassword.addEventListener('input', function() {
                    if (this.value && this.value !== newPassword.value) {
                        this.style.borderColor = '#dc3545';
                    } else {
                        this.style.borderColor = '';
                    }
                });
            }
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>