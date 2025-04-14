<?php
require_once 'session.php';
require_once 'db.php';
requireLogin();

$error = '';
$success = '';

// Get product ID from URL
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    header('Location: dashboard.php');
    exit();
}

try {
    // Get product details
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND user_id = ?");
    $stmt->execute([$product_id, $_SESSION['user_id']]);
    $product = $stmt->fetch();

    if (!$product) {
        header('Location: dashboard.php');
        exit();
    }
} catch (PDOException $e) {
    $error = 'Database error: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $image = $_FILES['image'] ?? null;

    if (empty($name) || empty($description) || empty($price)) {
        $error = 'Please fill in all required fields';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = 'Please enter a valid price';
    } else {
        $image_path = $product['image_path']; // Keep existing image by default
        
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png'];
            $max_size = 2 * 1024 * 1024; // 2MB

            if (!in_array($image['type'], $allowed_types)) {
                $error = 'Only JPG and PNG files are allowed';
            } elseif ($image['size'] > $max_size) {
                $error = 'File size must be less than 2MB';
            } else {
                $upload_dir = 'uploads/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $new_image_path = $upload_dir . $filename;

                if (move_uploaded_file($image['tmp_name'], $new_image_path)) {
                    // Delete old image if it exists
                    if ($product['image_path'] && file_exists($product['image_path'])) {
                        unlink($product['image_path']);
                    }
                    $image_path = $new_image_path;
                } else {
                    $error = 'Failed to upload image';
                }
            }
        }

        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_path = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$name, $description, $price, $image_path, $product_id, $_SESSION['user_id']]);
                
                $success = 'Product updated successfully!';
                $product = [
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'image_path' => $image_path
                ];
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Mini Product Catalog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Edit Product</h1>
            <nav>
                <a href="dashboard.php" class="btn">Back to Dashboard</a>
            </nav>
        </header>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data" class="product-form">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price ($):</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>

            <div class="form-group">
                <label for="image">Product Image:</label>
                <?php if ($product['image_path']): ?>
                    <div class="current-image">
                        <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Current product image" style="max-width: 200px; margin-bottom: 10px;">
                    </div>
                <?php endif; ?>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png">
                <small>Max size: 2MB, Allowed formats: JPG, PNG</small>
            </div>

            <button type="submit" class="btn">Update Product</button>
        </form>
    </div>
</body>
</html> 