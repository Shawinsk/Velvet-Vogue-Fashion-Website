USE velvet_vogue;

-- Insert categories
INSERT INTO categories (name, slug, description, status) VALUES
('Women''s Dresses', 'womens-dresses', 'Elegant collection of dresses for all occasions', 'active'),
('Women''s Tops', 'womens-tops', 'Stylish tops and blouses', 'active'),
('Women''s Bottoms', 'womens-bottoms', 'Pants, skirts, and shorts', 'active'),
('Men''s Shirts', 'mens-shirts', 'Casual and formal shirts', 'active'),
('Men''s Pants', 'mens-pants', 'Trousers and jeans', 'active'),
('Accessories', 'accessories', 'Bags, jewelry, and more', 'active'),
('Footwear', 'footwear', 'Shoes and sandals', 'active'),
('New Arrivals', 'new-arrivals', 'Latest fashion collection', 'active'),
('Outerwear', 'outerwear', 'Jackets, coats, and blazers', 'active'),
('Athleisure', 'athleisure', 'Athletic and leisure wear', 'active');

-- Insert products
INSERT INTO products (name, slug, description, price, sale_price, stock_quantity, category_id, image, status, featured) VALUES
-- Women's Dresses
('Floral Summer Maxi Dress', 'floral-summer-maxi', 'Beautiful floral print maxi dress perfect for summer', 6999.00, 5999.00, 50, 1, 'floral-maxi-dress.jpg', 'active', 1),
('Elegant Evening Gown', 'elegant-evening-gown', 'Long elegant evening gown in deep blue', 12999.00, NULL, 30, 1, 'evening-gown.jpg', 'active', 1),
('Casual Day Dress', 'casual-day-dress', 'Comfortable cotton day dress', 4999.00, 3999.00, 75, 1, 'day-dress.jpg', 'active', 0),
('Party Cocktail Dress', 'party-cocktail-dress', 'Stunning cocktail dress for special occasions', 8999.00, NULL, 40, 1, 'cocktail-dress.jpg', 'active', 1),
('Wrap Midi Dress', 'wrap-midi-dress', 'Flattering wrap style midi dress', 5999.00, NULL, 45, 1, 'wrap-midi-dress.jpg', 'active', 0),
('Little Black Dress', 'little-black-dress', 'Classic little black dress for any occasion', 7499.00, 6499.00, 35, 1, 'black-dress.jpg', 'active', 1),

-- Women's Tops
('Classic White Blouse', 'classic-white-blouse', 'Timeless white blouse for any occasion', 3499.00, NULL, 100, 2, 'white-blouse.jpg', 'active', 0),
('Silk Designer Top', 'silk-designer-top', 'Luxurious silk top with modern design', 5999.00, 4999.00, 45, 2, 'silk-top.jpg', 'active', 1),
('Casual T-Shirt', 'casual-tshirt', 'Comfortable cotton t-shirt', 1999.00, NULL, 150, 2, 'casual-tshirt.jpg', 'active', 0),
('Bohemian Style Top', 'bohemian-top', 'Stylish bohemian pattern top', 4499.00, 3799.00, 60, 2, 'bohemian-top.jpg', 'active', 0),
('Ruffled Sleeve Blouse', 'ruffled-sleeve-blouse', 'Elegant blouse with ruffled sleeves', 4299.00, NULL, 55, 2, 'ruffled-blouse.jpg', 'active', 1),
('Crop Top', 'crop-top', 'Trendy crop top with modern design', 2999.00, 2499.00, 80, 2, 'crop-top.jpg', 'active', 0),

-- Women's Bottoms
('High-Waist Jeans', 'high-waist-jeans', 'Classic high-waist denim jeans', 4999.00, NULL, 80, 3, 'high-waist-jeans.jpg', 'active', 1),
('Pleated Midi Skirt', 'pleated-midi-skirt', 'Elegant pleated midi skirt', 3999.00, 3499.00, 55, 3, 'midi-skirt.jpg', 'active', 0),
('Wide Leg Pants', 'wide-leg-pants', 'Comfortable wide leg pants', 4499.00, NULL, 70, 3, 'wide-leg-pants.jpg', 'active', 0),
('Summer Shorts', 'summer-shorts', 'Cool and casual summer shorts', 2499.00, 1999.00, 90, 3, 'summer-shorts.jpg', 'active', 0),
('Leather Skirt', 'leather-skirt', 'Stylish faux leather mini skirt', 4799.00, NULL, 40, 3, 'leather-skirt.jpg', 'active', 1),
('Palazzo Pants', 'palazzo-pants', 'Flowing palazzo pants in solid color', 4299.00, 3799.00, 65, 3, 'palazzo-pants.jpg', 'active', 0),

-- Men's Shirts
('Formal White Shirt', 'formal-white-shirt', 'Classic formal white shirt', 3999.00, NULL, 120, 4, 'formal-shirt.jpg', 'active', 1),
('Casual Denim Shirt', 'casual-denim-shirt', 'Stylish denim casual shirt', 4499.00, 3999.00, 85, 4, 'denim-shirt.jpg', 'active', 0),
('Printed Summer Shirt', 'printed-summer-shirt', 'Colorful summer print shirt', 3499.00, NULL, 95, 4, 'summer-shirt.jpg', 'active', 0),
('Business Formal Shirt', 'business-formal-shirt', 'Professional business shirt', 4299.00, 3799.00, 100, 4, 'business-shirt.jpg', 'active', 1),
('Linen Casual Shirt', 'linen-casual-shirt', 'Breathable linen casual shirt', 3799.00, NULL, 75, 4, 'linen-shirt.jpg', 'active', 0),
('Polo T-Shirt', 'polo-tshirt', 'Classic polo t-shirt', 2999.00, 2499.00, 110, 4, 'polo-shirt.jpg', 'active', 1),

-- Men's Pants
('Classic Fit Trousers', 'classic-fit-trousers', 'Professional classic fit trousers', 4999.00, NULL, 110, 5, 'classic-trousers.jpg', 'active', 1),
('Slim Fit Jeans', 'slim-fit-jeans', 'Modern slim fit jeans', 4499.00, 3999.00, 130, 5, 'slim-jeans.jpg', 'active', 0),
('Cargo Pants', 'cargo-pants', 'Casual cargo pants', 3999.00, NULL, 95, 5, 'cargo-pants.jpg', 'active', 0),
('Formal Black Pants', 'formal-black-pants', 'Essential formal black pants', 4799.00, 4299.00, 85, 5, 'formal-pants.jpg', 'active', 1),
('Chino Pants', 'chino-pants', 'Versatile chino pants', 3999.00, NULL, 90, 5, 'chino-pants.jpg', 'active', 0),
('Athletic Track Pants', 'track-pants', 'Comfortable athletic track pants', 2999.00, 2499.00, 100, 5, 'track-pants.jpg', 'active', 0),

-- Accessories
('Designer Handbag', 'designer-handbag', 'Luxury designer handbag', 8999.00, 7499.00, 40, 6, 'designer-bag.jpg', 'active', 1),
('Silver Necklace Set', 'silver-necklace-set', 'Elegant silver necklace and earrings set', 2999.00, NULL, 65, 6, 'necklace-set.jpg', 'active', 0),
('Leather Belt', 'leather-belt', 'Premium leather belt', 1999.00, 1499.00, 150, 6, 'leather-belt.jpg', 'active', 0),
('Designer Sunglasses', 'designer-sunglasses', 'Trendy designer sunglasses', 3499.00, NULL, 75, 6, 'sunglasses.jpg', 'active', 1),
('Silk Scarf', 'silk-scarf', 'Luxurious silk scarf with print', 2499.00, NULL, 60, 6, 'silk-scarf.jpg', 'active', 0),
('Crossbody Bag', 'crossbody-bag', 'Stylish leather crossbody bag', 5999.00, 4999.00, 45, 6, 'crossbody-bag.jpg', 'active', 1),

-- Footwear
('Classic Heels', 'classic-heels', 'Elegant classic heels', 5999.00, 4999.00, 60, 7, 'classic-heels.jpg', 'active', 1),
('Casual Sneakers', 'casual-sneakers', 'Comfortable casual sneakers', 4499.00, NULL, 100, 7, 'sneakers.jpg', 'active', 0),
('Formal Oxford Shoes', 'formal-oxford-shoes', 'Classic oxford formal shoes', 6999.00, 5999.00, 70, 7, 'oxford-shoes.jpg', 'active', 1),
('Summer Sandals', 'summer-sandals', 'Comfortable summer sandals', 2999.00, 2499.00, 120, 7, 'sandals.jpg', 'active', 0),
('Ballet Flats', 'ballet-flats', 'Classic ballet flats', 3499.00, NULL, 85, 7, 'ballet-flats.jpg', 'active', 0),
('Ankle Boots', 'ankle-boots', 'Stylish leather ankle boots', 7499.00, 6499.00, 50, 7, 'ankle-boots.jpg', 'active', 1),

-- Outerwear
('Classic Blazer', 'classic-blazer', 'Timeless classic blazer', 8999.00, NULL, 45, 9, 'classic-blazer.jpg', 'active', 1),
('Leather Jacket', 'leather-jacket', 'Edgy leather jacket', 12999.00, 11499.00, 30, 9, 'leather-jacket.jpg', 'active', 1),
('Denim Jacket', 'denim-jacket', 'Versatile denim jacket', 5999.00, NULL, 60, 9, 'denim-jacket.jpg', 'active', 0),
('Trench Coat', 'trench-coat', 'Classic trench coat', 9999.00, 8999.00, 35, 9, 'trench-coat.jpg', 'active', 1),

-- Athleisure
('Yoga Pants', 'yoga-pants', 'High-performance yoga pants', 3999.00, NULL, 80, 10, 'yoga-pants.jpg', 'active', 1),
('Sports Bra', 'sports-bra', 'Supportive sports bra', 2499.00, 1999.00, 100, 10, 'sports-bra.jpg', 'active', 0),
('Athletic Tank Top', 'athletic-tank', 'Breathable athletic tank top', 1999.00, NULL, 120, 10, 'athletic-tank.jpg', 'active', 0),
('Running Shorts', 'running-shorts', 'Lightweight running shorts', 2299.00, 1999.00, 90, 10, 'running-shorts.jpg', 'active', 1);

-- Insert admin user (password: password123)
INSERT INTO users (first_name, last_name, email, password, role, status) VALUES
('Admin', 'User', 'admin@velvetvogue.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');