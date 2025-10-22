USE velvet_vogue;

-- Create categories table
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create products table
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
    is_featured BOOLEAN DEFAULT FALSE,
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
    INDEX idx_is_featured (is_featured),
    INDEX idx_in_stock (in_stock),
    FULLTEXT idx_search (name, description, tags)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create product_images table
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    is_primary BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id),
    INDEX idx_is_primary (is_primary)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample categories
INSERT IGNORE INTO categories (id, name, slug, description, image, sort_order) VALUES 
(1, 'Women''s Collection', 'womens-collection', 'Stylish and trendy clothing for women', 'images/categories/womens.jpg', 1),
(2, 'Men''s Collection', 'mens-collection', 'Modern and comfortable clothing for men', 'images/categories/mens.jpg', 2),
(3, 'Accessories', 'accessories', 'Beautiful accessories to complete your look', 'images/categories/accessories.jpg', 3);

-- Insert sample products
INSERT IGNORE INTO products (id, name, slug, description, short_description, sku, price, sale_price, stock_quantity, category_id, brand, is_featured, status) VALUES 
(1, 'Elegant Evening Dress', 'elegant-evening-dress', 'A stunning evening dress perfect for special occasions', 'Beautiful evening dress with elegant design', 'WD001', 299.99, 249.99, 15, 1, 'Velvet Vogue', TRUE, 'active'),
(2, 'Casual Summer Blouse', 'casual-summer-blouse', 'Light and comfortable blouse for summer days', 'Comfortable summer blouse in various colors', 'WB001', 79.99, NULL, 25, 1, 'Velvet Vogue', TRUE, 'active'),
(3, 'Classic Men''s Suit', 'classic-mens-suit', 'Professional suit for business and formal events', 'High-quality suit with modern fit', 'MS001', 599.99, 499.99, 8, 2, 'Velvet Vogue', TRUE, 'active'),
(4, 'Casual Men''s Shirt', 'casual-mens-shirt', 'Comfortable shirt for everyday wear', 'Versatile shirt in multiple colors', 'MS002', 89.99, NULL, 30, 2, 'Velvet Vogue', FALSE, 'active'),
(5, 'Designer Handbag', 'designer-handbag', 'Luxury handbag with premium materials', 'Elegant handbag for any occasion', 'AC001', 199.99, 179.99, 12, 3, 'Velvet Vogue', TRUE, 'active'),
(6, 'Fashion Sunglasses', 'fashion-sunglasses', 'Stylish sunglasses with UV protection', 'Trendy sunglasses in various styles', 'AC002', 129.99, NULL, 20, 3, 'Velvet Vogue', FALSE, 'active');

-- Insert sample product images
INSERT IGNORE INTO product_images (product_id, image_url, alt_text, is_primary, sort_order) VALUES 
(1, 'images/products/evening-dress-1.jpg', 'Elegant Evening Dress - Front View', TRUE, 1),
(1, 'images/products/evening-dress-2.jpg', 'Elegant Evening Dress - Side View', FALSE, 2),
(2, 'images/products/summer-blouse-1.jpg', 'Casual Summer Blouse - Front View', TRUE, 1),
(2, 'images/products/summer-blouse-2.jpg', 'Casual Summer Blouse - Back View', FALSE, 2),
(3, 'images/products/mens-suit-1.jpg', 'Classic Men''s Suit - Full View', TRUE, 1),
(3, 'images/products/mens-suit-2.jpg', 'Classic Men''s Suit - Detail View', FALSE, 2),
(4, 'images/products/mens-shirt-1.jpg', 'Casual Men''s Shirt - Front View', TRUE, 1),
(5, 'images/products/handbag-1.jpg', 'Designer Handbag - Main View', TRUE, 1),
(5, 'images/products/handbag-2.jpg', 'Designer Handbag - Detail View', FALSE, 2),
(6, 'images/products/sunglasses-1.jpg', 'Fashion Sunglasses - Front View', TRUE, 1);