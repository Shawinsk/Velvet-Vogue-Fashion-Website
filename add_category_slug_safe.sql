-- Add slug column to categories table if it doesn't exist
SET @exist := (SELECT COUNT(*) 
               FROM information_schema.columns 
               WHERE table_schema = 'velvet_vogue'
               AND table_name = 'categories'
               AND column_name = 'slug');

SET @query := IF(@exist = 0,
    'ALTER TABLE categories ADD COLUMN slug VARCHAR(100) UNIQUE AFTER name',
    'SELECT "Slug column already exists" AS message');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Update existing categories with slugs where slug is NULL
UPDATE categories SET slug = LOWER(REPLACE(name, ' ', '-')) WHERE slug IS NULL;
