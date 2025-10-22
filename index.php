<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once 'classes/Cart.php';

// Initialize cart
$cart = new Cart($pdo);
$cart->refreshSession();

// Get cart count for header
$cartCount = $cart->getItemCount();
$cartTotal = $cart->getCartTotal();

// Get announcement banner
$stmt = $pdo->prepare("SELECT setting_value FROM admin_settings WHERE setting_key = 'website_announcement_banner'");
$stmt->execute();
$banner = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velvet Vogue - Luxury Fashion Store</title>
    <meta name="description" content="Discover luxury fashion at Velvet Vogue. Premium clothing, accessories, and style for the modern individual.">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/performance.css">
</head>
<body>
    <?php 
    // Show announcement banner if exists and not empty
    if ($banner && !empty($banner['setting_value'])): 
    ?>
    <div class="announcement-banner">
        <div class="container">
            <p><i class="fas fa-star"></i> <?php echo htmlspecialchars($banner['setting_value']); ?> <i class="fas fa-star"></i></p>
        </div>
    </div>
    <?php endif; ?>

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

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-slider">
            <div class="hero-slide active" style="background-image: url('assets/images/hero-1.jpg');">
                <div class="hero-content">
                    <div class="container">
                        <div class="hero-text">
                            <h1 class="hero-title">
                                <span class="subtitle">New Collection 2024</span>
                                Velvet Elegance
                            </h1>
                            <p class="hero-description">Discover our exclusive collection of premium fashion pieces crafted for the modern individual who appreciates luxury and style.</p>
                            <a href="products.php" class="btn btn-primary hero-btn">
                                Shop Collection
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-slide" style="background-image: url('assets/images/hero-2.jpg.jpg');">
                <div class="hero-content">
                    <div class="container">
                        <div class="hero-text">
                            <h1 class="hero-title">
                                <span class="subtitle">Limited Edition</span>
                                Luxury Redefined
                            </h1>
                            <p class="hero-description">Experience unparalleled quality with our handpicked selection of designer pieces that embody sophistication and timeless appeal.</p>
                            <a href="products.php" class="btn btn-primary hero-btn">
                                Explore Now
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-slide" style="background-image: url('assets/images/fashion-model-in-pink.jpg');">
                <div class="hero-content">
                    <div class="container">
                        <div class="hero-text">
                            <h1 class="hero-title">
                                <span class="subtitle">Summer Collection</span>
                                Fashion Forward
                            </h1>
                            <p class="hero-description">Step into the season with our vibrant summer collection featuring bold colors and contemporary designs for the fashion-forward individual.</p>
                            <a href="products.php?category=womens-dresses" class="btn btn-primary hero-btn">
                                Shop Summer
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-slide" style="background-image: url('assets/images/istockphoto-1293366109-612x612.jpg');">
                <div class="hero-content">
                    <div class="container">
                        <div class="hero-text">
                            <h1 class="hero-title">
                                <span class="subtitle">Men's Collection</span>
                                Sophisticated Style
                            </h1>
                            <p class="hero-description">Elevate your wardrobe with our refined men's collection featuring classic cuts and modern tailoring for the distinguished gentleman.</p>
                            <a href="products.php?category=mens-shirts" class="btn btn-primary hero-btn">
                                Shop Men's
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-slide" style="background-image: url('assets/images/istockphoto-531786318-612x612.jpg');">
                <div class="hero-content">
                    <div class="container">
                        <div class="hero-text">
                            <h1 class="hero-title">
                                <span class="subtitle">Accessories</span>
                                Complete Your Look
                            </h1>
                            <p class="hero-description">Perfect your style with our curated selection of premium accessories including bags, jewelry, and statement pieces.</p>
                            <a href="products.php?category=accessories" class="btn btn-primary hero-btn">
                                Shop Accessories
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Slider Navigation -->
        <div class="hero-nav">
            <button class="hero-prev"><i class="fas fa-chevron-left"></i></button>
            <button class="hero-next"><i class="fas fa-chevron-right"></i></button>
        </div>
        
        <!-- Slider Dots -->
        <div class="hero-dots">
            <span class="dot active" data-slide="0"></span>
            <span class="dot" data-slide="1"></span>
            <span class="dot" data-slide="2"></span>
            <span class="dot" data-slide="3"></span>
            <span class="dot" data-slide="4"></span>
        </div>
    </section>

    <!-- Page Header -->
    <section class="page-header">
        <div class="floating-elements">
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
        </div>
       
    </section>

    

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Free Shipping</h3>
                    <p>Complimentary shipping on orders over LKR 200</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h3>Easy Returns</h3>
                    <p>30-day return policy for your peace of mind</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Dedicated customer service team</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>Premium Quality</h3>
                    <p>Carefully curated luxury fashion pieces</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-subtitle">Explore our curated collections</p>
            </div>
            
            <div class="categories-grid">
                <?php
                // Get active categories from database with error handling
                try {
                    $stmt = $pdo->prepare("SELECT id, name, slug, description, image FROM categories WHERE is_active = 1 ORDER BY name LIMIT 8");
                    $stmt->execute();
                    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    $categories = [];
                    error_log("Database error in categories: " . $e->getMessage());
                }
                
                foreach ($categories as $category):
                    // Determine image source with better fallback
                    $image_src = 'assets/images/placeholder-category.jpg'; // Default placeholder
                    if (!empty($category['image'])) {
                        if (strpos($category['image'], 'uploads/') === 0) {
                            $image_src = $category['image'];
                        } elseif (strpos($category['image'], 'assets/') === 0) {
                            $image_src = $category['image'];
                        } else {
                            $image_src = 'assets/images/' . $category['image'];
                        }
                    }
                    
                    // Category-specific default images based on category name
                    if ($image_src === 'assets/images/placeholder-category.jpg') {
                        $category_lower = strtolower($category['name']);
                        if (strpos($category_lower, 'dress') !== false) {
                            $image_src = 'assets/images/fashion-model-in-pink.jpg';
                        } elseif (strpos($category_lower, 'men') !== false) {
                            $image_src = 'assets/images/istockphoto-1293366109-612x612.jpg';
                        } elseif (strpos($category_lower, 'accessories') !== false) {
                            $image_src = 'assets/images/istockphoto-531786318-612x612.jpg';
                        }
                    }
                ?>
                <div class="category-item">
                    <div class="category-image">
                        <img src="<?php echo htmlspecialchars($image_src); ?>" 
                             alt="<?php echo htmlspecialchars($category['name']); ?>"
                             loading="lazy"
                             onerror="this.src='assets/images/placeholder-category.jpg'; this.style.display='block';">
                        <div class="category-overlay">
                            <div class="category-content">
                                <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                                <p><?php echo htmlspecialchars($category['description'] ?: 'Discover our ' . $category['name'] . ' collection'); ?></p>
                                <a href="products.php?category=<?php echo urlencode($category['slug']); ?>" class="btn btn-outline">Shop <?php echo htmlspecialchars($category['name']); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($categories)): ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No categories available at the moment.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'featured_products.php'; ?>

   

    <?php include 'includes/footer.php'; ?>

    <!-- Search Overlay Styles -->
    <style>
    /* Navigation Search Form Styles */
    .nav-search-form {
        position: relative;
        display: flex;
        align-items: center;
        margin-left: 1rem;
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid transparent;
        border-radius: 25px;
        padding: 8px 12px;
        transition: all 0.3s ease;
        width: 40px;
        overflow: hidden;
    }

    .search-input-wrapper.expanded {
        width: 250px;
        background: rgba(255, 255, 255, 0.95);
        border-color: #d4af37;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .search-input-wrapper:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(212, 175, 55, 0.5);
    }

    .nav-search-input {
        border: none;
        outline: none;
        background: transparent;
        color: #333;
        font-size: 14px;
        width: 0;
        padding: 0;
        transition: all 0.3s ease;
        opacity: 0;
    }

    .search-input-wrapper.expanded .nav-search-input {
        width: 180px;
        padding: 0 10px;
        opacity: 1;
    }

    .nav-search-input::placeholder {
        color: #666;
        font-style: italic;
    }

    .nav-search-btn {
        background: none;
        border: none;
        color: #d4af37;
        font-size: 16px;
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .nav-search-btn:hover {
        color: #b8941f;
        transform: scale(1.1);
    }

    .search-input-wrapper.expanded .nav-search-btn {
        color: #d4af37;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .nav-search-form {
            display: none;
        }
    }

    /* Search Overlay Styles */
    .search-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .search-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .search-content {
        width: 100%;
        max-width: 800px;
        position: relative;
    }

    .search-form {
        display: flex;
        align-items: center;
        background: white;
        border-radius: 50px;
        padding: 8px;
        margin-bottom: 2rem;
    }

    .search-input {
        flex: 1;
        border: none;
        outline: none;
        padding: 15px 25px;
        font-size: 1.1rem;
        background: transparent;
    }

    .search-submit {
        background: #d4af37;
        border: none;
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-submit:hover {
        background: #b8941f;
    }

    .search-close {
        position: absolute;
        top: -60px;
        right: 0;
        background: none;
        border: none;
        color: white;
        font-size: 2rem;
        cursor: pointer;
    }

    .search-suggestions {
        background: white;
        border-radius: 12px;
        max-height: 400px;
        overflow-y: auto;
        display: none;
    }

    .search-suggestion {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-suggestion:hover {
        background: #f8f9fa;
    }

    .search-suggestion:last-child {
        border-bottom: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .search-content {
            padding: 0 20px;
        }

        .search-form {
            margin-bottom: 1rem;
        }

        .search-input {
            padding: 12px 20px;
            font-size: 1rem;
        }

        .search-submit {
            width: 45px;
            height: 45px;
        }
    }
    </style>

    <!-- Search Overlay JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search overlay functionality
        const searchToggle = document.getElementById('search-toggle');
        const searchOverlay = document.getElementById('search-overlay');
        const searchClose = document.getElementById('search-close');
        const searchInput = document.querySelector('.search-input');
        
        if (searchToggle && searchOverlay) {
            searchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                searchOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            });
        }
        
        if (searchClose) {
            searchClose.addEventListener('click', function() {
                searchOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
        
        // Close search overlay with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchOverlay && searchOverlay.classList.contains('active')) {
                searchOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        // Close search overlay when clicking outside
        if (searchOverlay) {
            searchOverlay.addEventListener('click', function(e) {
                if (e.target === searchOverlay) {
                    searchOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }
        
        // Search suggestions (basic implementation)
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                const suggestions = document.getElementById('search-suggestions');
                
                if (query.length > 2 && suggestions) {
                    // Here you could implement AJAX search suggestions
                    // For now, just show the suggestions container
                    suggestions.style.display = 'block';
                    suggestions.innerHTML = '<div style="padding: 1rem; color: #666;">Type to search products...</div>';
                } else if (suggestions) {
                    suggestions.style.display = 'none';
                }
            });
        }
        
        // Navigation Search Form Functionality
        const navSearchInput = document.querySelector('.nav-search-input');
        const searchWrapper = document.querySelector('.search-input-wrapper');
        const navSearchBtn = document.querySelector('.nav-search-btn');
        
        if (navSearchInput && searchWrapper) {
            // Expand search input on focus
            navSearchInput.addEventListener('focus', function() {
                searchWrapper.classList.add('expanded');
            });
            
            // Contract search input on blur (only if empty)
            navSearchInput.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    searchWrapper.classList.remove('expanded');
                }
            });
            
            // Keep expanded if there's a value
            navSearchInput.addEventListener('input', function() {
                if (this.value.trim()) {
                    searchWrapper.classList.add('expanded');
                } else {
                    // Only contract if not focused
                    if (document.activeElement !== this) {
                        searchWrapper.classList.remove('expanded');
                    }
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
    });
    </script>

    <!-- JavaScript -->
    <script src="assets/js/performance-optimizer.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
