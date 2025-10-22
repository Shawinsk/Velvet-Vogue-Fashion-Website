-- Remove duplicate categories
DELETE c1 FROM categories c1
INNER JOIN categories c2 
WHERE c1.id > c2.id AND c1.name = c2.name;

-- Now update the slugs
UPDATE categories 
SET slug = LOWER(REPLACE(TRIM(name), ' ', '-'))
WHERE slug IS NULL;
