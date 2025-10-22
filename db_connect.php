<?php
/**
 * Velvet Vogue - Database Connection
 * 
 * This file establishes connection to the MySQL database
 * and provides error handling for database operations.
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$db_config = [
    'host' => 'localhost',
    'dbname' => 'velvet_vogue',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];

try {
    // Create PDO connection
    $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset={$db_config['charset']}";
    $pdo = new PDO($dsn, $db_config['username'], $db_config['password'], $db_config['options']);
    
    // Set additional PDO attributes for better security and performance
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
    
} catch (PDOException $e) {
    // Log error (in production, you should log to a file instead of displaying)
    error_log("Database Connection Error: " . $e->getMessage());
    
    // Show user-friendly error message
    die("We're experiencing technical difficulties. Please try again later.");
}

/**
 * Create database tables if they don't exist
 * This function should be called during initial setup
 */
function createDatabaseTables($pdo) {
    try {
        // Users table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(100) NOT NULL,
                last_name VARCHAR(100) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                phone VARCHAR(20),
                date_of_birth DATE,
                gender ENUM('male', 'female', 'other'),
                is_admin BOOLEAN DEFAULT FALSE,
                email_verified BOOLEAN DEFAULT FALSE,
                verification_token VARCHAR(255),
                reset_token VARCHAR(255),
                reset_token_expiry DATETIME,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email (email),
                INDEX idx_verification_token (verification_token),
                INDEX idx_reset_token (reset_token)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Categories table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                slug VARCHAR(100) UNIQUE NOT NULL,
                description TEXT,
                image VARCHAR(255),
                parent_id INT DEFAULT NULL,
                sort_order INT DEFAULT 0,
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
                INDEX idx_slug (slug),
                INDEX idx_parent_id (parent_id),
                INDEX idx_is_active (is_active)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Products table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                slug VARCHAR(255) UNIQUE NOT NULL,
                description TEXT,
                short_description VARCHAR(500),
                sku VARCHAR(100) UNIQUE NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                sale_price DECIMAL(10,2) DEFAULT NULL,
                cost_price DECIMAL(10,2) DEFAULT NULL,
                stock_quantity INT DEFAULT 0,
                manage_stock BOOLEAN DEFAULT TRUE,
                in_stock BOOLEAN DEFAULT TRUE,
                weight DECIMAL(8,2) DEFAULT NULL,
                dimensions VARCHAR(100),
                category_id INT,
                brand VARCHAR(100),
                tags TEXT,
                featured BOOLEAN DEFAULT FALSE,
                status ENUM('active', 'inactive', 'draft') DEFAULT 'active',
                meta_title VARCHAR(255),
                meta_description VARCHAR(500),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
                INDEX idx_slug (slug),
                INDEX idx_sku (sku),
                INDEX idx_category_id (category_id),
                INDEX idx_status (status),
                INDEX idx_featured (featured),
                INDEX idx_in_stock (in_stock),
                FULLTEXT idx_search (name, description, tags)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Product images table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS product_images (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                image_path VARCHAR(255) NOT NULL,
                alt_text VARCHAR(255),
                is_primary BOOLEAN DEFAULT FALSE,
                sort_order INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                INDEX idx_product_id (product_id),
                INDEX idx_is_primary (is_primary)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Product attributes table (for size, color, etc.)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS product_attributes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                attribute_name VARCHAR(100) NOT NULL,
                attribute_value VARCHAR(255) NOT NULL,
                price_modifier DECIMAL(10,2) DEFAULT 0,
                stock_quantity INT DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                INDEX idx_product_id (product_id),
                INDEX idx_attribute_name (attribute_name)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Shopping cart table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS cart (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT DEFAULT NULL,
                session_id VARCHAR(255) NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL DEFAULT 1,
                attributes JSON DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                INDEX idx_user_id (user_id),
                INDEX idx_session_id (session_id),
                INDEX idx_product_id (product_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Wishlist table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS wishlist (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                product_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                UNIQUE KEY unique_wishlist (user_id, product_id),
                INDEX idx_user_id (user_id),
                INDEX idx_product_id (product_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Orders table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT DEFAULT NULL,
                order_number VARCHAR(50) UNIQUE NOT NULL,
                status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending',
                payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
                payment_method VARCHAR(50),
                subtotal DECIMAL(10,2) NOT NULL,
                tax_amount DECIMAL(10,2) DEFAULT 0,
                shipping_amount DECIMAL(10,2) DEFAULT 0,
                discount_amount DECIMAL(10,2) DEFAULT 0,
                total_amount DECIMAL(10,2) NOT NULL,
                currency VARCHAR(3) DEFAULT 'USD',
                billing_address JSON NOT NULL,
                shipping_address JSON NOT NULL,
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
                INDEX idx_user_id (user_id),
                INDEX idx_order_number (order_number),
                INDEX idx_status (status),
                INDEX idx_payment_status (payment_status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Order items table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS order_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                product_name VARCHAR(255) NOT NULL,
                product_sku VARCHAR(100) NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                total DECIMAL(10,2) NOT NULL,
                attributes JSON DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
                INDEX idx_order_id (order_id),
                INDEX idx_product_id (product_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Settings table for website configuration
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                setting_key VARCHAR(100) UNIQUE NOT NULL,
                setting_value TEXT,
                setting_type ENUM('text', 'textarea', 'number', 'boolean', 'json') DEFAULT 'text',
                description VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_setting_key (setting_key)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Coupons table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS coupons (
                id INT AUTO_INCREMENT PRIMARY KEY,
                code VARCHAR(50) UNIQUE NOT NULL,
                type ENUM('percentage', 'fixed_amount') NOT NULL,
                value DECIMAL(10,2) NOT NULL,
                minimum_amount DECIMAL(10,2) DEFAULT 0,
                maximum_discount DECIMAL(10,2) DEFAULT NULL,
                usage_limit INT DEFAULT NULL,
                used_count INT DEFAULT 0,
                valid_from DATETIME NOT NULL,
                valid_until DATETIME NOT NULL,
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_code (code),
                INDEX idx_is_active (is_active),
                INDEX idx_valid_dates (valid_from, valid_until)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Product reviews table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS product_reviews (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                user_id INT NOT NULL,
                rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
                title VARCHAR(255),
                review_text TEXT,
                is_verified_purchase BOOLEAN DEFAULT FALSE,
                is_approved BOOLEAN DEFAULT FALSE,
                helpful_count INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                UNIQUE KEY unique_user_product_review (product_id, user_id),
                INDEX idx_product_id (product_id),
                INDEX idx_user_id (user_id),
                INDEX idx_rating (rating),
                INDEX idx_is_approved (is_approved)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Newsletter subscribers table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS newsletter_subscribers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) UNIQUE NOT NULL,
                name VARCHAR(255),
                is_active BOOLEAN DEFAULT TRUE,
                confirmation_token VARCHAR(255),
                confirmed_at TIMESTAMP NULL DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email (email),
                INDEX idx_is_active (is_active)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        return true;
        
    } catch (PDOException $e) {
        error_log("Database Table Creation Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Insert default data into tables
 */
function insertDefaultData($pdo) {
    try {
        // Insert default settings
        $default_settings = [
            ['website_announcement_banner', '', 'textarea', 'Announcement banner text shown at top of website'],
            ['site_name', 'Velvet Vogue', 'text', 'Website name'],
            ['site_description', 'Luxury Fashion Store', 'text', 'Website description'],
            ['contact_email', 'info@velvetvogue.com', 'text', 'Contact email address'],
            ['contact_phone', '+1 (234) 567-890', 'text', 'Contact phone number'],
            ['free_shipping_threshold', '200', 'number', 'Minimum order amount for free shipping'],
            ['currency', 'USD', 'text', 'Default currency'],
            ['tax_rate', '8.25', 'number', 'Tax rate percentage'],
        ];

        foreach ($default_settings as $setting) {
            $stmt = $pdo->prepare("INSERT IGNORE INTO settings (setting_key, setting_value, setting_type, description) VALUES (?, ?, ?, ?)");
            $stmt->execute($setting);
        }

        // Insert default categories
        $default_categories = [
            ['Women', 'women', 'Women\'s fashion collection', null, 1],
            ['Men', 'men', 'Men\'s fashion collection', null, 2],
            ['Accessories', 'accessories', 'Fashion accessories', null, 3],
            ['Dresses', 'dresses', 'Women\'s dresses', 1, 1],
            ['Tops', 'tops', 'Women\'s tops', 1, 2],
            ['Bottoms', 'bottoms', 'Women\'s bottoms', 1, 3],
            ['Suits', 'suits', 'Men\'s suits', 2, 1],
            ['Shirts', 'shirts', 'Men\'s shirts', 2, 2],
            ['Pants', 'pants', 'Men\'s pants', 2, 3],
            ['Bags', 'bags', 'Handbags and purses', 3, 1],
            ['Jewelry', 'jewelry', 'Fashion jewelry', 3, 2],
            ['Shoes', 'shoes', 'Fashion shoes', 3, 3],
        ];

        foreach ($default_categories as $category) {
            $stmt = $pdo->prepare("INSERT IGNORE INTO categories (name, slug, description, parent_id, sort_order) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute($category);
        }

        // Create default admin user (password: admin123)
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT IGNORE INTO users (first_name, last_name, email, password, is_admin, email_verified) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Admin', 'User', 'admin@velvetvogue.com', $admin_password, true, true]);

        return true;
        
    } catch (PDOException $e) {
        error_log("Default Data Insertion Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Utility function to execute queries safely
 */
function executeQuery($pdo, $query, $params = []) {
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Query Execution Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get setting value by key
 */
function getSetting($pdo, $key, $default = null) {
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch (PDOException $e) {
        error_log("Get Setting Error: " . $e->getMessage());
        return $default;
    }
}

/**
 * Update setting value
 */
function updateSetting($pdo, $key, $value) {
    try {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = CURRENT_TIMESTAMP");
        return $stmt->execute([$key, $value]);
    } catch (PDOException $e) {
        error_log("Update Setting Error: " . $e->getMessage());
        return false;
    }
}

// Initialize database tables and default data if needed
// This should only be run once during setup
if (!empty($_GET['setup']) && $_GET['setup'] === 'database') {
    if (createDatabaseTables($pdo)) {
        insertDefaultData($pdo);
        echo "Database setup completed successfully!";
    } else {
        echo "Database setup failed!";
    }
    exit;
}
?>