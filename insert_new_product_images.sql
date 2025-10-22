-- Insert new product images

-- First, let's find some product IDs to associate with our new images
SET @stylish_jacket_product_id = (SELECT id FROM products WHERE name LIKE '%jacket%' OR name LIKE '%coat%' LIMIT 1);
SET @elegant_blouse_product_id = (SELECT id FROM products WHERE name LIKE '%blouse%' OR name LIKE '%top%' LIMIT 1);
SET @formal_suit_product_id = (SELECT id FROM products WHERE name LIKE '%suit%' OR name LIKE '%formal%' LIMIT 1);
SET @stylish_scarf_product_id = (SELECT id FROM products WHERE name LIKE '%scarf%' OR name LIKE '%accessory%' LIMIT 1);
SET @leather_belt_product_id = (SELECT id FROM products WHERE name LIKE '%belt%' OR name LIKE '%accessory%' LIMIT 1);

-- If we can't find matching products, use some default product IDs
SET @stylish_jacket_product_id = IFNULL(@stylish_jacket_product_id, 1);
SET @elegant_blouse_product_id = IFNULL(@elegant_blouse_product_id, 2);
SET @formal_suit_product_id = IFNULL(@formal_suit_product_id, 3);
SET @stylish_scarf_product_id = IFNULL(@stylish_scarf_product_id, 4);
SET @leather_belt_product_id = IFNULL(@leather_belt_product_id, 5);

-- Insert the new product images
INSERT INTO product_images (product_id, image_path, alt_text, is_primary, sort_order)
VALUES
(@stylish_jacket_product_id, 'stylish-jacket.svg', 'Stylish Jacket', 1, 0),
(@elegant_blouse_product_id, 'elegant-blouse.svg', 'Elegant Blouse', 1, 0),
(@formal_suit_product_id, 'formal-suit.svg', 'Formal Suit', 1, 0),
(@stylish_scarf_product_id, 'stylish-scarf.svg', 'Stylish Scarf', 1, 0),
(@leather_belt_product_id, 'leather-belt.svg', 'Leather Belt', 1, 0);

-- Update the products table to use these new images as primary images
UPDATE products 
SET image = 'stylish-jacket.svg'
WHERE id = @stylish_jacket_product_id;

UPDATE products 
SET image = 'elegant-blouse.svg'
WHERE id = @elegant_blouse_product_id;

UPDATE products 
SET image = 'formal-suit.svg'
WHERE id = @formal_suit_product_id;

UPDATE products 
SET image = 'stylish-scarf.svg'
WHERE id = @stylish_scarf_product_id;

UPDATE products 
SET image = 'leather-belt.svg'
WHERE id = @leather_belt_product_id;