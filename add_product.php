<?php
require_once 'session.php';
require_once 'db.php';
requireLogin();

$error = '';
$success = '';

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
        $image_path = null;
        
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
                $image_path = $upload_dir . $filename;

                if (!move_uploaded_file($image['tmp_name'], $image_path)) {
                    $error = 'Failed to upload image';
                }
            }
        }

        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO products (user_id, name, description, price, image_path) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], $name, $description, $price, $image_path]);
                $success = 'Product added successfully!';
                
                // Clear form
                $name = $description = $price = '';
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
    <title>Add Product - Mini Product Catalog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Add New Product</h1>
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
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($description ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price ($):</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($price ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="image">Product Image:</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png">
                <small>Max size: 2MB, Allowed formats: JPG, PNG</small>
            </div>

            <button type="submit" class="btn">Add Product</button>
        </form>
    </div>
</body>
</html> 