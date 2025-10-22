-- Create missing tables for Velvet Vogue E-commerce
-- Run this after initialize_database.sql

USE velvet_vogue;

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
('shipping_cost', '500.00', 'Default shipping cost')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- Insert sample orders for testing
INSERT INTO orders (user_id, total_amount, status, shipping_address, payment_method, payment_status) VALUES
(1, 15999.00, 'pending', '123 Main Street, Colombo 01, Sri Lanka', 'credit_card', 'pending'),
(1, 8999.00, 'completed', '456 Park Avenue, Kandy, Sri Lanka', 'bank_transfer', 'paid'),
(1, 12500.00, 'processing', '789 Beach Road, Galle, Sri Lanka', 'cash_on_delivery', 'pending');

-- Insert sample order items
INSERT INTO order_items (order_id, product_id, quantity, price, total) VALUES
-- Order 1 items
(1, 1, 2, 4500.00, 9000.00),
(1, 3, 1, 6500.00, 6500.00),
(1, 5, 1, 499.00, 499.00),
-- Order 2 items
(2, 2, 1, 2800.00, 2800.00),
(2, 4, 1, 6199.00, 6199.00),
-- Order 3 items
(3, 1, 1, 4500.00, 4500.00),
(3, 2, 2, 2800.00, 5600.00),
(3, 5, 5, 499.00, 2400.00);

-- Insert sample cart items
INSERT INTO cart (user_id, product_id, quantity) VALUES
(1, 1, 2),
(1, 3, 1);

SELECT 'Missing tables created successfully!' as status;