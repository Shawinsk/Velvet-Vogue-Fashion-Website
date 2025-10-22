-- Create admin user for Velvet Vogue
-- Username: admin, Password: admin123

USE velvet_vogue;

-- Insert admin user (password hash for 'admin123')
INSERT INTO users (first_name, last_name, email, password, is_admin, email_verified, created_at) 
VALUES (
    'Admin',
    'User', 
    'admin@velvetvogue.com',
    '$2y$10$8FPi8P.V0FvzPd7qmRKH8O6PXx3pvbF0K8kWbXLHEb4PAYKl0nOru',
    1,
    1,
    NOW()
)
ON DUPLICATE KEY UPDATE 
    password = '$2y$10$8FPi8P.V0FvzPd7qmRKH8O6PXx3pvbF0K8kWbXLHEb4PAYKl0nOru',
    is_admin = 1,
    email_verified = 1;

SELECT 'Admin user created successfully! Username: admin, Password: admin123' as status;