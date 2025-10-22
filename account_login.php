<?php
/**
 * Velvet Vogue - Customer Account Login & Registration
 * 
 * This file handles customer login and registration functionality
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Redirect if already logged in
if (is_logged_in()) {
    redirect('index.php');
}

$error = '';
$success = '';
$mode = $_GET['mode'] ?? 'login'; // login or register

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'login') {
            // Handle login
            $email = sanitize_input($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $error = 'Please fill in all fields';
            } else {
                try {
                    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, password FROM users WHERE email = ? AND is_admin = 0");
                    $stmt->execute([$email]);
                    $user = $stmt->fetch();
                    
                    if ($user && password_verify($password, $user['password'])) {
                        // Login successful
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['first_name'] = $user['first_name'];
                        $_SESSION['last_name'] = $user['last_name'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                        
                        // Log activity
                        log_activity($pdo, $user['id'], 'login', 'Customer login');
                        
                        // Redirect to intended page or home
                        $redirect_url = $_SESSION['redirect_after_login'] ?? 'index.php';
                        unset($_SESSION['redirect_after_login']);
                        redirect($redirect_url);
                    } else {
                        $error = 'Invalid email or password';
                    }
                } catch (PDOException $e) {
                    error_log("Login error: " . $e->getMessage());
                    $error = 'An error occurred. Please try again.';
                }
            }
        } elseif ($_POST['action'] === 'register') {
            // Handle registration
            $first_name = sanitize_input($_POST['first_name'] ?? '');
            $last_name = sanitize_input($_POST['last_name'] ?? '');
            $email = sanitize_input($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validation
            if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
                $error = 'Please fill in all required fields';
            } elseif (!is_valid_email($email)) {
                $error = 'Please enter a valid email address';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters long';
            } elseif ($password !== $confirm_password) {
                $error = 'Passwords do not match';
            } else {
                try {
                    // Check if email already exists
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    if ($stmt->fetch()) {
                        $error = 'An account with this email already exists';
                    } else {
                        // Create new user
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, is_admin, created_at) VALUES (?, ?, ?, ?, 0, NOW())");
                        
                        if ($stmt->execute([$first_name, $last_name, $email, $hashed_password])) {
                            $user_id = $pdo->lastInsertId();
                            
                            // Auto-login after registration
                            $_SESSION['user_id'] = $user_id;
                            $_SESSION['first_name'] = $first_name;
                            $_SESSION['last_name'] = $last_name;
                            $_SESSION['email'] = $email;
                            $_SESSION['user_name'] = $first_name . ' ' . $last_name;
                            
                            // Log activity
                            log_activity($pdo, $user_id, 'register', 'New customer registration');
                            
                            $success = 'Account created successfully! Welcome to Velvet Vogue.';
                            // Redirect after a short delay
                            header("refresh:2;url=index.php");
                        } else {
                            $error = 'Failed to create account. Please try again.';
                        }
                    }
                } catch (PDOException $e) {
                    error_log("Registration error: " . $e->getMessage());
                    $error = 'An error occurred. Please try again.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $mode === 'register' ? 'Create Account' : 'Login'; ?> - Velvet Vogue</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #8B4513 0%, #D4AF37 50%, #2C1810 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-secondary);
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="80" cy="40" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="40" cy="80" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
            pointer-events: none;
        }
        
        .auth-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .auth-header .logo {
            font-family: var(--font-primary);
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #8B4513, #D4AF37);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .auth-header h1 {
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .auth-header p {
            color: var(--text-secondary);
            margin: 0;
            font-size: 1rem;
        }
        
        .auth-tabs {
            display: flex;
            margin-bottom: 2rem;
            background: var(--bg-light);
            border-radius: 12px;
            padding: 4px;
        }
        
        .auth-tab {
            flex: 1;
            padding: 0.75rem 1rem;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            color: var(--text-secondary);
        }
        
        .auth-tab.active {
            background: linear-gradient(135deg, #8B4513, #D4AF37);
            color: white;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
            background: var(--bg-white);
        }
        
        .form-control:focus {
            outline: none;
            border-color: #8B4513;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
            transform: translateY(-1px);
        }
        
        .form-control.error {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }
        
        .input-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .input-icon:hover {
            color: var(--text-secondary);
        }
        
        .btn-auth {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #8B4513, #D4AF37);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-auth::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 69, 19, 0.3);
        }
        
        .btn-auth:hover::before {
            left: 100%;
        }
        
        .btn-auth:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 12px;
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
        
        .divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
            color: var(--text-light);
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
        }
        
        .divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
        }
        
        .social-login {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .social-btn {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 500;
        }
        
        .social-btn:hover {
            border-color: var(--text-secondary);
            transform: translateY(-1px);
        }
        
        .back-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }
        
        .back-link a {
            color: #8B4513;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .back-link a:hover {
            color: #D4AF37;
            transform: translateX(-3px);
        }
        
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }
        
        .strength-bar {
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            margin-top: 0.25rem;
            overflow: hidden;
        }
        
        .strength-fill {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }
        
        .strength-weak { background: #dc3545; width: 25%; }
        .strength-fair { background: #ffc107; width: 50%; }
        .strength-good { background: #28a745; width: 75%; }
        .strength-strong { background: #20c997; width: 100%; }
        
        @media (max-width: 768px) {
            .auth-container {
                margin: 1rem;
                padding: 2rem 1.5rem;
            }
            
            .auth-header .logo {
                font-size: 2rem;
            }
            
            .social-login {
                flex-direction: column;
            }
        }
        
        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .form-row {
            display: flex;
            gap: 1rem;
        }
        
        .form-row .form-group {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <div class="logo">Velvet Vogue</div>
            <h1><?php echo $mode === 'register' ? 'Create Account' : 'Welcome Back'; ?></h1>
            <p><?php echo $mode === 'register' ? 'Join our fashion community today' : 'Sign in to your account'; ?></p>
        </div>
        
        <div class="auth-tabs">
            <div class="auth-tab <?php echo $mode === 'login' ? 'active' : ''; ?>" onclick="switchMode('login')">
                <i class="fas fa-sign-in-alt"></i> Login
            </div>
            <div class="auth-tab <?php echo $mode === 'register' ? 'active' : ''; ?>" onclick="switchMode('register')">
                <i class="fas fa-user-plus"></i> Register
            </div>
        </div>
        
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
        
        <!-- Login Form -->
        <form id="loginForm" method="POST" style="display: <?php echo $mode === 'login' ? 'block' : 'none'; ?>">
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label for="login_email">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" id="login_email" name="email" class="form-control" required 
                       value="<?php echo isset($_POST['email']) && $_POST['action'] === 'login' ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="login_password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password" id="login_password" name="password" class="form-control" required>
                <i class="fas fa-eye input-icon" onclick="togglePassword('login_password')"></i>
            </div>
            
            <button type="submit" class="btn-auth">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>
        
        <!-- Registration Form -->
        <form id="registerForm" method="POST" style="display: <?php echo $mode === 'register' ? 'block' : 'none'; ?>">
            <input type="hidden" name="action" value="register">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">
                        <i class="fas fa-user"></i> First Name
                    </label>
                    <input type="text" id="first_name" name="first_name" class="form-control" required 
                           value="<?php echo isset($_POST['first_name']) && $_POST['action'] === 'register' ? htmlspecialchars($_POST['first_name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="last_name">
                        <i class="fas fa-user"></i> Last Name
                    </label>
                    <input type="text" id="last_name" name="last_name" class="form-control" required 
                           value="<?php echo isset($_POST['last_name']) && $_POST['action'] === 'register' ? htmlspecialchars($_POST['last_name']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="register_email">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" id="register_email" name="email" class="form-control" required 
                       value="<?php echo isset($_POST['email']) && $_POST['action'] === 'register' ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="register_password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password" id="register_password" name="password" class="form-control" required 
                       oninput="checkPasswordStrength(this.value)">
                <i class="fas fa-eye input-icon" onclick="togglePassword('register_password')"></i>
                <div class="password-strength">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthBar"></div>
                    </div>
                    <span id="strengthText">Enter a password</span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">
                    <i class="fas fa-lock"></i> Confirm Password
                </label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                <i class="fas fa-eye input-icon" onclick="togglePassword('confirm_password')"></i>
            </div>
            
            <button type="submit" class="btn-auth">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>
        
        <div class="back-link">
            <a href="index.php">
                <i class="fas fa-arrow-left"></i> Back to Store
            </a>
        </div>
    </div>
    
    <script>
        function switchMode(mode) {
            const url = new URL(window.location);
            url.searchParams.set('mode', mode);
            window.location.href = url.toString();
        }
        
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling;
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            let text = '';
            
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'strength-fill';
            
            switch (strength) {
                case 0:
                case 1:
                    strengthBar.classList.add('strength-weak');
                    text = 'Weak password';
                    break;
                case 2:
                    strengthBar.classList.add('strength-fair');
                    text = 'Fair password';
                    break;
                case 3:
                case 4:
                    strengthBar.classList.add('strength-good');
                    text = 'Good password';
                    break;
                case 5:
                    strengthBar.classList.add('strength-strong');
                    text = 'Strong password';
                    break;
            }
            
            strengthText.textContent = text;
        }
        
        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('.btn-auth');
                    const originalText = submitBtn.innerHTML;
                    
                    // Add loading state
                    submitBtn.innerHTML = '<div class="loading"></div> Processing...';
                    submitBtn.disabled = true;
                    
                    // Re-enable after 3 seconds if form doesn't submit
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 3000);
                });
            });
            
            // Real-time validation for confirm password
            const confirmPassword = document.getElementById('confirm_password');
            const password = document.getElementById('register_password');
            
            if (confirmPassword && password) {
                confirmPassword.addEventListener('input', function() {
                    if (this.value && this.value !== password.value) {
                        this.classList.add('error');
                    } else {
                        this.classList.remove('error');
                    }
                });
            }
        });
    </script>
</body>
</html>