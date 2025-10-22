<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Get featured products
$products_query = "
    SELECT 
        p.*,
        c.name as category_name,
        c.slug as category_slug,
        (SELECT image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) as primary_image
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'active' AND p.is_featured = 1
    ORDER BY p.created_at DESC
    LIMIT 8
";

$products_stmt = $pdo->prepare($products_query);
$products_stmt->execute();
$featured_products = $products_stmt->fetchAll();
?>

<!-- Featured Products Section -->
<section class="featured-products">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Featured Products</h2>
            <p class="section-subtitle">Handpicked pieces from our latest collection</p>
        </div>
        
        <?php if (empty($featured_products)): ?>
            <div class="no-products">
                <div class="no-products-content">
                    <i class="fas fa-shopping-bag"></i>
                    <h3>No featured products available</h3>
                    <p>Check back soon for our latest featured items.</p>
                    <a href="products.php" class="btn btn-primary">Browse All Products</a>
                </div>
            </div>
        <?php else: ?>
            <style>
                .featured-products {
                    padding: 60px 0;
                    background: #f8f9fa;
                }
                
                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 0 20px;
                }
                
                .section-header {
                    text-align: center;
                    margin-bottom: 50px;
                }
                
                .section-title {
                    font-family: 'Playfair Display', serif;
                    font-size: 2.5rem;
                    color: #2c2c2c;
                    margin-bottom: 15px;
                }
                
                .section-subtitle {
                    color: #666;
                    font-size: 1.1rem;
                }
                
                .products-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                    gap: 30px;
                    margin-bottom: 50px;
                }
                
                .product-card {
                    background: white;
                    border-radius: 15px;
                    overflow: hidden;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                    transition: all 0.3s ease;
                    position: relative;
                }
                
                .product-card:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
                }
                
                .product-image {
                    position: relative;
                    overflow: hidden;
                    height: 300px;
                    background: #f8f8f8;
                }
                
                .product-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.3s ease;
                }
                
                .product-card:hover .product-image img {
                    transform: scale(1.05);
                }
                
                .no-image {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    height: 100%;
                    color: #666;
                }
                
                .no-image i {
                    font-size: 3rem;
                    margin-bottom: 10px;
                }
                
                .sale-badge, .featured-badge {
                    position: absolute;
                    top: 15px;
                    padding: 5px 12px;
                    border-radius: 15px;
                    font-size: 0.8rem;
                    font-weight: 600;
                    color: white;
                    z-index: 2;
                }
                
                .sale-badge {
                    background: #e74c3c;
                    left: 15px;
                }
                
                .featured-badge {
                    background: #d4af37;
                    right: 15px;
                }
                
                .product-info {
                    padding: 25px;
                }
                
                .product-category {
                    margin-bottom: 8px;
                }
                
                .product-category a {
                    color: #d4af37;
                    text-decoration: none;
                    font-size: 0.9rem;
                    font-weight: 500;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
                
                .product-name {
                    margin-bottom: 10px;
                }
                
                .product-name a {
                    color: #2c2c2c;
                    text-decoration: none;
                    font-size: 1.2rem;
                    font-weight: 600;
                    transition: color 0.3s ease;
                }
                
                .product-name a:hover {
                    color: #d4af37;
                }
                
                .product-description {
                    color: #666;
                    font-size: 0.9rem;
                    margin-bottom: 15px;
                    line-height: 1.5;
                }
                
                .product-price {
                    margin-bottom: 15px;
                }
                
                .sale-price {
                    font-size: 1.3rem;
                    font-weight: 700;
                    color: #e74c3c;
                    margin-right: 10px;
                }
                
                .original-price {
                    font-size: 1.1rem;
                    color: #666;
                    text-decoration: line-through;
                }
                
                .price {
                    font-size: 1.3rem;
                    font-weight: 700;
                    color: #2c2c2c;
                }
                
                .cart-button-section {
                    margin-bottom: 15px;
                }
                
                .button-row {
                    display: flex;
                    gap: 10px;
                    align-items: center;
                }
                
                .cart-button-section .add-to-cart-btn,
                .cart-button-section .out-of-stock-btn {
                    flex: 1;
                    padding: 12px;
                    border: none;
                    border-radius: 8px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    font-size: 0.95rem;
                }
                
                .cart-button-section .wishlist-btn {
                    padding: 12px;
                    background: transparent;
                    border: 2px solid #e0e0e0;
                    border-radius: 8px;
                    color: #666;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    font-size: 0.9rem;
                    flex-shrink: 0;
                    width: 48px;
                    height: 48px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                
                .cart-button-section .wishlist-btn:hover {
                    border-color: #d4af37;
                    color: #d4af37;
                }
                
                .cart-button-section .add-to-cart-btn {
                    background: #d4af37;
                    color: white;
                }
                
                .cart-button-section .add-to-cart-btn:hover {
                    background: #b8941f;
                    transform: translateY(-2px);
                }
                
                .cart-button-section .out-of-stock-btn {
                    background: #ccc;
                    color: #666;
                    cursor: not-allowed;
                }
                

                
                .no-products {
                    text-align: center;
                    padding: 60px 20px;
                    color: #666;
                }
                
                .no-products-content {
                    max-width: 400px;
                    margin: 0 auto;
                }
                
                .no-products-content i {
                    font-size: 4rem;
                    color: #ddd;
                    margin-bottom: 20px;
                }
                
                .no-products-content h3 {
                    font-size: 1.5rem;
                    margin-bottom: 10px;
                    color: #333;
                }
                
                .no-products-content p {
                    margin-bottom: 20px;
                }
                
                .btn {
                    display: inline-block;
                    padding: 12px 24px;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                }
                
                .btn-primary {
                    background: #d4af37;
                    color: white;
                }
                
                .btn-primary:hover {
                    background: #b8941f;
                    transform: translateY(-2px);
                }
                
                .section-footer {
                    text-align: center;
                    margin-top: 40px;
                }
                
                @media (max-width: 768px) {
                    .products-grid {
                        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                        gap: 20px;
                    }
                    
                    .section-title {
                        font-size: 2rem;
                    }
                    
                    .featured-products {
                        padding: 40px 0;
                    }
                }
            </style>
            <script>


                // Wishlist functionality
                const wishlistBtns = document.querySelectorAll('.wishlist-btn');
                wishlistBtns.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const icon = btn.querySelector('i');
                        
                        if (icon.classList.contains('far')) {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            btn.style.color = '#e74c3c';
                            btn.style.borderColor = '#e74c3c';
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            btn.style.color = '#666';
                            btn.style.borderColor = '#e0e0e0';
                        }
                    });
                });

                // Product card hover effects
                const productCards = document.querySelectorAll('.product-card');
                productCards.forEach(card => {
                    card.addEventListener('mouseenter', () => {
                        card.style.transform = 'translateY(-10px)';
                        card.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)';
                    });
                    
                    card.addEventListener('mouseleave', () => {
                        card.style.transform = 'translateY(0)';
                        card.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';
                    });
                });
            </script>
            <div class="products-grid" id="featured-products">
                <?php foreach ($featured_products as $product): ?>
                    <?php 
                    // Check if this is a men's product
                    $is_mens_product = (strpos(strtolower($product['category_slug']), 'mens') !== false || 
                                       strpos(strtolower($product['category_name']), 'men') !== false);
                    ?>
                    <div class="product-card <?php echo $is_mens_product ? 'mens-product' : ''; ?>">
                        <div class="product-image">
                            <a href="product_detail.php?id=<?php echo $product['id']; ?>">
                                <?php if (!empty($product['primary_image'])): ?>
                                    <img src="<?php echo htmlspecialchars($product['primary_image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                        <span><?php echo htmlspecialchars($product['name']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </a>
                            
                            <?php if ($product['is_featured']): ?>
                                <span class="featured-badge">Featured</span>
                            <?php endif; ?>
                            
                            <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                                <span class="sale-badge">Sale</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <div class="product-category">
                                <a href="products.php?category=<?php echo urlencode($product['category_slug']); ?>">
                                    <?php echo htmlspecialchars($product['category_name']); ?>
                                </a>
                            </div>
                            <h3 class="product-name">
                                <a href="product_detail.php?id=<?php echo $product['id']; ?>">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h3>
                            <p class="product-description">
                                <?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?>
                            </p>
                            <div class="product-price">
                                <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                                    <span class="sale-price">LKR <?php echo number_format($product['sale_price'], 2); ?></span>
                                    <span class="original-price">LKR <?php echo number_format($product['price'], 2); ?></span>
                                <?php else: ?>
                                    <span class="price">LKR <?php echo number_format($product['price'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Wishlist Button -->
                            <div class="cart-button-section">
                                <div class="button-row">
                                    <button class="wishlist-btn" data-product-id="<?php echo $product['id']; ?>">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="section-footer">
            <a href="products.php" class="btn btn-primary">View All Products</a>
        </div>
    </div>
</section>