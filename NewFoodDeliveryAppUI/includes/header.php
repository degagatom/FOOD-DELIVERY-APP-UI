<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get cart count
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'FoodDelivery - Order Food Online'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-wrapper">
                <!-- Logo -->
                <div class="logo">
                    <a href="index.php">
                        <span class="logo-icon">🍔</span>
                        <span class="logo-text">FoodDelivery</span>
                    </a>
                </div>

                <!-- Search Bar (Desktop) -->
                <div class="search-bar-desktop">
                    <input type="text" id="searchInput" placeholder="Search for restaurants, food..." class="search-input">
                    <button class="search-btn" onclick="handleSearch()">
                        <span>🔍</span>
                    </button>
                </div>

                <!-- Navigation Links -->
                <div class="nav-links">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="restaurant-listing.php" class="nav-link">Restaurants</a>
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="cart.php" class="nav-link cart-link">
                            <span>Cart</span>
                            <?php if ($cartCount > 0): ?>
                                <span class="cart-badge"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="logout.php" class="nav-link">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="nav-link">Login</a>
                        <a href="register.php" class="nav-link btn-primary">Sign Up</a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" id="mobileMenuToggle" onclick="toggleMobileMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

            <!-- Mobile Search Bar -->
            <div class="search-bar-mobile">
                <input type="text" id="searchInputMobile" placeholder="Search for restaurants, food..." class="search-input">
                <button class="search-btn" onclick="handleSearch()">
                    <span>🔍</span>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div class="mobile-menu" id="mobileMenu">
                <a href="index.php" class="mobile-nav-link">Home</a>
                <a href="restaurant-listing.php" class="mobile-nav-link">Restaurants</a>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="cart.php" class="mobile-nav-link">
                        Cart
                        <?php if ($cartCount > 0): ?>
                            <span class="cart-badge"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="logout.php" class="mobile-nav-link">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="mobile-nav-link">Login</a>
                    <a href="register.php" class="mobile-nav-link">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

