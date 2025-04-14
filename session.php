<?php
// Start or resume the session
session_start();

/**
 * Checks if a user is currently logged in
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Ensures user is logged in, redirects to login page if not
 * Used to protect pages that require authentication
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

/**
 * Ensures user is NOT logged in, redirects to dashboard if logged in
 * Used to protect login/register pages from logged-in users
 */
function requireGuest() {
    if (isLoggedIn()) {
        header('Location: dashboard.php');
        exit();
    }
}
?> 