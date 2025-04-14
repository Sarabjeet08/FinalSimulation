<?php
// Include required files
require_once 'session.php';

// Check if user is logged in
if (isLoggedIn()) {
    // Redirect to dashboard if logged in
    header('Location: dashboard.php');
} else {
    // Redirect to login page if not logged in
    header('Location: login.php');
}
exit();
?> 