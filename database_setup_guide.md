# Velvet Vogue Database Setup Guide

## Prerequisites
- XAMPP installed and running
- MySQL/MariaDB service started
- PHP 7.4 or higher

## Step 1: Create Database

### Option A: Using phpMyAdmin
1. Open your browser and go to `http://localhost/phpmyadmin`
2. Click "New" to create a new database
3. Enter database name: `velvet_vogue`
4. Click "Create"

### Option B: Using MySQL Command Line
```sql
CREATE DATABASE IF NOT EXISTS velvet_vogue;
USE velvet_vogue;
```

## Step 2: Run Database Initialization

### Method 1: Using the Setup Script
1. Navigate to: `http://localhost/web/New%20folder%20(2)/velvet-vogue/setup_database.php`
2. This will automatically create all required tables

### Method 2: Manual SQL Execution
Execute the following SQL files in order:

1. **Core Tables** - Run `sql/initialize_database.sql`
2. **Additional Tables** - Run the SQL below for missing tables
3. **Sample Data** - Run `sql/sample_data.sql`

## Step 3: Missing Tables (Orders & Cart)

The following tables are missing and need to be created:

```sql
-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT,
    billing_address TEXT,
    payment_method VARCHAR(50),
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Create cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    session_id VARCHAR(255),
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id),
    KEY session_cart (session_id, product_id)
);

-- Create admin_settings table
CREATE TABLE IF NOT EXISTS admin_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin settings
INSERT INTO admin_settings (setting_key, setting_value, description) VALUES
('site_name', 'Velvet Vogue', 'Website name'),
('site_description', 'Premium Fashion Store', 'Website description'),
('contact_email', 'info@velvetvogue.com', 'Contact email address'),
('currency', 'LKR', 'Default currency'),
('tax_rate', '0.00', 'Tax rate percentage'),
('shipping_cost', '500.00', 'Default shipping cost');
```

## Step 4: Database Configuration

Ensure your database connection settings in `includes/db_connect.php` are correct:

```php
$db_config = [
    'host' => 'localhost',
    'dbname' => 'velvet_vogue',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
];
```

## Step 5: Test Database Connection

1. Navigate to: `http://localhost/web/New%20folder%20(2)/velvet-vogue/admin/view_orders.php`
2. If no errors appear, the database is properly connected
3. You should see the orders management interface

## Default Admin Login

- **Username:** admin
- **Password:** admin123
- **Email:** admin@velvetvogue.com

## Troubleshooting

### Common Issues:

1. **"Access denied" error:**
   - Check MySQL username/password in `db_connect.php`
   - Ensure MySQL service is running

2. **"Database doesn't exist" error:**
   - Create the database manually using phpMyAdmin
   - Run the initialization script

3. **"Table doesn't exist" error:**
   - Run the complete SQL initialization
   - Check if all tables were created successfully

4. **Permission errors:**
   - Ensure proper file permissions
   - Check XAMPP security settings

## Database Structure Overview

### Core Tables:
- `users` - User accounts (customers and admins)
- `categories` - Product categories
- `products` - Product catalog
- `product_images` - Product image gallery

### E-commerce Tables:
- `cart` - Shopping cart items
- `orders` - Customer orders
- `order_items` - Individual order line items

### Admin Tables:
- `admin_settings` - Site configuration settings

## Next Steps

1. ✅ Database created and connected
2. ✅ All tables initialized
3. ✅ Sample data inserted
4. ✅ Admin account created
5. 🔄 Test the application functionality
6. 🔄 Add more products and categories as needed

---

**Note:** Always backup your database before making changes in production!