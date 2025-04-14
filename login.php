<?php
// Include required files
require_once 'session.php';
require_once 'db.php';

// Ensure only guests can access this page
requireGuest();

// Initialize error message
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate form data
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        // Prepare and execute query to find user
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Verify password and set session if valid
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mini Product Catalog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Login Form Container -->
    <div class="auth-container">
        <h1>Login</h1>
        
        <!-- Display error message if any -->
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <!-- Login Form -->
        <form method="POST" action="" class="auth-form">
            <!-- Username Field -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <!-- Password Field -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" class="btn">Login</button>
            
            <!-- Registration Link -->
            <p class="text-center">Don't have an account? <a href="register.php">Register here</a></p>
        </form>
    </div>
</body>
</html> 