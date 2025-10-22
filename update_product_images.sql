USE velvet_vogue;

DELETE FROM product_images;

INSERT INTO product_images (product_id, image_url, alt_text, is_primary, sort_order) VALUES
(1, 'assets/images/product_images/evening-dress-1.jpg', 'Elegant Evening Dress - Front View', 1, 1),
(1, 'assets/images/product_images/evening-dress-2.jpg', 'Elegant Evening Dress - Side View', 0, 2),
(2, 'assets/images/product_images/summer-blouse-1.jpg', 'Casual Summer Blouse - Front View', 1, 1),
(2, 'assets/images/product_images/summer-blouse-2.jpg', 'Casual Summer Blouse - Back View', 0, 2),
(3, 'assets/images/product_images/mens-suit-1.jpg', 'Classic Mens Suit - Front View', 1, 1),
(3, 'assets/images/product_images/mens-suit-2.jpg', 'Classic Mens Suit - Side View', 0, 2),
(4, 'assets/images/product_images/mens-shirt-1.jpg', 'Casual Mens Shirt - Worn', 1, 1),
(4, 'assets/images/product_images/mens-shirt-2.jpg', 'Casual Mens Shirt - Folded', 0, 2),
(5, 'assets/images/product_images/handbag-1.jpg', 'Designer Handbag - Front View', 1, 1),
(5, 'assets/images/product_images/handbag-2.jpg', 'Designer Handbag - Side View', 0, 2),
(6, 'assets/images/product_images/sunglasses-1.jpg', 'Fashion Sunglasses - Front View', 1, 1),
(6, 'assets/images/product_images/sunglasses-2.jpg', 'Fashion Sunglasses - Side View', 0, 2);