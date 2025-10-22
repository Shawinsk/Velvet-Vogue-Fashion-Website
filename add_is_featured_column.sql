-- Add missing is_featured column to products table
-- This fixes the PDOException: Unknown column 'p.is_featured' error

USE velvet_vogue;

-- Check if column exists before adding
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'velvet_vogue' 
AND table_name = 'products' 
AND column_name = 'is_featured';

-- Add column only if it doesn't exist
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE products ADD COLUMN is_featured BOOLEAN DEFAULT FALSE AFTER stock_quantity;',
    'SELECT "Column is_featured already exists" as message;'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Verify the column was added
SELECT 'Column added successfully' as status;
DESCRIBE products;