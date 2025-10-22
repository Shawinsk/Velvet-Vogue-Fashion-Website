<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get cart count for header
$cartCount = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch();
    $cartCount = $result['total'] ?? 0;
}

// Handle contact form submission
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $messageText = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($messageText)) {
        $message = 'All fields are required.';
        $messageType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $messageType = 'error';
    } else {
        // Here you would typically save to database or send email
        $message = 'Thank you for your message! We will get back to you soon.';
        $messageType = 'success';
        // Clear form data on success
        $name = $email = $subject = $messageText = '';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Velvet Vogue</title>
    <meta name="description" content="Get in touch with Velvet Vogue. Contact us for any questions about our luxury fashion collection.">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/performance.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <!-- Search Overlay -->
    <div class="search-overlay" id="search-overlay">
        <div class="search-content">
            <div class="container">
                <form class="search-form" action="products.php" method="GET">
                    <input type="text" name="search" placeholder="Search for products..." class="search-input" autocomplete="off">
                    <button type="submit" class="search-submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <div class="search-suggestions" id="search-suggestions">
                    <!-- Search suggestions will be populated here -->
                </div>
            </div>
            <button class="search-close" id="search-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

<style>
/* Contact Page Styles */
.contact-hero {
    background: linear-gradient(135deg, #2c1810 0%, #d4af37 100%);
    padding: 160px 0 80px;
    text-align: center;
    position: relative;
    overflow: hidden;
    margin-top: 80px;
}

.contact-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.contact-hero h1 {
    font-size: 4rem;
    font-weight: 700;
    color: white;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    background: linear-gradient(45deg, #ffffff, #d4af37);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
    z-index: 1;
}

.contact-hero p {
    font-size: 1.3rem;
    color: rgba(255,255,255,0.9);
    max-width: 600px;
    margin: 0 auto;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    position: relative;
    z-index: 1;
}

.contact-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    margin: 80px 0;
}

.contact-form {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 50px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(212, 175, 55, 0.2);
}

.contact-form h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2.8rem;
    color: #2c1810;
    margin-bottom: 30px;
    text-align: center;
    position: relative;
}

.contact-form h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #d4af37, #2c1810);
    border-radius: 2px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c1810;
    font-size: 1.1rem;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 15px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.9);
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #d4af37;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    transform: translateY(-2px);
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

.submit-btn {
    background: linear-gradient(135deg, #d4af37 0%, #2c1810 100%);
    color: white;
    padding: 18px 40px;
    border: none;
    border-radius: 50px;
    font-size: 1.2rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    overflow: hidden;
}

.submit-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.submit-btn:hover::before {
    left: 100%;
}

.submit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
}

.contact-info {
    background: linear-gradient(135deg, #2c1810 0%, #1a0f08 100%);
    color: white;
    border-radius: 20px;
    padding: 50px;
    position: relative;
    overflow: hidden;
}

.contact-info::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60"><defs><pattern id="luxury" width="60" height="60" patternUnits="userSpaceOnUse"><path d="M30 0L45 15L30 30L15 15Z" fill="%23d4af37" opacity="0.05"/></pattern></defs><rect width="60" height="60" fill="url(%23luxury)"/></svg>');
}

.contact-info h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2.8rem;
    margin-bottom: 30px;
    text-align: center;
    position: relative;
    z-index: 1;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    background: linear-gradient(45deg, #ffffff, #d4af37);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
    padding: 25px;
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(212, 175, 55, 0.2);
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.info-item:hover {
    transform: translateX(10px) scale(1.02);
    background: rgba(212, 175, 55, 0.2);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.info-item i {
    font-size: 2rem;
    color: #d4af37;
    margin-right: 20px;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(212, 175, 55, 0.2);
    border-radius: 50%;
    border: 2px solid #d4af37;
}

.info-item div h3 {
    font-size: 1.3rem;
    margin-bottom: 5px;
    color: #d4af37;
    font-weight: 600;
}

.info-item div p {
    color: rgba(255,255,255,0.9);
    margin: 0;
    font-size: 1.1rem;
}

.map-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 80px 0;
    position: relative;
}

.map-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><defs><pattern id="mapPattern" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="%23d4af37" opacity="0.1"/></pattern></defs><rect width="40" height="40" fill="url(%23mapPattern)"/></svg>');
}

.map-header {
    text-align: center;
    margin-bottom: 50px;
    position: relative;
    z-index: 1;
}

.map-section h2 {
    font-family: 'Playfair Display', serif;
    font-size: 3rem;
    color: #2c1810;
    margin-bottom: 20px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

.map-container {
    max-width: 800px;
    margin: 0 auto;
    height: 400px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 3px solid #d4af37;
    position: relative;
    z-index: 1;
}

.map-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #2c1810 0%, #d4af37 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    position: relative;
    overflow: hidden;
}

.map-placeholder::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><rect width="20" height="20" fill="none" stroke="%23ffffff" stroke-width="0.5" opacity="0.2"/></svg>');
}

.map-placeholder i {
    font-size: 4rem;
    margin-bottom: 20px;
    color: #d4af37;
    position: relative;
    z-index: 1;
}

.map-placeholder p {
    margin: 0;
    text-align: center;
    position: relative;
    z-index: 1;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

.success-message, .error-message {
    padding: 20px 25px;
    border-radius: 15px;
    margin-bottom: 30px;
    font-weight: 600;
    font-size: 1.1rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    text-align: center;
}

.success-message {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: 2px solid #047857;
    padding-left: 60px;
}

.success-message::before {
    content: '✓';
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.5rem;
    font-weight: bold;
}

.error-message {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border: 2px solid #b91c1c;
    padding-left: 60px;
}

.error-message::before {
    content: '⚠';
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.5rem;
    font-weight: bold;
}

/* Responsive Design */
@media (max-width: 768px) {
    .contact-hero {
        padding: 60px 20px;
    }

    .contact-hero h1 {
        font-size: 2.8rem;
    }

    .contact-hero p {
        font-size: 1.1rem;
    }

    .contact-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .contact-form,
    .contact-info {
        padding: 30px;
    }

    .contact-form h2,
    .contact-info h2 {
        font-size: 2.2rem;
    }

    .info-item:hover {
        transform: translateX(5px) scale(1.01);
    }

    .map-section {
        padding: 60px 20px;
    }

    .map-section h2 {
        font-size: 2.5rem;
    }

    .map-container {
        height: 350px;
        margin: 0 20px;
    }

    .submit-btn {
        padding: 16px 30px;
        font-size: 1.1rem;
    }
}

@media (max-width: 480px) {
    .contact-hero h1 {
        font-size: 2.2rem;
    }

    .contact-form,
    .contact-info {
        padding: 25px;
    }

    .info-item {
        padding: 20px;
        flex-direction: column;
        text-align: center;
    }

    .info-item i {
        margin-right: 0;
        margin-bottom: 15px;
    }

    .map-container {
        height: 300px;
    }
}
</style>

<!-- Contact Hero Section -->
<div class="contact-hero">
    <div class="contact-container">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
    </div>
</div>

<!-- Contact Form and Info -->
<div class="contact-container">
    <div class="contact-grid">
        <div class="contact-form">
            <h2>Send Message</h2>
            
            <?php if ($message): ?>
                <div class="<?php echo $messageType; ?>-message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required><?php echo htmlspecialchars($messageText ?? ''); ?></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
        
        <div class="contact-info">
            <h2>Contact Information</h2>
            
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h3>Address</h3>
                    <p>123 Fashion Street<br>New York, NY 10001</p>
                </div>
            </div>
            
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <div>
                    <h3>Phone</h3>
                    <p>+1 (234) 567-890</p>
                </div>
            </div>
            
            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <h3>Email</h3>
                    <p>info@velvetvogue.com</p>
                </div>
            </div>
            
            <div class="info-item">
                <i class="fas fa-clock"></i>
                <div>
                    <h3>Business Hours</h3>
                    <p>Mon - Fri: 9:00 AM - 8:00 PM<br>Sat - Sun: 10:00 AM - 6:00 PM</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Section -->
<div class="map-section">
    <div class="contact-container">
        <div class="map-header">
            <h2>Find Us</h2>
            <p>Visit our flagship store in the heart of the fashion district</p>
        </div>
        
        <div class="map-container">
            <div class="map-placeholder">
                <i class="fas fa-map-marked-alt"></i>
                <p>Interactive map will be integrated here<br>123 Fashion Street, New York, NY 10001</p>
            </div>
        </div>
    </div>
</div>

<script>
// Contact form validation and smooth scrolling
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Form validation
    const form = document.querySelector('.contact-form form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = '#ef4444';
                } else {
                    input.style.borderColor = '#e0e0e0';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>

<script>
// Enhanced Header JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    // Search overlay functionality
    const searchOverlay = document.getElementById('search-overlay');
    const searchClose = document.getElementById('search-close');
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    // Search functionality
    const navSearchForm = document.querySelector('.nav-search-form');
    if (navSearchForm) {
        const searchWrapper = navSearchForm.querySelector('.search-input-wrapper');
        const navSearchInput = navSearchForm.querySelector('.nav-search-input');
        const navSearchBtn = navSearchForm.querySelector('.nav-search-btn');
        
        // Click outside to close search
        document.addEventListener('click', function(e) {
            if (!navSearchForm.contains(e.target)) {
                searchWrapper.classList.remove('expanded');
            }
        });
        
        // Handle search button click
        if (navSearchBtn) {
            navSearchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!searchWrapper.classList.contains('expanded')) {
                    searchWrapper.classList.add('expanded');
                    navSearchInput.focus();
                } else if (navSearchInput.value.trim()) {
                    // Perform search - redirect to products page with search query
                    const searchQuery = navSearchInput.value.trim();
                    window.location.href = `products.php?search=${encodeURIComponent(searchQuery)}`;
                }
            });
        }
        
        // Handle Enter key in search input
        navSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchQuery = this.value.trim();
                if (searchQuery) {
                    window.location.href = `products.php?search=${encodeURIComponent(searchQuery)}`;
                }
            }
        });
    }
    
    // Mobile menu toggle
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });
    }
    
    // Search overlay close
    if (searchClose) {
        searchClose.addEventListener('click', function() {
            searchOverlay.classList.remove('active');
        });
    }
    
    // Close search overlay on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
            searchOverlay.classList.remove('active');
        }
    });
});
</script>

<!-- JavaScript -->
<script src="assets/js/performance-optimizer.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>