-- Add slug column to categories table
ALTER TABLE categories ADD COLUMN slug VARCHAR(100) UNIQUE AFTER name;

-- Update existing categories with slugs
UPDATE categories SET slug = LOWER(REPLACE(name, ' ', '-'));
