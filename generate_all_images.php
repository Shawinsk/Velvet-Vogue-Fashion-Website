<?php
require_once 'includes/db_connect.php';

function createGradientBackground($image, $width, $height, $type = 'default') {
    switch ($type) {
        case 'product':
            $start = imagecolorallocate($image, 245, 240, 245); // Light pink
            $end = imagecolorallocate($image, 235, 230, 235);   // Slightly darker pink
            break;
        case 'banner':
            $start = imagecolorallocate($image, 50, 40, 50);    // Dark purple
            $end = imagecolorallocate($image, 90, 80, 90);      // Lighter purple
            break;
        case 'category':
            $start = imagecolorallocate($image, 240, 235, 240); // Very light purple
            $end = imagecolorallocate($image, 230, 225, 230);   // Slightly darker
            break;
        default:
            $start = imagecolorallocate($image, 245, 245, 245); // Light gray
            $end = imagecolorallocate($image, 235, 235, 235);   // Slightly darker gray
    }

    // Create gradient
    for($i = 0; $i < $height; $i++) {
        $ratio = $i / $height;
        $r = (int)(($end >> 16) * $ratio + ($start >> 16) * (1 - $ratio));
        $g = (int)((($end >> 8) & 0xFF) * $ratio + (($start >> 8) & 0xFF) * (1 - $ratio));
        $b = (int)(($end & 0xFF) * $ratio + ($start & 0xFF) * (1 - $ratio));
        $color = imagecolorallocate($image, $r, $g, $b);
        imageline($image, 0, $i, $width, $i, $color);
    }
}

function addTextWithShadow($image, $text, $x, $y, $font_size, $font_path, $main_color, $shadow_color) {
    // Add shadow
    imagettftext($image, $font_size, 0, $x + 2, $y + 2, $shadow_color, $font_path, $text);
    // Add main text
    imagettftext($image, $font_size, 0, $x, $y, $main_color, $font_path, $text);
}

function createImage($width, $height, $text, $filename, $type = 'default') {
    $image = imagecreatetruecolor($width, $height);
    
    // Create gradient background
    createGradientBackground($image, $width, $height, $type);
    
    // Set colors
    $text_color = ($type === 'banner') ? 
        imagecolorallocate($image, 255, 255, 255) : 
        imagecolorallocate($image, 50, 40, 50);
    $shadow_color = ($type === 'banner') ? 
        imagecolorallocate($image, 30, 20, 30) : 
        imagecolorallocate($image, 200, 200, 200);
    
    $font_path = __DIR__ . '/assets/fonts/arial.ttf';
    
    // Add decorative elements
    $accent_color = imagecolorallocate($image, 180, 160, 180);
    if ($type !== 'banner') {
        // Add corner decorations
        $corner_size = 30;
        imagefilledrectangle($image, 0, 0, $corner_size, 2, $accent_color);
        imagefilledrectangle($image, 0, 0, 2, $corner_size, $accent_color);
        imagefilledrectangle($image, $width - $corner_size, 0, $width, 2, $accent_color);
        imagefilledrectangle($image, $width - 2, 0, $width, $corner_size, $accent_color);
        imagefilledrectangle($image, 0, $height - 2, $corner_size, $height, $accent_color);
        imagefilledrectangle($image, 0, $height - $corner_size, 2, $height, $accent_color);
        imagefilledrectangle($image, $width - $corner_size, $height - 2, $width, $height, $accent_color);
        imagefilledrectangle($image, $width - 2, $height - $corner_size, $width, $height, $accent_color);
    }
    
    // Calculate main text position
    $font_size = ($type === 'banner') ? 40 : 30;
    $box = imagettfbbox($font_size, 0, $font_path, $text);
    $text_width = abs($box[4] - $box[0]);
    $text_height = abs($box[5] - $box[1]);
    $x = ($width - $text_width) / 2;
    $y = ($height - $text_height) / 2;
    
    // Add main text with shadow
    addTextWithShadow($image, $text, $x, $y, $font_size, $font_path, $text_color, $shadow_color);
    
    // Add brand name with shadow
    $brand = "Velvet Vogue";
    $brand_size = ($type === 'banner') ? 25 : 20;
    addTextWithShadow($image, $brand, 20, 40, $brand_size, $font_path, $text_color, $shadow_color);
    // Save image
    imagejpeg($image, $filename, 90);
    imagedestroy($image);
    
    echo "Created image: " . basename($filename) . "\n";
}

// Base directory
$base_dir = __DIR__ . '/assets/images';

// 1. Create banner images
$banner_dir = $base_dir . '/banners';
if (!file_exists($banner_dir)) mkdir($banner_dir, 0777, true);

$banners = [
    'home-hero' => 'Welcome to Velvet Vogue',
    'about-banner' => 'About Us',
    'contact-banner' => 'Contact Us',
    'products-banner' => 'Our Collections',
    'categories-banner' => 'Shop by Category'
];

foreach ($banners as $name => $text) {
    createImage(1920, 600, $text, "$banner_dir/$name.jpg");
}

// 2. Create category images
$category_dir = $base_dir . '/categories';
if (!file_exists($category_dir)) mkdir($category_dir, 0777, true);

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset={$db_config['charset']}",
        $db_config['username'],
        $db_config['password'],
        $pdo_options
    );
    
    $stmt = $pdo->query("SELECT name, slug FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($categories as $category) {
        createImage(800, 600, $category['name'], "$category_dir/{$category['slug']}.jpg");
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

// 3. Create about page images
$about_dir = $base_dir . '/about';
if (!file_exists($about_dir)) mkdir($about_dir, 0777, true);

$about_images = [
    'our-story' => 'Our Story',
    'our-mission' => 'Our Mission',
    'our-team' => 'Our Team',
    'store-interior' => 'Our Store',
    'sustainability' => 'Sustainability'
];

foreach ($about_images as $name => $text) {
    createImage(800, 600, $text, "$about_dir/$name.jpg");
}

// 4. Create contact page images
$contact_dir = $base_dir . '/contact';
if (!file_exists($contact_dir)) mkdir($contact_dir, 0777, true);

$contact_images = [
    'store-location' => 'Visit Our Store',
    'customer-service' => 'Customer Service',
    'contact-map' => 'Store Location Map'
];

foreach ($contact_images as $name => $text) {
    createImage(800, 600, $text, "$contact_dir/$name.jpg");
}

// 5. Create product images (if they don't exist)
$product_dir = $base_dir . '/product_images';
if (!file_exists($product_dir)) mkdir($product_dir, 0777, true);

try {
    $stmt = $pdo->query("SELECT name, image FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($products as $product) {
        $image_path = $product_dir . '/' . $product['image'];
        if (!file_exists($image_path)) {
            createImage(800, 1000, $product['name'], $image_path);
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

// 6. Create/update logo if it doesn't exist
$logo_path = $base_dir . '/logo.png';
if (!file_exists($logo_path)) {
    $width = 300;
    $height = 100;
    $image = imagecreatetruecolor($width, $height);
    
    // Make background transparent
    imagesavealpha($image, true);
    $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
    imagefill($image, 0, 0, $transparent);
    
    // Add text
    $text_color = imagecolorallocate($image, 50, 50, 50);
    $font_size = 40;
    $text = "Velvet Vogue";
    $font_path = __DIR__ . '/assets/fonts/arial.ttf';
    
    // Calculate text position
    $box = imagettfbbox($font_size, 0, $font_path, $text);
    $text_width = abs($box[4] - $box[0]);
    $text_height = abs($box[5] - $box[1]);
    $x = ($width - $text_width) / 2;
    $y = ($height - $text_height) / 2;
    
    // Draw text
    imagettftext($image, $font_size, 0, $x, $y, $text_color, $font_path, $text);
    
    // Save as PNG with transparency
    imagepng($image, $logo_path);
    imagedestroy($image);
    
    echo "Created logo: logo.png\n";
}

echo "All images have been generated successfully!\n";
