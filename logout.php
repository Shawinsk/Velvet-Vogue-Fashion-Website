<?php
/**
 * Velvet Vogue - User Logout
 * 
 * This file handles user logout and session cleanup
 */

require_once 'includes/functions.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user was logged in
$was_logged_in = is_logged_in();

// Destroy the session
session_destroy();

// Start a new session for flash message
session_start();

// Set logout success message
if ($was_logged_in) {
    $_SESSION['flash_message'] = 'You have been logged out successfully.';
    $_SESSION['flash_type'] = 'success';
}

// Redirect to login page
header('Location: account_login.php');
exit;
?>