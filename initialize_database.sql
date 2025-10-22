-- Create database if not exists
CREATE DATABASE IF NOT EXISTS velvet_vogue;
USE velvet_vogue;

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE,
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image VARCHAR(255) NOT NULL,
    stock_quantity INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Create product_images table
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    is_primary BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    role ENUM('admin', 'customer') DEFAULT 'customer',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default categories
INSERT INTO categories (name, slug, description) VALUES
('Dresses', 'dresses', 'Elegant dresses for all occasions'),
('Tops', 'tops', 'Stylish tops and blouses'),
('Pants', 'pants', 'Comfortable and fashionable pants'),
('Accessories', 'accessories', 'Beautiful accessories to complete your look');

-- Insert sample products
INSERT INTO products (name, description, price, category_id, image, stock_quantity) VALUES
('Elegant Summer Dress', 'Beautiful floral summer dress perfect for any occasion', 4500.00, 1, 'casual-dress.svg', 50),
('Classic White Blouse', 'Timeless white blouse that goes with everything', 2800.00, 2, 'formal-shirt.svg', 75),
('Designer Handbag', 'Luxurious leather handbag with gold accents', 6500.00, 4, 'handbag.svg', 25),
('Ruby Gold Necklace', 'Stunning gold necklace with ruby pendant', 12000.00, 4, 'necklace.svg', 15),
('Classic Blue Jeans', 'Comfortable and stylish blue jeans', 3500.00, 3, 'jeans.svg', 100);

-- Insert initial product images
INSERT INTO product_images (product_id, image_path, alt_text, is_primary, sort_order)
SELECT 
    id as product_id,
    image as image_path,
    name as alt_text,
    1 as is_primary,
    0 as sort_order
FROM products;

-- Create default admin user (password: admin123)
INSERT INTO users (username, password, email, first_name, last_name, role) VALUES
('admin', '$2y$10$8FPi8P.V0FvzPd7qmRKH8O6PXx3pvbF0K8kWbXLHEb4PAYKl0nOru', 'admin@velvetvogue.com', 'Admin', 'User', 'admin');