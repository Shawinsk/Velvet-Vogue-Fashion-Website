<?php
/**
 * Velvet Vogue - User Registration Redirect
 * 
 * This file redirects to the login page with registration form
 */

// Redirect to login page with register parameter
header('Location: account_login.php?register=1');
exit;
?>