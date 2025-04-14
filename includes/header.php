<?php
// Get current page name for active link highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final-Simulation</title>
    <!-- Bootstrap CSS for responsive design -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<!-- Main Header Section -->
<header class="main-header">
    <div class="container">
        <div class="header-content">
            <!-- Logo Section -->
            <div class="logo">
                <h1>Final-Simulation</h1>
            </div>
            
            <!-- Navigation Menu -->
            <nav class="main-nav">
                <!-- Home Link -->
                <a href="dashboard.php" class="nav-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Home
                </a>
                
                <!-- Products Link -->
                <a href="products.php" class="nav-link <?php echo $current_page === 'products.php' ? 'active' : ''; ?>">
                    <i class="fas fa-box"></i> Products
                </a>
                
                <!-- Profile Link -->
                <a href="profile.php" class="nav-link <?php echo $current_page === 'profile.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user"></i> Profile
                </a>
                
                <!-- User Info and Logout (only shown when logged in) -->
                <?php if (isLoggedIn()): ?>
                    <!-- Display Username -->
                    <span class="username">
                        <i class="fas fa-user-circle"></i> <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?>
                    </span>
                    
                    <!-- Logout Button -->
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>
</body>
</html> 