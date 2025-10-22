-- Mark several clothing products as featured
UPDATE products SET featured = 1 WHERE name IN (
    'Casual Summer Dress',
    'Elegant Evening Dress',
    'Summer Crop Top',
    'Formal Shirt',
    'Elegant Blouse',
    'Classic Blue Jeans',
    'Business Formal Suit',
    'Stylish Jacket',
    'Casual Sneakers'
);
