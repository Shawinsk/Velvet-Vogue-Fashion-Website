<?php
/**
 * Cart Helper Functions
 * @package VelvetVogue
 */

require_once 'classes/Cart.php';

function getCartItemCount($pdo) {
    $cart = new Cart($pdo);
    return $cart->getItemCount();
}

function formatPrice($price) {
    return 'LKR ' . number_format($price, 2);
}

function addToCart($pdo, $productId, $quantity = 1) {
    $cart = new Cart($pdo);
    return $cart->addToCart($productId, $quantity);
}

function removeFromCart($pdo, $productId) {
    $cart = new Cart($pdo);
    return $cart->removeFromCart($productId);
}

function getCartTotal($pdo) {
    $cart = new Cart($pdo);
    return $cart->getCartTotal();
}

function mergeGuestCart($pdo) {
    if (isset($_SESSION['user_id'])) {
        $cart = new Cart($pdo);
        return $cart->mergeGuestCart();
    }
    return false;
}