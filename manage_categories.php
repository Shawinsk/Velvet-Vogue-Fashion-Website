<?php
require_once 'includes/admin_auth.php';
require_once '../includes/db_connect.php';

// Require admin authentication
requireAdminAuth();

// Initialize variables
$category_id = $category_name = $category_description = $category_slug = $category_image = '';
$error = $success = '';
$current_image = '';

// Pagination settings
$items_per_page = 10;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Search functionality
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_condition = '';
$search_params = [];

if (!empty($search_term)) {
    $search_condition = "WHERE c.name LIKE ? OR c.description LIKE ?";
    $search_params = ["%{$search_term}%", "%{$search_term}%"];
}

// Sorting functionality
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'name';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'asc';

// Validate sort parameters
$allowed_sort_fields = ['id', 'name', 'product_count'];
$allowed_sort_orders = ['asc', 'desc'];

if (!in_array($sort_by, $allowed_sort_fields)) {
    $sort_by = 'name';
}

if (!in_array($sort_order, $allowed_sort_orders)) {
    $sort_order = 'asc';
}

// Handle form submission for adding/editing categories
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        // Get form data
        $category_id = $_POST['category_id'] ?? '';
        $category_name = $_POST['category_name'] ?? '';
        $category_description = $_POST['category_description'] ?? '';
        $category_slug = $_POST['category_slug'] ?? '';
        
        // Generate slug if empty
        if (empty($category_slug)) {
            $category_slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $category_name), '-'));
        }
        
        // Validate form data
        if (empty($category_name)) {
            $error = "Please enter a category name.";
        } else {
            // Handle image upload
            $image_path = '';
            $upload_success = true;
            
            if (!empty($_FILES['category_image']['name'])) {
                $target_dir = "../assets/images/categories/";
                
                // Create directory if it doesn't exist
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $file_extension = strtolower(pathinfo($_FILES["category_image"]["name"], PATHINFO_EXTENSION));
                $new_filename = $category_slug . '-' . time() . '.' . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                // Check file type
                $allowed_types = ["jpg", "jpeg", "png", "gif", "webp"];
                if (!in_array($file_extension, $allowed_types)) {
                    $error = "Sorry, only JPG, JPEG, PNG, GIF, and WEBP files are allowed.";
                    $upload_success = false;
                }
                
                // Check file size (max 2MB)
                if ($_FILES["category_image"]["size"] > 2000000) {
                    $error = "Sorry, your file is too large. Maximum size is 2MB.";
                    $upload_success = false;
                }
                
                if ($upload_success) {
                    if (move_uploaded_file($_FILES["category_image"]["tmp_name"], $target_file)) {
                        $image_path = $new_filename;
                    } else {
                        $error = "Sorry, there was an error uploading your file.";
                        $upload_success = false;
                    }
                }
            }
            
            if (empty($error)) {
                // Add or update category
                if ($_POST['action'] === 'add') {
                    // Check if category name or slug already exists
                    $check_stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ? OR slug = ?");
                    $check_stmt->execute([$category_name, $category_slug]);
                    $check_result = $check_stmt->fetch();
                    
                    if ($check_result) {
                        $error = "Category name or slug already exists.";
                    } else {
                        // Add new category
                        if (!empty($image_path)) {
                            $stmt = $pdo->prepare("INSERT INTO categories (name, description, slug, image) VALUES (?, ?, ?, ?)");
                            $stmt->execute([$category_name, $category_description, $category_slug, $image_path]);
                        } else {
                            $stmt = $pdo->prepare("INSERT INTO categories (name, description, slug) VALUES (?, ?, ?)");
                            $stmt->execute([$category_name, $category_description, $category_slug]);
                        }
                        $success = "Category added successfully!";
                        // Clear form fields after successful submission
                        $category_id = $category_name = $category_description = $category_slug = $category_image = '';
                    }
                } else if ($_POST['action'] === 'edit' && $category_id) {
                    // Check if category name or slug already exists for other categories
                    $check_stmt = $pdo->prepare("SELECT id FROM categories WHERE (name = ? OR slug = ?) AND id != ?");
                    $check_stmt->execute([$category_name, $category_slug, $category_id]);
                    $check_result = $check_stmt->fetch();
                    
                    if ($check_result) {
                        $error = "Category name or slug already exists for another category.";
                    } else {
                        // Update category
                        if (!empty($image_path)) {
                            // Delete old image if exists
                            if (!empty($_POST['current_image'])) {
                                $old_image_path = "../assets/images/categories/" . $_POST['current_image'];
                                if (file_exists($old_image_path)) {
                                    unlink($old_image_path);
                                }
                            }
                            
                            $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ?, slug = ?, image = ? WHERE id = ?");
                            $stmt->execute([$category_name, $category_description, $category_slug, $image_path, $category_id]);
                        } else {
                            $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ?, slug = ? WHERE id = ?");
                            $stmt->execute([$category_name, $category_description, $category_slug, $category_id]);
                        }
                        $success = "Category updated successfully!";
                    }
                }
            }
        }
    } else if (isset($_POST['delete']) && !empty($_POST['delete_id'])) {
        // Delete category
        $delete_id = $_POST['delete_id'];
        
        // Check if category has products
        $check_stmt = $pdo->prepare("SELECT COUNT(*) as product_count FROM products WHERE category_id = ?");
        $check_stmt->execute([$delete_id]);
        $check_result = $check_stmt->fetch();
        $product_count = $check_result['product_count'];
        
        if ($product_count > 0) {
            $error = "Cannot delete category. It has {$product_count} products associated with it.";
        } else {
            // Get category image before deleting
            $img_stmt = $pdo->prepare("SELECT image FROM categories WHERE id = ?");
            $img_stmt->execute([$delete_id]);
            $category_img = $img_stmt->fetch();
            
            // Delete category image if exists
            if ($category_img && !empty($category_img['image'])) {
                $image_path = "../assets/images/categories/" . $category_img['image'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            
            $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$delete_id]);
            $success = "Category deleted successfully!";
        }
    }
}

// Handle edit request
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$edit_id]);
    $category = $stmt->fetch();
    
    if ($category) {
        $category_id = $category['id'];
        $category_name = $category['name'];
        $category_description = $category['description'];
        $category_slug = $category['slug'] ?? '';
        $current_image = $category['image'] ?? '';
    }
}

// Count total categories for pagination
$count_query = "SELECT COUNT(*) as total FROM categories c $search_condition";
$count_stmt = $pdo->prepare($count_query);

if (!empty($search_params)) {
    $count_stmt->execute($search_params);
} else {
    $count_stmt->execute();
}

$total_categories = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_categories / $items_per_page);

// Get categories with pagination, search, and sorting
$categories_query = "SELECT c.*, COUNT(p.id) as product_count 
                   FROM categories c 
                   LEFT JOIN products p ON c.id = p.category_id 
                   $search_condition
                   GROUP BY c.id 
                   ORDER BY c.$sort_by $sort_order
                   LIMIT $offset, $items_per_page";

$categories_stmt = $pdo->prepare($categories_query);

if (!empty($search_params)) {
    $categories_stmt->execute($search_params);
} else {
    $categories_stmt->execute();
}

$categories_result = $categories_stmt;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Velvet Vogue Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* --- Modern, premium, glassmorphism-inspired admin UI for manage_categories.php --- */
        body.admin-body {
            background: linear-gradient(135deg, #e3f0ff 0%, #f7f7fa 100%);
        }
        
        /* Enhanced Sidebar Styles */
        .admin-sidebar {
            background: linear-gradient(135deg, #8B4513 0%, #D4AF37 100%);
            box-shadow: 0 8px 32px rgba(44, 24, 16, 0.37);
            backdrop-filter: blur(4px);
            border-right: 1px solid rgba(255, 255, 255, 0.18);
            transition: all 0.3s ease;
            padding: 20px 0;
            position: fixed;
            overflow-y: auto;
            overflow-x: hidden;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        
        .admin-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgcGF0dGVyblRyYW5zZm9ybT0icm90YXRlKDQ1KSI+PGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMSIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==');
            opacity: 0.5;
            z-index: 0;
        }
        
        .admin-sidebar-header {
            padding: 15px 25px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .admin-logo {
            font-size: 24px;
            font-weight: 800;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 0.5px;
            position: relative;
            padding: 5px 0;
        }
        
        .admin-logo i {
            color: #D4AF37;
            font-size: 22px;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.3));
            transition: transform 0.3s ease;
        }
        
        .admin-logo:hover i {
            transform: rotate(15deg) scale(1.1);
        }
        
        .admin-logo::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #D4AF37, transparent);
            transition: width 0.5s ease;
        }
        
        .admin-logo:hover::after {
            width: 100%;
        }
        
        .admin-user-info {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            margin: 0 15px 25px 15px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .admin-user-info:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .admin-user-info::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            opacity: 0;
            transform: scale(0.5);
            transition: opacity 0.5s, transform 0.5s;
        }
        
        .admin-user-info:hover::after {
            opacity: 1;
            transform: scale(1);
        }
        
        .admin-avatar {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .admin-avatar i {
            font-size: 20px;
            color: white;
        }
        
        .admin-user-details {
            flex: 1;
        }
        
        .admin-username {
            font-weight: 700;
            font-size: 16px;
            color: white;
            margin-bottom: 4px;
        }
        
        .admin-role {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .admin-role i {
            color: #FFD700;
            font-size: 11px;
        }
        
        .admin-nav {
            margin-top: 10px;
        }
        
        .admin-nav-item {
            margin: 8px 0;
        }
        
        .admin-nav-link {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            font-weight: 500;
        }
        
        .admin-nav-link i {
            margin-right: 15px;
            font-size: 18px;
            width: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .admin-nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: rgba(255, 255, 255, 0.5);
        }
        
        .admin-nav-link:hover i {
            transform: translateX(3px);
        }
        
        .admin-nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: #FFD700;
            font-weight: 600;
        }
        
        .admin-nav-link.active i {
            color: #FFD700;
            transform: translateX(3px);
        }
        
        .admin-nav-item {
            position: relative;
        }
        
        .admin-nav-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 25px;
            right: 25px;
            height: 1px;
            background: rgba(255, 255, 255, 0.05);
        }
        
        .admin-nav-item:last-child::after,
        .logout-item::after {
            display: none;
        }
        
        .logout-item {
            margin-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 15px;
        }
        
        .logout-item .admin-nav-link {
            color: #ff6b6b;
        }
        
        .logout-item .admin-nav-link:hover {
            background: rgba(255, 99, 99, 0.1);
            border-left-color: #ff6b6b;
        }
        .admin-main {
            background: linear-gradient(135deg, #e3f0ff 0%, #f7f7fa 100%);
            margin-left: 250px; /* Width of the sidebar */
            width: calc(100% - 250px);
            transition: all 0.3s ease;
        }
        .admin-card {
            background: rgba(255,255,255,0.92);
            border-radius: 22px;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.13);
            backdrop-filter: blur(6px);
            border: 1.5px solid rgba(255,255,255,0.22);
            margin-bottom: 36px;
            padding-bottom: 12px;
            transition: box-shadow 0.2s, border 0.2s;
        }
        .admin-card:hover {
            box-shadow: 0 12px 42px 0 rgba(31,38,135,0.17);
            border: 1.5px solid rgba(255,255,255,0.35);
        }
        .admin-card-header {
            border-radius: 22px 22px 0 0;
            padding: 22px 36px;
            box-shadow: 0 2px 8px rgba(44, 24, 16, 0.08);
            background: linear-gradient(90deg, #f9f5e8 0%, #f7f7fa 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .admin-card-title {
            font-size: 25px;
            font-weight: 800;
            color: #8B4513;
            letter-spacing: 0.7px;
            text-shadow: 0 1px 4px #f9f5e8;
            margin-top: 10px; /* Add space above the title */
        }
        
        /* Specific styling for search card title */
        .admin-card:nth-of-type(1) .admin-card-title {
            margin-top: 25px;
            margin-bottom: 15px;
            position: relative;
            top: 10px;
        }
        .admin-card-body {
            padding: 24px 36px;
        }
        .admin-btn-primary {
            background: linear-gradient(90deg, #8B4513 0%, #D4AF37 100%);
            color: #fff;
            border: 1.5px solid #f9f5e8;
            border-radius: 10px;
            padding: 12px 28px;
            font-weight: 700;
            font-size: 17px;
            box-shadow: 0 4px 16px rgba(139, 69, 19, 0.18);
            transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
        }
        .admin-btn-primary:hover {
            background: linear-gradient(90deg, #D4AF37 0%, #8B4513 100%);
            box-shadow: 0 8px 24px rgba(139, 69, 19, 0.22);
            transform: translateY(-2px) scale(1.03);
        }
        .admin-btn-light {
            background: #f7f7fa;
            color: #333;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            padding: 10px 24px;
            font-weight: 500;
            font-size: 16px;
            transition: background 0.2s, border 0.2s;
        }
        .admin-btn-light:hover {
            background: #f9f5e8;
            border: 1.5px solid #8B4513;
        }
        .admin-btn-sm {
            padding: 8px 16px;
            font-size: 14px;
        }
        .admin-table {
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.10), 0 1.5px 8px 0 rgba(45,140,240,0.08);
            border-radius: 18px;
            overflow: hidden;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(2.5px);
            border: 1.5px solid #f9f5e8;
            min-width: 800px;
        }
        .admin-table th, .admin-table td {
            border-bottom: 1px solid #f0f4fa;
            vertical-align: middle;
            text-align: center;
            padding: 16px 12px;
        }
        .admin-table th {
            background: linear-gradient(90deg, #f9f5e8 0%, #f7f7fa 100%);
            color: #8B4513;
            font-weight: 700;
            border-bottom: 2px solid #e0e0e0;
            font-size: 16px;
            letter-spacing: 0.2px;
        }
        .admin-table td {
            background: rgba(255,255,255,0.92);
            font-size: 15px;
            transition: background 0.2s;
        }
        .admin-table tr {
            transition: background 0.2s, box-shadow 0.2s;
            background: rgba(255,255,255,0.85);
            box-shadow: 0 2px 8px rgba(31,38,135,0.04);
        }
        .admin-table tr:hover {
            background: #faf8f0;
            box-shadow: 0 4px 16px rgba(139, 69, 19, 0.10);
        }
        .admin-table-action {
            border: none;
            background: none;
            font-size: 18px;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 6px;
            transition: background 0.18s, color 0.18s, box-shadow 0.18s;
        }
        .admin-table-action.edit:hover { background: #f9f5e8; color: #8B4513; }
        .admin-table-action.delete:hover { background: #ffeaea; color: #e74c3c; }
        .admin-table-action.view:hover { background: #e6f9e7; color: #27ae60; }
        .admin-table .admin-badge {
            font-size: 13px;
            padding: 3px 12px;
            border-radius: 14px;
            font-weight: 500;
            box-shadow: 0 1px 4px rgba(45,140,240,0.04);
        }
        .admin-badge-success { background: #e6f9e7; color: #27ae60; }
        .admin-badge-warning { background: #fffbe6; color: #f39c12; }
        .admin-badge-danger { background: #ffeaea; color: #e74c3c; }
        .admin-badge-secondary { background: #f0f0f0; color: #888; }
        .admin-table-actions { display: flex; gap: 8px; justify-content: center; }
        .admin-table-responsive, .admin-table-wrapper { overflow-x: auto; }
        .admin-form-group label { font-weight: 500; color: #444; }
        .admin-form-control, .admin-form-textarea {
            border: 1.5px solid #e0e0e0;
            border-radius: 7px;
            padding: 12px 16px;
            font-size: 15px;
            width: 100%;
            background: rgba(255,255,255,0.7);
            margin-bottom: 16px;
            box-shadow: 0 1px 4px rgba(45,140,240,0.04);
            transition: border 0.2s, box-shadow 0.2s;
        }
        .admin-form-control:focus, .admin-form-textarea:focus {
            border: 1.5px solid #8B4513;
            box-shadow: 0 2px 8px rgba(139, 69, 19, 0.10);
            outline: none;
        }
        .admin-form-label {
            margin-bottom: 8px;
            display: block;
            font-weight: 600;
            color: #333;
        }
        .admin-form-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 24px;
        }
        .admin-header-actions {
            display: flex;
            gap: 10px;
        }
        .admin-btn {
            cursor: pointer;
        }
        .admin-table-empty, .admin-text-center {
            text-align: center;
            color: #aaa;
            font-size: 18px;
            padding: 32px 0;
        }
        .admin-search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
        }
        .admin-search-form .admin-form-control {
            margin-bottom: 0;
            flex-grow: 1;
        }
        .admin-pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        .admin-pagination-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border-radius: 8px;
            background: #fff;
            border: 1.5px solid #e0e0e0;
            color: #333;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }
        .admin-pagination-item:hover {
            background: #f9f5e8;
            border-color: #8B4513;
        }
        .admin-pagination-item.active {
            background: #8B4513;
            border-color: #8B4513;
            color: #fff;
        }
        .admin-pagination-item.disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        .admin-alert {
            padding: 16px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: opacity 0.5s;
        }
        .admin-alert-success {
            background: #e6f9e7;
            color: #27ae60;
            border-left: 4px solid #27ae60;
        }
        .admin-alert-danger {
            background: #ffeaea;
            color: #e74c3c;
            border-left: 4px solid #e74c3c;
        }
        .admin-alert i {
            font-size: 20px;
        }
        .admin-form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 10px;
        }
        .admin-form-col {
            flex: 1;
        }
        .admin-form-help {
            font-size: 13px;
            color: #777;
            margin-top: 4px;
        }
        .admin-image-preview {
            width: 100%;
            max-width: 200px;
            height: auto;
            border-radius: 10px;
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 2px solid #f9f5e8;
        }
        .admin-image-preview-container {
            margin-bottom: 16px;
        }
        .admin-sort-icon {
            margin-left: 5px;
            font-size: 12px;
        }
        .admin-sort-link {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .admin-sort-link:hover {
            color: #8B4513;
        }
        .admin-category-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 2px solid #f9f5e8;
        }
        .admin-category-image-placeholder {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f4fa;
            border-radius: 8px;
            color: #aaa;
            font-size: 20px;
        }
        /* Responsive tweaks for mobile */
        @media (max-width: 900px) {
            .admin-main {
                background: #f7f7fa;
                margin-left: 0;
                width: 100%;
                padding-top: 60px;
            }
            .admin-sidebar {
                width: 100%;
                height: 60px;
                overflow: hidden;
                padding: 0;
            }
            .admin-sidebar-header {
                padding: 10px 15px;
                margin-bottom: 0;
                display: flex;
                justify-content: center;
            }
            .admin-logo {
                font-size: 18px;
            }
            .admin-user-info, .admin-nav {
                display: none;
            }
            .admin-table {
                min-width: 600px;
            }
            .admin-form-row {
                flex-direction: column;
                gap: 0;
            }
            .admin-card-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            .admin-search-form {
                flex-direction: column;
                width: 100%;
            }
        }
    </style>
</head>
<body class="admin-body">
    <div class="admin-wrapper" style="overflow-x: hidden;">
        <?php include __DIR__ . '/includes/admin_sidebar.php'; ?>
        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1 class="admin-page-title">Manage Categories</h1>
                <div class="admin-header-actions">
                    <button class="admin-btn admin-btn-primary" id="showAddCategoryForm">
                        <i class="fas fa-plus"></i> Add New Category
                    </button>
                </div>
            </header>
            
            <?php if (!empty($error)): ?>
            <div class="admin-alert admin-alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
            <div class="admin-alert admin-alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success; ?>
            </div>
            <?php endif; ?>
            
            <!-- Search and Filter -->
            <div class="admin-card">
                <div class="admin-card-header" style="padding-bottom: 2px;">
                    <h3 class="admin-card-title" style="padding-bottom: 5px; margin-bottom: 50px; margin-top: 50px;"><i class="fas fa-search"></i> Search Categories</h3>
                </div>
                <div class="admin-card-body">
                    <form method="GET" action="" class="admin-search-form">
                        <input type="text" name="search" class="admin-form-control" placeholder="Search by name or description..." value="<?php echo htmlspecialchars($search_term); ?>">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <?php if (!empty($search_term)): ?>
                        <a href="manage_categories.php" class="admin-btn admin-btn-light">
                            <i class="fas fa-times"></i> Clear
                        </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            
            <!-- Category Form -->
            <div class="admin-card" id="categoryForm" style="<?php echo (empty($category_id) && !isset($_GET['add'])) ? 'display: none;' : ''; ?>">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <i class="fas <?php echo empty($category_id) ? 'fa-plus-circle' : 'fa-edit'; ?>"></i>
                        <?php echo empty($category_id) ? 'Add New Category' : 'Edit Category'; ?>
                    </h3>
                </div>
                <div class="admin-card-body">
                    <form method="POST" action="" class="admin-form" enctype="multipart/form-data">
                        <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($category_id); ?>">
                        <input type="hidden" name="action" value="<?php echo empty($category_id) ? 'add' : 'edit'; ?>">
                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($current_image); ?>">
                        
                        <div class="admin-form-row">
                            <div class="admin-form-col">
                                <div class="admin-form-group">
                                    <label for="category_name" class="admin-form-label">Category Name *</label>
                                    <input type="text" id="category_name" name="category_name" class="admin-form-control" value="<?php echo htmlspecialchars($category_name); ?>" required>
                                </div>
                            </div>
                            <div class="admin-form-col">
                                <div class="admin-form-group">
                                    <label for="category_slug" class="admin-form-label">Slug</label>
                                    <input type="text" id="category_slug" name="category_slug" class="admin-form-control" value="<?php echo htmlspecialchars($category_slug); ?>" placeholder="auto-generated-if-empty">
                                    <div class="admin-form-help">Leave empty to auto-generate from name. Used in URLs.</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="category_description" class="admin-form-label">Description</label>
                            <textarea id="category_description" name="category_description" class="admin-form-control admin-form-textarea" rows="4"><?php echo htmlspecialchars($category_description); ?></textarea>
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="category_image" class="admin-form-label">Category Image</label>
                            <input type="file" id="category_image" name="category_image" class="admin-form-control" accept="image/*">
                            <div class="admin-form-help">Recommended size: 600x400px. Max size: 2MB. Formats: JPG, PNG, GIF, WEBP</div>
                            
                            <?php if (!empty($current_image)): ?>
                            <div class="admin-image-preview-container">
                                <p>Current Image:</p>
                                <img src="../assets/images/categories/<?php echo htmlspecialchars($current_image); ?>" alt="Category Image" class="admin-image-preview">
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="admin-form-actions">
                            <button type="submit" class="admin-btn admin-btn-primary">
                                <i class="fas fa-save"></i> <?php echo empty($category_id) ? 'Add Category' : 'Update Category'; ?>
                            </button>
                            <a href="manage_categories.php" class="admin-btn admin-btn-light">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Categories List -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <i class="fas fa-list"></i> All Categories
                        <?php if (!empty($search_term)): ?>
                        <small>(Search results for: "<?php echo htmlspecialchars($search_term); ?>")</small>
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>
                                    <a href="?sort_by=id&sort_order=<?php echo $sort_by === 'id' && $sort_order === 'asc' ? 'desc' : 'asc'; ?><?php echo !empty($search_term) ? '&search=' . urlencode($search_term) : ''; ?>" class="admin-sort-link">
                                        ID
                                        <?php if ($sort_by === 'id'): ?>
                                            <i class="fas fa-sort-<?php echo $sort_order === 'asc' ? 'up' : 'down'; ?> admin-sort-icon"></i>
                                        <?php else: ?>
                                            <i class="fas fa-sort admin-sort-icon"></i>
                                        <?php endif; ?>
                                    </a>
                                </th>
                                <th>Image</th>
                                <th>
                                    <a href="?sort_by=name&sort_order=<?php echo $sort_by === 'name' && $sort_order === 'asc' ? 'desc' : 'asc'; ?><?php echo !empty($search_term) ? '&search=' . urlencode($search_term) : ''; ?>" class="admin-sort-link">
                                        Name
                                        <?php if ($sort_by === 'name'): ?>
                                            <i class="fas fa-sort-<?php echo $sort_order === 'asc' ? 'up' : 'down'; ?> admin-sort-icon"></i>
                                        <?php else: ?>
                                            <i class="fas fa-sort admin-sort-icon"></i>
                                        <?php endif; ?>
                                    </a>
                                </th>
                                <th>Description</th>
                                <th>Slug</th>
                                <th>
                                    <a href="?sort_by=product_count&sort_order=<?php echo $sort_by === 'product_count' && $sort_order === 'asc' ? 'desc' : 'asc'; ?><?php echo !empty($search_term) ? '&search=' . urlencode($search_term) : ''; ?>" class="admin-sort-link">
                                        Products
                                        <?php if ($sort_by === 'product_count'): ?>
                                            <i class="fas fa-sort-<?php echo $sort_order === 'asc' ? 'up' : 'down'; ?> admin-sort-icon"></i>
                                        <?php else: ?>
                                            <i class="fas fa-sort admin-sort-icon"></i>
                                        <?php endif; ?>
                                    </a>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($categories_result && $categories_result->rowCount() > 0): ?>
                                <?php while ($category = $categories_result->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td><?php echo $category['id']; ?></td>
                                        <td>
                                            <?php if (!empty($category['image'])): ?>
                                                <img src="../assets/images/categories/<?php echo htmlspecialchars($category['image']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" class="admin-category-image">
                                            <?php else: ?>
                                                <div class="admin-category-image-placeholder">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                                        <td><?php echo htmlspecialchars($category['description'] ?? 'No description'); ?></td>
                                        <td><?php echo htmlspecialchars($category['slug'] ?? 'N/A'); ?></td>
                                        <td>
                                            <span class="admin-badge <?php echo $category['product_count'] > 0 ? 'admin-badge-success' : 'admin-badge-secondary'; ?>">
                                                <?php echo $category['product_count']; ?> product<?php echo $category['product_count'] !== 1 ? 's' : ''; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="admin-table-actions">
                                                <a href="manage_categories.php?edit=<?php echo $category['id']; ?>" class="admin-table-action edit" title="Edit Category">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($category['product_count'] > 0): ?>
                                                    <a href="manage_products.php?category=<?php echo $category['id']; ?>" class="admin-table-action view" title="View Products">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <form method="POST" action="" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                    <input type="hidden" name="delete_id" value="<?php echo $category['id']; ?>">
                                                    <button type="submit" name="delete" class="admin-table-action delete" title="Delete Category" <?php echo $category['product_count'] > 0 ? 'disabled' : ''; ?>>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="admin-text-center">
                                        <?php if (!empty($search_term)): ?>
                                            <i class="fas fa-search fa-2x" style="margin-bottom: 10px; color: #ccc;"></i><br>
                                            No categories found matching "<?php echo htmlspecialchars($search_term); ?>".
                                            <br><br>
                                            <a href="manage_categories.php" class="admin-btn admin-btn-light admin-btn-sm">
                                                <i class="fas fa-arrow-left"></i> Back to All Categories
                                            </a>
                                        <?php else: ?>
                                            <i class="fas fa-tag fa-2x" style="margin-bottom: 10px; color: #ccc;"></i><br>
                                            No categories found. Create your first category!
                                            <br><br>
                                            <button class="admin-btn admin-btn-primary admin-btn-sm" id="showAddCategoryFormEmpty">
                                                <i class="fas fa-plus"></i> Add New Category
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="admin-pagination">
                    <?php if ($current_page > 1): ?>
                        <a href="?page=<?php echo $current_page - 1; ?><?php echo !empty($search_term) ? '&search=' . urlencode($search_term) : ''; ?><?php echo !empty($sort_by) ? '&sort_by=' . $sort_by . '&sort_order=' . $sort_order : ''; ?>" class="admin-pagination-item">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php else: ?>
                        <span class="admin-pagination-item disabled"><i class="fas fa-chevron-left"></i></span>
                    <?php endif; ?>
                    
                    <?php
                    // Calculate range of page numbers to display
                    $range = 2; // Number of pages to show before and after current page
                    $start_page = max(1, $current_page - $range);
                    $end_page = min($total_pages, $current_page + $range);
                    
                    // Always show first page
                    if ($start_page > 1) {
                        echo '<a href="?page=1' . (!empty($search_term) ? '&search=' . urlencode($search_term) : '') . (!empty($sort_by) ? '&sort_by=' . $sort_by . '&sort_order=' . $sort_order : '') . '" class="admin-pagination-item">1</a>';
                        if ($start_page > 2) {
                            echo '<span class="admin-pagination-item disabled">...</span>';
                        }
                    }
                    
                    // Display page numbers
                    for ($i = $start_page; $i <= $end_page; $i++) {
                        $active_class = ($i === $current_page) ? 'active' : '';
                        echo '<a href="?page=' . $i . (!empty($search_term) ? '&search=' . urlencode($search_term) : '') . (!empty($sort_by) ? '&sort_by=' . $sort_by . '&sort_order=' . $sort_order : '') . '" class="admin-pagination-item ' . $active_class . '">' . $i . '</a>';
                    }
                    
                    // Always show last page
                    if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) {
                            echo '<span class="admin-pagination-item disabled">...</span>';
                        }
                        echo '<a href="?page=' . $total_pages . (!empty($search_term) ? '&search=' . urlencode($search_term) : '') . (!empty($sort_by) ? '&sort_by=' . $sort_by . '&sort_order=' . $sort_order : '') . '" class="admin-pagination-item">' . $total_pages . '</a>';
                    }
                    ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <a href="?page=<?php echo $current_page + 1; ?><?php echo !empty($search_term) ? '&search=' . urlencode($search_term) : ''; ?><?php echo !empty($sort_by) ? '&sort_by=' . $sort_by . '&sort_order=' . $sort_order : ''; ?>" class="admin-pagination-item">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <span class="admin-pagination-item disabled"><i class="fas fa-chevron-right"></i></span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Category Stats -->
                <div class="admin-card-body">
                    <div style="text-align: center; color: #777; font-size: 14px;">
                        Showing <?php echo $categories_result->rowCount(); ?> of <?php echo $total_categories; ?> categories
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show/hide category form
            const showAddCategoryFormBtn = document.getElementById('showAddCategoryForm');
            const categoryForm = document.getElementById('categoryForm');
            const emptyStateButton = document.getElementById('showAddCategoryFormEmpty');
            
            function showAddCategoryForm() {
                // Reset form if it's being used for editing
                const form = categoryForm.querySelector('form');
                if (form) {
                    form.reset();
                    const actionInput = form.querySelector('input[name="action"]');
                    if (actionInput) actionInput.value = 'add';
                    
                    const categoryIdInput = form.querySelector('input[name="category_id"]');
                    if (categoryIdInput) categoryIdInput.value = '';
                    
                    const categorySlugInput = form.querySelector('input[name="category_slug"]');
                    if (categorySlugInput) {
                        categorySlugInput.value = '';
                        categorySlugInput.dataset.userEdited = 'false';
                    }
                    
                    // Reset image preview if exists
                    const imagePreview = form.querySelector('.admin-image-preview-container');
                    if (imagePreview) {
                        imagePreview.style.display = 'none';
                    }
                    
                    // Update form title
                    const formTitle = categoryForm.querySelector('.admin-card-title');
                    if (formTitle) {
                        formTitle.innerHTML = '<i class="fas fa-plus-circle"></i> Add New Category';
                    }
                    
                    // Update submit button text
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-save"></i> Add Category';
                    }
                }
                
                categoryForm.style.display = 'block';
                window.scrollTo({ top: categoryForm.offsetTop - 20, behavior: 'smooth' });
            }
            
            if (showAddCategoryFormBtn) {
                showAddCategoryFormBtn.addEventListener('click', showAddCategoryForm);
            }
            
            if (emptyStateButton) {
                emptyStateButton.addEventListener('click', showAddCategoryForm);
            }
            
            // Auto-generate slug from category name
            const categoryNameInput = document.getElementById('category_name');
            const categorySlugInput = document.getElementById('category_slug');
            
            if (categoryNameInput && categorySlugInput) {
                categoryNameInput.addEventListener('input', function() {
                    // Only auto-generate if slug field is empty or hasn't been manually edited
                    if (categorySlugInput.dataset.userEdited !== 'true') {
                        const slug = this.value.toLowerCase()
                            .replace(/[^a-z0-9\s-]/g, '') // Remove special chars except spaces and hyphens
                            .replace(/\s+/g, '-')         // Replace spaces with hyphens
                            .replace(/-+/g, '-')          // Replace multiple hyphens with single hyphen
                            .trim();
                        categorySlugInput.value = slug;
                    }
                });
                
                // Mark slug as user-edited when user types in it
                categorySlugInput.addEventListener('input', function() {
                    this.dataset.userEdited = 'true';
                });
                
                // Reset user-edited flag when form is reset
                const cancelBtn = document.querySelector('#categoryForm a.admin-btn-light');
                if (cancelBtn) {
                    cancelBtn.addEventListener('click', function() {
                        categorySlugInput.dataset.userEdited = 'false';
                    });
                }
            }
            
            // Image preview functionality
            const categoryImageInput = document.getElementById('category_image');
            if (categoryImageInput) {
                categoryImageInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        // Check file type
                        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                        if (!validTypes.includes(file.type)) {
                            alert('Invalid file type. Please upload JPG, PNG, GIF, or WEBP images only.');
                            this.value = '';
                            return;
                        }
                        
                        // Check file size (max 2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('File size exceeds 2MB. Please upload a smaller image.');
                            this.value = '';
                            return;
                        }
                        
                        // Create or update preview container
                        let previewContainer = document.querySelector('.admin-image-preview-container');
                        if (!previewContainer) {
                            previewContainer = document.createElement('div');
                            previewContainer.className = 'admin-image-preview-container';
                            previewContainer.innerHTML = '<p>New Image Preview:</p>';
                            const img = document.createElement('img');
                            img.className = 'admin-image-preview';
                            img.alt = 'Category Image Preview';
                            previewContainer.appendChild(img);
                            this.parentNode.appendChild(previewContainer);
                        } else {
                            previewContainer.style.display = 'block';
                            let img = previewContainer.querySelector('img');
                            if (!img) {
                                img = document.createElement('img');
                                img.className = 'admin-image-preview';
                                img.alt = 'Category Image Preview';
                                previewContainer.appendChild(img);
                            }
                            const previewText = previewContainer.querySelector('p');
                            if (previewText) previewText.textContent = 'New Image Preview:';
                        }
                        
                        // Show preview
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = previewContainer.querySelector('img');
                            if (img) img.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.admin-alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });
            
            // Add smooth scrolling to pagination links
            document.querySelectorAll('.admin-pagination-item').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    // Only apply to actual links, not disabled items
                    if (!this.classList.contains('disabled') && this.getAttribute('href')) {
                        // Don't prevent default as we want the page to navigate
                        // But scroll to top smoothly before navigation
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
            });
        });
    </script>
</body>
</html>