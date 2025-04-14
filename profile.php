<?php
require_once 'session.php';
require_once 'db.php';
requireLogin();

$error = '';
$success = '';

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $profile_image = $_FILES['profile_image'] ?? null;

    if (empty($username)) {
        $error = 'Username is required';
    } else {
        try {
            // Check if username is already taken by another user
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
            $stmt->execute([$username, $_SESSION['user_id']]);
            if ($stmt->fetch()) {
                $error = 'Username already taken';
            } else {
                $update_fields = ['username = ?'];
                $params = [$username];

                // Handle password change
                if (!empty($current_password)) {
                    if (!password_verify($current_password, $user['password'])) {
                        $error = 'Current password is incorrect';
                    } elseif (empty($new_password)) {
                        $error = 'New password is required';
                    } elseif ($new_password !== $confirm_password) {
                        $error = 'New passwords do not match';
                    } else {
                        $update_fields[] = 'password = ?';
                        $params[] = password_hash($new_password, PASSWORD_DEFAULT);
                    }
                }

                // Handle profile image upload
                $profile_image_path = null;
                if ($profile_image && $profile_image['error'] === UPLOAD_ERR_OK) {
                    $allowed_types = ['image/jpeg', 'image/png'];
                    $max_size = 2 * 1024 * 1024; // 2MB

                    if (!in_array($profile_image['type'], $allowed_types)) {
                        $error = 'Only JPG and PNG files are allowed';
                    } elseif ($profile_image['size'] > $max_size) {
                        $error = 'File size must be less than 2MB';
                    } else {
                        $upload_dir = 'uploads/profiles/';
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }

                        $extension = pathinfo($profile_image['name'], PATHINFO_EXTENSION);
                        $filename = uniqid() . '.' . $extension;
                        $profile_image_path = $upload_dir . $filename;

                        if (move_uploaded_file($profile_image['tmp_name'], $profile_image_path)) {
                            // Delete old profile image if exists
                            if ($user['profile_image'] && file_exists($user['profile_image'])) {
                                unlink($user['profile_image']);
                            }
                            $update_fields[] = 'profile_image = ?';
                            $params[] = $profile_image_path;
                        } else {
                            $error = 'Failed to upload profile image';
                        }
                    }
                }

                if (empty($error)) {
                    $params[] = $_SESSION['user_id'];
                    $sql = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    
                    $success = 'Profile updated successfully!';
                    $user['username'] = $username;
                    if ($profile_image_path) {
                        $user['profile_image'] = $profile_image_path;
                    }
                }
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Mini Product Catalog</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>Profile Settings</h1>
        </div>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="profile-container">
            <div class="profile-image-section">
                <?php if ($user['profile_image']): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" class="profile-image">
                <?php else: ?>
                    <div class="profile-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                <?php endif; ?>
            </div>

            <form method="POST" action="" enctype="multipart/form-data" class="profile-form">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="profile_image">Profile Image:</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/png">
                    <small>Max size: 2MB, Allowed formats: JPG, PNG</small>
                </div>

                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password">
                    <small>Leave blank to keep current password</small>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>

                <button type="submit" class="btn">Update Profile</button>
            </form>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 