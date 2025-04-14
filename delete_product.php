<?php
require_once 'session.php';
require_once 'db.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    try {
        // First get the image path
        $stmt = $pdo->prepare("SELECT image_path FROM products WHERE id = ? AND user_id = ?");
        $stmt->execute([$product_id, $_SESSION['user_id']]);
        $product = $stmt->fetch();
        
        if ($product) {
            // Delete the product
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
            $stmt->execute([$product_id, $_SESSION['user_id']]);
            
            // Delete the image file if it exists
            if ($product['image_path'] && file_exists($product['image_path'])) {
                unlink($product['image_path']);
            }
        }
    } catch (PDOException $e) {
        // Log error but don't show to user
        error_log("Delete error: " . $e->getMessage());
    }
}

header('Location: dashboard.php');
exit();
?> 