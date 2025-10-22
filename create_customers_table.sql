-- Create customers table for Velvet Vogue
USE velvet_vogue;

-- Create customers table with comprehensive customer information
CREATE TABLE IF NOT EXISTS customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_code VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    
    -- Address information
    address_line_1 VARCHAR(255),
    address_line_2 VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100) DEFAULT 'Sri Lanka',
    
    -- Account status and preferences
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    email_verified BOOLEAN DEFAULT FALSE,
    email_verification_token VARCHAR(255),
    phone_verified BOOLEAN DEFAULT FALSE,
    
    -- Marketing preferences
    newsletter_subscribed BOOLEAN DEFAULT TRUE,
    sms_notifications BOOLEAN DEFAULT FALSE,
    
    -- Customer metrics
    total_orders INT DEFAULT 0,
    total_spent DECIMAL(12,2) DEFAULT 0.00,
    loyalty_points INT DEFAULT 0,
    
    -- Account management
    last_login TIMESTAMP NULL,
    password_reset_token VARCHAR(255),
    password_reset_expires TIMESTAMP NULL,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes for better performance
    INDEX idx_email (email),
    INDEX idx_customer_code (customer_code),
    INDEX idx_phone (phone),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_last_login (last_login)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create trigger to generate customer code automatically
DELIMITER //
CREATE TRIGGER IF NOT EXISTS generate_customer_code 
    BEFORE INSERT ON customers 
    FOR EACH ROW 
BEGIN
    IF NEW.customer_code IS NULL OR NEW.customer_code = '' THEN
        SET NEW.customer_code = CONCAT('CUST', LPAD(LAST_INSERT_ID() + 1, 6, '0'));
    END IF;
END//
DELIMITER ;

-- Migrate existing customer data from users table
INSERT INTO customers (
    customer_code,
    first_name, 
    last_name, 
    email, 
    password,
    phone,
    date_of_birth,
    gender,
    status,
    email_verified,
    created_at
)
SELECT 
    CONCAT('CUST', LPAD(id, 6, '0')) as customer_code,
    first_name,
    last_name,
    email,
    password,
    phone,
    date_of_birth,
    gender,
    'active' as status,
    email_verified,
    created_at
FROM users 
WHERE is_admin = FALSE
ON DUPLICATE KEY UPDATE email = VALUES(email);

-- Create customer_addresses table for multiple addresses
CREATE TABLE IF NOT EXISTS customer_addresses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    address_type ENUM('billing', 'shipping', 'both') DEFAULT 'both',
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    company VARCHAR(100),
    address_line_1 VARCHAR(255) NOT NULL,
    address_line_2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL DEFAULT 'Sri Lanka',
    phone VARCHAR(20),
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    INDEX idx_customer_id (customer_id),
    INDEX idx_address_type (address_type),
    INDEX idx_is_default (is_default)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create customer_preferences table
CREATE TABLE IF NOT EXISTS customer_preferences (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    preference_key VARCHAR(100) NOT NULL,
    preference_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    UNIQUE KEY unique_customer_preference (customer_id, preference_key),
    INDEX idx_customer_id (customer_id),
    INDEX idx_preference_key (preference_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SELECT 'Customers table and related tables created successfully!' as message;