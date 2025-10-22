<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once 'db_connect.php';

// Get cart count for logged in users
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $result = $stmt->fetch();
        $cart_count = $result['total'] ?? 0;
    } catch (Exception $e) {
        $cart_count = 0;
    }
}

// Get wishlist count for logged in users
$wishlist_count = 0;
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM wishlist WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $result = $stmt->fetch();
        $wishlist_count = $result['total'] ?? 0;
    } catch (Exception $e) {
        $wishlist_count = 0;
    }
}

// Handle category filtering
$category_filter = isset($_GET['category']) ? trim($_GET['category']) : '';
$search_filter = isset($_GET['search']) ? trim($_GET['search']) : '';

// Load all products (no pagination)
$products_per_page = 1000; // Large number to load all products
$current_page = 1;
$offset = 0;

// Build the products query with filters
$where_conditions = ["p.status = 'active'"];
$params = [];

if (!empty($category_filter)) {
    $where_conditions[] = "c.slug = ?";
    $params[] = $category_filter;
}

if (!empty($search_filter)) {
    $where_conditions[] = "(p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?)";
    $search_term = '%' . $search_filter . '%';
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count for pagination
try {
    $count_query = "
        SELECT COUNT(*) as total
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE $where_clause
    ";
    
    $stmt = $pdo->prepare($count_query);
    $stmt->execute($params);
    $total_products = $stmt->fetch()['total'];
    $total_pages = ceil($total_products / $products_per_page);
} catch (Exception $e) {
    $total_products = 0;
    $total_pages = 0;
}

// Get products with category information and pagination
try {
    $products_query = "
        SELECT 
            p.*,
            c.name as category_name,
            c.slug as category_slug,
            (SELECT image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) as primary_image
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE $where_clause
        ORDER BY p.created_at DESC
        LIMIT $products_per_page OFFSET $offset
    ";
    
    $stmt = $pdo->prepare($products_query);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    $products = [];
}

// Get current category name for display
$current_category_name = 'All Products';
if (!empty($category_filter)) {
    try {
        $stmt = $pdo->prepare("SELECT name FROM categories WHERE slug = ?");
        $stmt->execute([$category_filter]);
        $category_result = $stmt->fetch();
        if ($category_result) {
            $current_category_name = $category_result['name'];
        }
    } catch (Exception $e) {
        // Keep default name
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Velvet Vogue</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #faf9f7;
            color: #2c2c2c;
            line-height: 1.6;
        }

        /* Header Styles */
        .header {
            background: #fff;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c2c2c;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .logo:hover {
            color: #d4af37;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 40px;
        }

        .nav-menu a {
            text-decoration: none;
            color: #2c2c2c;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-menu a:hover {
            color: #d4af37;
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #d4af37;
            transition: width 0.3s ease;
        }

        .nav-menu a:hover::after {
            width: 100%;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-actions a, .header-actions button {
            color: #2c2c2c;
            font-size: 1.1rem;
            transition: color 0.3s ease;
            text-decoration: none;
            position: relative;
          
            border: none;
            cursor: pointer;
        }

        .header-actions a:hover, .header-actions button:hover {
            color: #d4af37;
        }

        /* Enhanced Login Button */
        .login-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #d4af37, #f4d03f);
            color: white !important;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(212, 175, 55, 0.3);
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #b8941f, #d4af37);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
            color: white !important;
        }

        .login-text {
            font-size: 0.9rem;
        }

        /* User Menu */
        .user-menu {
            position: relative;
        }

        .user-account-link {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #2c2c2c;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: rgba(212, 175, 55, 0.1);
            font-weight: 500;
        }

        .user-account-link:hover {
            color: #d4af37;
            background: rgba(212, 175, 55, 0.2);
            transform: translateY(-1px);
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1), 0 5px 15px rgba(0,0,0,0.07);
            padding: 8px 0;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .user-dropdown::before {
            content: '';
            position: absolute;
            top: -6px;
            right: 20px;
            width: 12px;
            height: 12px;
            background: white;
            border: 1px solid rgba(212, 175, 55, 0.1);
            border-bottom: none;
            border-right: none;
            transform: rotate(45deg);
        }

        .user-menu:hover .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #2c2c2c;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.95rem;
            border-left: 3px solid transparent;
        }

        .user-dropdown a:hover {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(212, 175, 55, 0.05));
            color: #d4af37;
            border-left-color: #d4af37;
            transform: translateX(2px);
        }

        .user-dropdown a i {
            width: 16px;
            text-align: center;
            color: #d4af37;
        }

        /* Active navigation link */
        .nav-menu a.active {
            color: #d4af37;
        }

        .nav-menu a.active::after {
            width: 100%;
        }

        /* Cart and Wishlist counters */
        .cart-count, .wishlist-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #d4af37;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Mobile menu toggle */
        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            gap: 3px;
            padding: 5px;
            cursor: pointer;
            z-index: 1001;
        }

        .mobile-menu-toggle span {
            width: 20px;
            height: 2px;
            background: #2c2c2c;
            transition: all 0.3s ease;
            transform-origin: center;
        }

        .mobile-menu-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .mobile-menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        /* Search Overlay */
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

        .nav-search-btn:hover {
            color: #d4af37;
        }

        /* Navigation Search Form Styles */
        .nav-search-form {
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

        /* Body scroll lock when menu is open */
        body.menu-open {
            overflow: hidden;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-search-form {
                display: none;
            }

            .nav-menu {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 80px);
                background: white;
                flex-direction: column;
                padding: 40px 20px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                transition: left 0.3s ease;
                z-index: 1000;
                overflow-y: auto;
            }

            .nav-menu.active {
                left: 0;
            }

            .nav-menu li {
                margin: 10px 0;
            }

            .nav-menu a {
                font-size: 1.2rem;
                padding: 15px 0;
                display: block;
                border-bottom: 1px solid #f0f0f0;
            }

            .mobile-menu-toggle {
                display: flex;
            }

            .header-actions {
                gap: 15px;
            }

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

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%);
            color: white;
            padding: 120px 0 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(212,175,55,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(212,175,55,0.15)"/><circle cx="40" cy="70" r="1" fill="rgba(212,175,55,0.2)"/><circle cx="70" cy="80" r="2.5" fill="rgba(212,175,55,0.1)"/></svg>');
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .breadcrumb {
            margin-bottom: 30px;
            opacity: 0.8;
        }

        .breadcrumb a {
            color: #d4af37;
            text-decoration: none;
        }

        .breadcrumb .separator {
            margin: 0 10px;
            opacity: 0.6;
        }

        .nav-user-welcome {
            margin: 20px 0;
            text-align: center;
        }

        .nav-welcome-message {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(244, 228, 166, 0.2));
            border: 2px solid rgba(212, 175, 55, 0.3);
            color: #2c2c2c;
            padding: 12px 25px;
            border-radius: 20px;
            display: inline-block;
            font-size: 1rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.2);
            transition: all 0.3s ease;
        }

        .nav-welcome-message:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.3);
            border-color: rgba(212, 175, 55, 0.5);
        }

        .nav-welcome-message i {
            margin-right: 8px;
            color: #d4af37;
            font-size: 1.1rem;
        }

        .nav-welcome-text strong {
            color: #d4af37;
            font-weight: 600;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff 0%, #d4af37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .user-welcome {
            margin: 20px 0;
            text-align: center;
        }

        .welcome-message {
            background: linear-gradient(135deg, #d4af37, #f4e4a6);
            color: #2c2c2c;
            padding: 15px 30px;
            border-radius: 25px;
            display: inline-block;
            font-size: 1.1rem;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
            margin-bottom: 15px;
            animation: welcomeGlow 2s ease-in-out infinite alternate;
        }

        .welcome-message i {
            margin-right: 8px;
            color: #2c2c2c;
        }

        .welcome-message strong {
            color: #1a1a1a;
        }

        .welcome-subtext {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-left: 5px;
        }

        @keyframes welcomeGlow {
            0% {
                box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
            }
            100% {
                box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5);
            }
        }

        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .header-stats {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 40px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            display: block;
            font-size: 2.5rem;
            font-weight: 700;
            color: #d4af37;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Main Content */
        .main-content {
            padding: 60px 0;
        }

        .content-wrapper {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 40px;
            align-items: start;
        }

        /* Filter Sidebar */
        .filter-sidebar {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: sticky;
            top: 100px;
            height: fit-content;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
        }

        /* Custom scrollbar for filter sidebar */
        .filter-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .filter-sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .filter-sidebar::-webkit-scrollbar-thumb {
            background: #d4af37;
            border-radius: 3px;
        }

        .filter-sidebar::-webkit-scrollbar-thumb:hover {
            background: #b8941f;
        }

        .sidebar-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .sidebar-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #2c2c2c;
        }

        .mobile-filter-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .filter-section {
            margin-bottom: 30px;
        }

        .filter-section h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c2c2c;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: #d4af37;
        }

        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .filter-option {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-option:hover {
            background: #f8f8f8;
            padding: 5px;
            border-radius: 5px;
        }

        .filter-option input {
            margin-right: 10px;
        }

        .count {
            margin-left: auto;
            font-size: 0.8rem;
            color: #666;
        }

        .price-inputs {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 15px;
        }

        .price-input {
            flex: 1;
        }

        .price-input label {
            display: block;
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 5px;
        }

        .price-input input {
            width: 100%;
            padding: 8px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .price-separator {
            padding: 0 10px;
            font-weight: 500;
            color: #666;
        }

        .price-presets {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .price-preset {
            padding: 6px 12px;
            background: #f8f8f8;
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .price-preset:hover {
            background: #d4af37;
            color: white;
            border-color: #d4af37;
        }

        .color-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .color-option {
            cursor: pointer;
        }

        .color-swatch {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: block;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .color-option input:checked + .color-swatch {
            border-color: #d4af37;
            box-shadow: 0 0 0 2px rgba(212,175,55,0.3);
        }

        .size-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .size-option {
            cursor: pointer;
        }

        .size-label {
            display: block;
            padding: 8px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            font-size: 0.9rem;
            text-align: center;
            min-width: 40px;
            transition: all 0.3s ease;
        }

        .size-option input:checked + .size-label {
            background: #d4af37;
            color: white;
            border-color: #d4af37;
        }

        .sort-select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.9rem;
            background: white;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .apply-filters-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 12px 24px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            font-family: var(--font-secondary);
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .apply-filters-btn:hover {
            background-color: var(--accent-color);
            transform: translateY(-2px);
        }

        .clear-filters-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 12px 24px;
            background-color: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: 4px;
            font-family: var(--font-secondary);
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .clear-filters-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* Products Content */
        .products-content {
            min-height: 100vh;
        }

        .mobile-filter-header {
            display: none;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .mobile-filter-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 12px 24px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            font-family: var(--font-secondary);
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .mobile-filter-btn:hover {
            background-color: var(--accent-color);
            transform: translateY(-2px);
        }

        .products-header {
            margin-bottom: 30px;
        }

        .products-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #2c2c2c;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .product-image {
            position: relative;
            overflow: hidden;
            height: 300px;
            background: #f8f8f8;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .no-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #666;
        }

        .no-image i {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .sale-badge, .featured-badge {
            position: absolute;
            top: 15px;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
            z-index: 2;
        }

        .sale-badge {
            background: #e74c3c;
            left: 15px;
        }

        .featured-badge {
            background: #d4af37;
            right: 15px;
        }

        .featured-badge {
            background: #d4af37;
        }

        .product-info {
            padding: 25px;
        }

        .product-category {
            margin-bottom: 8px;
        }

        .product-category a {
            color: #d4af37;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .product-name {
            margin-bottom: 10px;
        }

        .product-name a {
            color: #2c2c2c;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .product-name a:hover {
            color: #d4af37;
        }

        .product-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .product-price {
            margin-bottom: 20px;
        }

        .sale-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #e74c3c;
            margin-right: 10px;
        }

        .original-price {
            font-size: 1.1rem;
            color: #666;
            text-decoration: line-through;
        }

        .price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c2c2c;
        }

        .product-actions {
            display: flex;
            gap: 10px;
        }

        .add-to-cart-btn {
            flex: 1;
            padding: 12px;
            background: #d4af37;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background: #b8941f;
            transform: translateY(-2px);
        }

        .out-of-stock-btn {
            flex: 1;
            padding: 12px;
            background: #ccc;
            color: #666;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: not-allowed;
        }

        .wishlist-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            background-color: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .wishlist-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 50px;
        }

        .pagination-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            background-color: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: 4px;
            font-family: var(--font-secondary);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }

        .pagination-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .pagination-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .pagination-dots {
            display: flex;
            align-items: center;
            padding: 0 10px;
            color: #999;
            font-weight: bold;
        }

        /* Scroll to top button */
        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: #d4af37;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            z-index: 1000;
        }

        .scroll-to-top.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .scroll-to-top:hover {
            background: #b8941f;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
        }

        .scroll-to-top i {
            font-size: 1.2rem;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .header-container {
                height: 60px;
            }

            .page-header {
                padding: 80px 0 40px;
            }

            .page-title {
                font-size: 2.5rem;
            }

            .header-stats {
                flex-direction: column;
                gap: 20px;
            }

            .content-wrapper {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .filter-sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 80%;
                height: 100vh;
                overflow-y: auto;
                z-index: 1001;
                transition: left 0.3s ease;
            }

            .filter-sidebar.active {
                left: 0;
            }

            .mobile-filter-toggle {
                display: block;
            }

            .mobile-filter-header {
                display: flex;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }

            .pagination {
                flex-wrap: wrap;
            }
        }
    </style>
    
    <script>
        // Update cart and wishlist counts from PHP
        document.addEventListener('DOMContentLoaded', function() {
            // Update cart count
            const cartCount = <?php echo $cart_count; ?>;
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = cartCount;
                cartCountElement.style.display = cartCount > 0 ? 'flex' : 'none';
            }
            
            // Update wishlist count
            const wishlistCount = <?php echo $wishlist_count; ?>;
            const wishlistCountElement = document.getElementById('wishlist-count');
            if (wishlistCountElement) {
                wishlistCountElement.textContent = wishlistCount;
                wishlistCountElement.style.display = wishlistCount > 0 ? 'flex' : 'none';
            }
            
            // Search overlay functionality
            const searchToggle = document.getElementById('search-toggle');
            const searchOverlay = document.getElementById('search-overlay');
            const searchClose = document.getElementById('search-close');
            const searchInput = document.querySelector('.search-input');
            
            if (searchToggle && searchOverlay) {
                searchToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    searchOverlay.classList.add('active');
                    if (searchInput) {
                        setTimeout(() => searchInput.focus(), 100);
                    }
                });
            }
            
            if (searchClose && searchOverlay) {
                searchClose.addEventListener('click', function() {
                    searchOverlay.classList.remove('active');
                });
            }
            
            // Close search overlay on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && searchOverlay && searchOverlay.classList.contains('active')) {
                    searchOverlay.classList.remove('active');
                }
            });
            
            // Close search overlay when clicking outside
            if (searchOverlay) {
                searchOverlay.addEventListener('click', function(e) {
                    if (e.target === searchOverlay) {
                        searchOverlay.classList.remove('active');
                    }
                });
            }
            
            // Mobile menu functionality
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const navMenu = document.querySelector('.nav-menu');
            
            if (mobileMenuToggle && navMenu) {
                mobileMenuToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('active');
                    mobileMenuToggle.classList.toggle('active');
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
        });
        
        // Function to update cart count (can be called from other scripts)
        function updateCartCount(count) {
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                cartCountElement.style.display = count > 0 ? 'flex' : 'none';
            }
        }
        
        // Function to update wishlist count (can be called from other scripts)
        function updateWishlistCount(count) {
            const wishlistCountElement = document.getElementById('wishlist-count');
            if (wishlistCountElement) {
                wishlistCountElement.textContent = count;
                wishlistCountElement.style.display = count > 0 ? 'flex' : 'none';
            }
        }
    </script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <a href="index.php" class="logo">Velvet Vogue</a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="products.php?category=womens-collection">Women</a></li>
                    <li><a href="products.php?category=mens-collection">Men</a></li>
                    <li><a href="products.php?category=accessories">Accessories</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <!-- Inline Search Form -->
                <form class="nav-search-form" action="products.php" method="GET">
                    <div class="search-input-wrapper">
                        <input type="text" name="search" placeholder="Search products..." class="nav-search-input" autocomplete="off" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="nav-search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <!-- User Account -->
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="user-menu">
                    <a href="account.php" class="user-account-link" title="My Account">
                        <i class="fas fa-user"></i>
                        <span class="user-name">Hi, <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'User'); ?></span>
                    </a>
                    <div class="user-dropdown">
                        <a href="account.php"><i class="fas fa-user-circle"></i> My Account</a>
                        <a href="cart.php"><i class="fas fa-shopping-bag"></i> My Orders</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
                <?php else: ?>
                    <a href="account_login.php" class="login-btn" title="Login">
                        <i class="fas fa-sign-in-alt"></i>
                        <span class="login-text">Login</span>
                    </a>
                <?php endif; ?>
                
                <!-- Wishlist -->
                <a href="wishlist.php" title="Wishlist" class="wishlist-link">
                    <i class="fas fa-heart"></i>
                    <span class="wishlist-count" id="wishlist-count">0</span>
                </a>
                
                <!-- Shopping Cart -->
                <a href="cart.php" title="Shopping Cart" class="cart-link" id="cart-toggle">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-count" id="cart-count">0</span>
                </a>
                
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" id="mobile-menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
        
        <!-- Search Overlay -->
        <div class="search-overlay" id="search-overlay">
            <div class="search-content">
                <div class="container">
                    <form class="search-form" action="products.php" method="GET">
                        <input type="text" name="search" placeholder="Search for products..." class="search-input" autocomplete="off" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
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
        </div>        <!-- Search Overlay -->
        <div class="search-overlay" id="search-overlay">
            <div class="search-content">
                <div class="container">
                    <form class="search-form" action="products.php" method="GET">
                        <input type="text" name="search" placeholder="Search for products..." class="search-input" autocomplete="off" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
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
    </header>

    <main class="products-page">
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <nav class="breadcrumb">
                    <a href="index.php"><i class="fas fa-home"></i> Home</a>
                    <span class="separator"><i class="fas fa-chevron-right"></i></span>
                    <span class="current">Products</span>
                </nav>
                <?php if(isset($_SESSION['user_id']) && isset($_SESSION['first_name'])): ?>
                   
                <?php endif; ?>
                <div class="header-content">
                    <h1 class="page-title"><?php echo htmlspecialchars($current_category_name); ?></h1>
                    <?php if(isset($_SESSION['user_id']) && isset($_SESSION['first_name'])): ?>
                        <div class="user-welcome">
                            <p class="welcome-message">
                                <i class="fas fa-user-circle"></i>
                                Welcome back, <strong><?php echo htmlspecialchars($_SESSION['first_name']); ?></strong>! 
                                <span class="welcome-subtext">Happy shopping!</span>
                            </p>
                        </div>
                    <?php endif; ?>
                    <p class="page-subtitle">
                        <?php if (!empty($search_filter)): ?>
                            Search results for "<?php echo htmlspecialchars($search_filter); ?>"
                        <?php elseif (!empty($category_filter)): ?>
                            Discover our curated selection of <?php echo strtolower(htmlspecialchars($current_category_name)); ?>
                        <?php else: ?>
                            Discover timeless elegance and contemporary style in our curated selection of premium fashion
                        <?php endif; ?>
                    </p>
                    <div class="header-stats">
                        <?php
                        // Get actual product count
                        try {
                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM products WHERE status = 'active'");
                            $product_count = $stmt->fetch()['total'] ?? 0;
                        } catch (Exception $e) {
                            $product_count = 0;
                        }
                        
                        // Get actual category count
                        try {
                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM categories WHERE status = 'active'");
                            $category_count = $stmt->fetch()['total'] ?? 0;
                        } catch (Exception $e) {
                            $category_count = 0;
                        }
                        ?>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $total_products; ?></span>
                            <span class="stat-label">Products</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $category_count; ?></span>
                            <span class="stat-label">Categories</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">100%</span>
                            <span class="stat-label">Quality</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <div class="main-content">
            <div class="container">
                <div class="content-wrapper">
                    <!-- Filter Sidebar -->
                    <aside class="filter-sidebar">
                        <div class="sidebar-header">
                            <h3><i class="fas fa-filter"></i> Filters</h3>
                            <button class="mobile-filter-toggle">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <form class="filters-form">
                            <!-- Search Filter -->
                            <div class="filter-section">
                                <h4>Search</h4>
                                <div class="search-box">
                                    <input type="text" placeholder="Search products...">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                            
                            <!-- Category Filter -->
                            <div class="filter-section">
                                <h4>Categories</h4>
                                <div class="category-list">
                                    <label class="filter-option">
                                        <input type="radio" name="category" value="" checked>
                                        <span>All Categories</span>
                                        <span class="count">(156)</span>
                                    </label>
                                    <label class="filter-option">
                                        <input type="radio" name="category" value="dresses">
                                        <span>Dresses</span>
                                        <span class="count">(32)</span>
                                    </label>
                                    <label class="filter-option">
                                        <input type="radio" name="category" value="tops">
                                        <span>Tops</span>
                                        <span class="count">(28)</span>
                                    </label>
                                    <label class="filter-option">
                                        <input type="radio" name="category" value="bottoms">
                                        <span>Bottoms</span>
                                        <span class="count">(24)</span>
                                    </label>
                                    <label class="filter-option">
                                        <input type="radio" name="category" value="accessories">
                                        <span>Accessories</span>
                                        <span class="count">(18)</span>
                                    </label>
                                    <label class="filter-option">
                                        <input type="radio" name="category" value="shoes">
                                        <span>Shoes</span>
                                        <span class="count">(22)</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Price Range Filter -->
                            <div class="filter-section">
                                <h4>Price Range</h4>
                                <div class="price-range">
                                    <div class="price-inputs">
                                        <div class="price-input">
                                            <label>Min</label>
                                            <input type="number" value="0" min="0" step="0.01">
                                        </div>
                                        <div class="price-separator">-</div>
                                        <div class="price-input">
                                            <label>Max</label>
                                            <input type="number" value="1000" min="0" step="0.01">
                                        </div>
                                    </div>
                                    <div class="price-presets">
                                        <button type="button" class="price-preset">Under LKR 50</button>
                                        <button type="button" class="price-preset">LKR 50 - LKR 100</button>
                                        <button type="button" class="price-preset">LKR 100 - LKR 200</button>
                                        <button type="button" class="price-preset">Over LKR 200</button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Color Filter -->
                            <div class="filter-section">
                                <h4>Colors</h4>
                                <div class="color-options">
                                    <label class="color-option" title="White">
                                        <input type="checkbox" name="colors[]" value="white">
                                        <span class="color-swatch" style="background-color: #ffffff;"></span>
                                    </label>
                                    <label class="color-option" title="Brown">
                                        <input type="checkbox" name="colors[]" value="brown">
                                        <span class="color-swatch" style="background-color: #8B4513;"></span>
                                    </label>
                                    <label class="color-option" title="Navy">
                                        <input type="checkbox" name="colors[]" value="navy">
                                        <span class="color-swatch" style="background-color: #000080;"></span>
                                    </label>
                                    <label class="color-option" title="Gray">
                                        <input type="checkbox" name="colors[]" value="gray">
                                        <span class="color-swatch" style="background-color: #808080;"></span>
                                    </label>
                                    <label class="color-option" title="Red">
                                        <input type="checkbox" name="colors[]" value="red">
                                        <span class="color-swatch" style="background-color: #DC143C;"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Size Filter -->
                            <div class="filter-section">
                                <h4>Sizes</h4>
                                <div class="size-options">
                                    <label class="size-option">
                                        <input type="checkbox" name="sizes[]" value="xl">
                                        <span class="size-label">XL</span>
                                    </label>
                                    <label class="size-option">
                                        <input type="checkbox" name="sizes[]" value="xxl">
                                        <span class="size-label">XXL</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Sort Filter -->
                            <div class="filter-section">
                                <h4>Sort By</h4>
                                <select class="sort-select">
                                    <option value="name">Name (A-Z)</option>
                                    <option value="price_low">Price: Low to High</option>
                                    <option value="price_high">Price: High to Low</option>
                                    <option value="newest">Newest First</option>
                                    <option value="featured">Featured</option>
                                    <option value="popular">Most Popular</option>
                                </select>
                            </div>
                            
                            <!-- Filter Actions -->
                            <div class="filter-actions">
                                <button type="submit" class="apply-filters-btn">
                                    <i class="fas fa-check"></i> Apply Filters
                                </button>
                                <a href="#" class="clear-filters-btn">
                                    <i class="fas fa-times"></i> Clear All
                                </a>
                            </div>
                        </form>
                    </aside>
                    
                    <!-- Products Content -->
                    <main class="products-content">
                        <!-- Mobile Filter Toggle -->
                        <div class="mobile-filter-header">
                            <button class="mobile-filter-btn">
                                <i class="fas fa-filter"></i> Filters
                            </button>
                            <div class="results-count">
                                <span><?php echo $total_products; ?> Products Found</span>
                            </div>
                        </div>
                        
                        <!-- Products Header -->
                        <div class="products-header">
                            <h2><?php echo htmlspecialchars($current_category_name); ?> (<?php echo $total_products; ?> items)</h2>
                        </div>
                        
                        <!-- Products Grid -->
                        <div class="products-grid">
                            <?php if ($total_products > 0): ?>
                                <?php foreach ($products as $product): ?>
                                    <div class="product-card">
                                <div class="product-image">
                                    <a href="product_detail.php?id=<?php echo $product['id']; ?>">
                                        <?php if (!empty($product['primary_image'])): ?>
                                            <img src="<?php echo htmlspecialchars($product['primary_image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                        <?php else: ?>
                                            <div class="no-image">
                                                <i class="fas fa-image"></i>
                                                <span><?php echo htmlspecialchars($product['name']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                    
                                    <?php if ($product['is_featured']): ?>
                                        <span class="featured-badge">Featured</span>
                                    <?php endif; ?>
                                    
                                    <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                                        <span class="sale-badge">Sale</span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">
                                        <a href="products.php?category=<?php echo urlencode($product['category_slug']); ?>">
                                            <?php echo htmlspecialchars($product['category_name']); ?>
                                        </a>
                                    </div>
                                    <h3 class="product-name">
                                        <a href="product_detail.php?id=<?php echo $product['id']; ?>">
                                            <?php echo htmlspecialchars($product['name']); ?>
                                        </a>
                                    </h3>
                                    <p class="product-description">
                                        <?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?>
                                    </p>
                                    <div class="product-price">
                                        <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                                            <span class="sale-price">LKR <?php echo number_format($product['sale_price'], 2); ?></span>
                                            <span class="original-price">LKR <?php echo number_format($product['price'], 2); ?></span>
                                        <?php else: ?>
                                            <span class="price">LKR <?php echo number_format($product['price'], 2); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-actions">
                                        <button class="wishlist-btn" data-product-id="<?php echo $product['id']; ?>">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-products">
                                    <i class="fas fa-search"></i>
                                    <h3>No products found</h3>
                                    <p>Try adjusting your search criteria or browse our categories.</p>
                                </div>
                            <?php endif; ?>

                            

                        </div>
                        
                        <!-- Scroll to top button -->
        <div class="scroll-to-top" id="scroll-to-top">
            <i class="fas fa-chevron-up"></i>
        </div>
                    </main>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Initialize cart and wishlist counts from PHP
        let cartCount = <?php echo $cart_count; ?>;
        let wishlistCount = <?php echo $wishlist_count; ?>;
        
        // Update display counters
        function updateCounters() {
            const cartCountElement = document.getElementById('cart-count');
            const wishlistCountElement = document.getElementById('wishlist-count');
            
            if (cartCountElement) {
                cartCountElement.textContent = cartCount;
                cartCountElement.style.display = cartCount > 0 ? 'flex' : 'none';
            }
            
            if (wishlistCountElement) {
                wishlistCountElement.textContent = wishlistCount;
                wishlistCountElement.style.display = wishlistCount > 0 ? 'flex' : 'none';
            }
        }
        
        // Initialize counters on page load
        updateCounters();
        
        // Navigation button click feedback
        const navLinks = document.querySelectorAll('.nav-menu a');
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // Add visual feedback for navigation clicks
                link.style.transform = 'scale(0.95)';
                link.style.transition = 'transform 0.1s ease';
                
                // Reset transform after animation
                setTimeout(() => {
                    link.style.transform = 'scale(1)';
                }, 100);
                
                // Show loading indicator for category links
                if (link.href.includes('category=')) {
                    const originalText = link.textContent;
                    link.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + originalText;
                    
                    // Reset after navigation (this will be interrupted by page load)
                    setTimeout(() => {
                        link.textContent = originalText;
                    }, 1000);
                }
            });
        });
        
        // Search Overlay Functionality
        const searchToggle = document.getElementById('search-toggle');
        const searchOverlay = document.getElementById('search-overlay');
        const searchClose = document.getElementById('search-close');
        const searchInput = document.querySelector('.search-input');
        
        if (searchToggle) {
            searchToggle.addEventListener('click', (e) => {
                e.preventDefault();
                searchOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    if (searchInput) searchInput.focus();
                }, 300);
            });
        }
        
        if (searchClose) {
            searchClose.addEventListener('click', () => {
                searchOverlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        }
        
        // Close search overlay with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                searchOverlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
        
        // Close search overlay when clicking outside
        if (searchOverlay) {
            searchOverlay.addEventListener('click', (e) => {
                if (e.target === searchOverlay) {
                    searchOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        }
        
        // Navigation search form functionality
        const navSearchInput = document.querySelector('.nav-search-input');
        const searchInputWrapper = document.querySelector('.search-input-wrapper');
        const navSearchBtn = document.querySelector('.nav-search-btn');
        
        if (navSearchInput && searchInputWrapper) {
            // Expand search input on focus
            navSearchInput.addEventListener('focus', function() {
                searchInputWrapper.classList.add('expanded');
            });
            
            // Contract search input on blur (only if empty)
            navSearchInput.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    searchInputWrapper.classList.remove('expanded');
                }
            });
            
            // Keep expanded if there's a value
            navSearchInput.addEventListener('input', function() {
                if (this.value.trim()) {
                    searchInputWrapper.classList.add('expanded');
                } else {
                    // Only contract if not focused
                    if (document.activeElement !== this) {
                        searchInputWrapper.classList.remove('expanded');
                    }
                }
            });
            
            // Handle search button click
            if (navSearchBtn) {
                navSearchBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!searchInputWrapper.classList.contains('expanded')) {
                        searchInputWrapper.classList.add('expanded');
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
        
        // Mobile Menu Functionality
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const navMenu = document.querySelector('.nav-menu');
        
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', () => {
                mobileMenuToggle.classList.toggle('active');
                navMenu.classList.toggle('active');
                document.body.classList.toggle('menu-open');
            });
        }
        
        // Mobile filter toggle
        const mobileFilterBtn = document.querySelector('.mobile-filter-btn');
        const mobileFilterToggle = document.querySelector('.mobile-filter-toggle');
        const filterSidebar = document.querySelector('.filter-sidebar');

        if (mobileFilterBtn) {
            mobileFilterBtn.addEventListener('click', () => {
                filterSidebar.classList.add('active');
            });
        }

        if (mobileFilterToggle) {
            mobileFilterToggle.addEventListener('click', () => {
                filterSidebar.classList.remove('active');
            });
        }

        // Price preset buttons
        const pricePresets = document.querySelectorAll('.price-preset');
        const minPriceInput = document.querySelector('input[type="number"]:first-of-type');
        const maxPriceInput = document.querySelector('input[type="number"]:last-of-type');

        pricePresets.forEach(preset => {
            preset.addEventListener('click', () => {
                const min = preset.getAttribute('data-min');
                const max = preset.getAttribute('data-max');
                
                if (minPriceInput && min) minPriceInput.value = min;
                if (maxPriceInput && max) maxPriceInput.value = max;
                
                // Remove active class from all presets
                pricePresets.forEach(p => p.classList.remove('active'));
                // Add active class to clicked preset
                preset.classList.add('active');
            });
        });



        // Wishlist functionality
        const wishlistBtns = document.querySelectorAll('.wishlist-btn');
        wishlistBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const icon = btn.querySelector('i');
                
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    btn.style.color = '#e74c3c';
                    btn.style.borderColor = '#e74c3c';
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    btn.style.color = '#666';
                    btn.style.borderColor = '#e0e0e0';
                }
            });
        });

        // Smooth scrolling for pagination
        const paginationBtns = document.querySelectorAll('.pagination-btn');
        paginationBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Remove active class from all buttons
                paginationBtns.forEach(b => b.classList.remove('active'));
                
                // Add active class to clicked button (if it's a number)
                if (!isNaN(btn.textContent)) {
                    btn.classList.add('active');
                }
                
                // Scroll to top of products
                document.querySelector('.products-header').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Auto-apply filters on input change
        const filterInputs = document.querySelectorAll('.filters-form input, .filters-form select');
        filterInputs.forEach(input => {
            input.addEventListener('change', () => {
                // Add subtle animation to indicate filter is being applied
                const productsGrid = document.querySelector('.products-grid');
                if (productsGrid) {
                    productsGrid.style.opacity = '0.7';
                    productsGrid.style.transform = 'translateY(10px)';
                    
                    setTimeout(() => {
                        productsGrid.style.opacity = '1';
                        productsGrid.style.transform = 'translateY(0)';
                    }, 300);
                }
            });
        });

        // Product card hover effects
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-10px)';
                card.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';
            });
        });

        // Scroll to top functionality
        const scrollToTopBtn = document.getElementById('scroll-to-top');
        
        // Show/hide scroll to top button based on scroll position
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('visible');
            } else {
                scrollToTopBtn.classList.remove('visible');
            }
        });
        
        // Smooth scroll to top when button is clicked
        if (scrollToTopBtn) {
            scrollToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
        
        // Smooth scrolling for all products (infinite scroll effect)
        document.documentElement.style.scrollBehavior = 'smooth';
    </script>
</body>
</html>