<?php
// Download real product images for Velvet Vogue

require_once 'includes/db_connect.php';

// Create images directory if it doesn't exist
$imageDir = 'assets/images/product_images';
if (!file_exists($imageDir)) {
    mkdir($imageDir, 0777, true);
}

// Function to download image from URL
function downloadImage($url, $filename) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200 && $data !== false) {
        file_put_contents($filename, $data);
        return true;
    }
    return false;
}

// Sample image URLs for different product categories
$imageUrls = [
    // Women's Dresses
    'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=400&h=600&fit=crop' => 'elegant-dress-1.jpg',
    'https://images.unsplash.com/photo-1566479179817-c0b8e5b6b8b8?w=400&h=600&fit=crop' => 'casual-dress-2.jpg',
    'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=400&h=600&fit=crop' => 'summer-dress-3.jpg',
    
    // Men's Shirts
    'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=400&h=600&fit=crop' => 'formal-shirt-1.jpg',
    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=400&h=600&fit=crop' => 'casual-shirt-2.jpg',
    'https://images.unsplash.com/photo-1621072156002-e2fccdc0b176?w=400&h=600&fit=crop' => 'polo-shirt-3.jpg',
    
    // Accessories
    'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop' => 'luxury-watch-1.jpg',
    'https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?w=400&h=400&fit=crop' => 'leather-bag-1.jpg',
    'https://images.unsplash.com/photo-1506629905607-d405b7a82b8b?w=400&h=400&fit=crop' => 'sunglasses-1.jpg',
    
    // Women's Tops
    'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400&h=600&fit=crop' => 'blouse-1.jpg',
    'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=600&fit=crop' => 'sweater-1.jpg',
    
    // Men's Pants
    'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=400&h=600&fit=crop' => 'formal-pants-1.jpg',
    'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&h=600&fit=crop' => 'jeans-1.jpg',
    
    // Footwear
    'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=400&fit=crop' => 'sneakers-1.jpg',
    'https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=400&h=400&fit=crop' => 'dress-shoes-1.jpg',
    'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=400&h=400&fit=crop' => 'heels-1.jpg',
    
    // Jewelry
    'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=400&h=400&fit=crop' => 'necklace-1.jpg',
    'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=400&h=400&fit=crop' => 'earrings-1.jpg',
    
    // Outerwear
    'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&h=600&fit=crop' => 'jacket-1.jpg',
    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=600&fit=crop' => 'coat-1.jpg'
];

echo "Starting image download...\n";
$downloaded = 0;
$failed = 0;

foreach ($imageUrls as $url => $filename) {
    $filepath = $imageDir . '/' . $filename;
    
    // Skip if file already exists
    if (file_exists($filepath)) {
        echo "Skipping $filename (already exists)\n";
        continue;
    }
    
    echo "Downloading $filename...";
    
    if (downloadImage($url, $filepath)) {
        echo " SUCCESS\n";
        $downloaded++;
        
        // Add a small delay to be respectful to the server
        sleep(1);
    } else {
        echo " FAILED\n";
        $failed++;
    }
}

echo "\nDownload complete!\n";
echo "Downloaded: $downloaded images\n";
echo "Failed: $failed images\n";
echo "Images saved to: $imageDir\n";

// Now let's update some products with these new images
echo "\nUpdating product images in database...\n";

try {
    // Get some products that need images
    $stmt = $pdo->prepare("SELECT id, name, category_id FROM products WHERE (image IS NULL OR image = '') LIMIT 20");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $imageFiles = array_values($imageUrls);
    $updated = 0;
    
    foreach ($products as $index => $product) {
        if ($index < count($imageFiles)) {
            $imageName = $imageFiles[$index];
            $imagePath = 'assets/images/product_images/' . $imageName;
            
            // Update product with new image
            $updateStmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
            if ($updateStmt->execute([$imageName, $product['id']])) {
                echo "Updated product '{$product['name']}' with image $imageName\n";
                $updated++;
            }
        }
    }
    
    echo "\nDatabase update complete!\n";
    echo "Updated $updated products with new images\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

echo "\nAll done! Check the products page to see the new images.\n";
?>