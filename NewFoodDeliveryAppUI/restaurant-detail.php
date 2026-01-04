<?php
require_once 'config.php';

// Get restaurant ID from URL
$restaurantId = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Load restaurant from database
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT id, name, cuisine, rating, delivery_time, icon FROM restaurants WHERE id = ? AND is_active = 1");
$stmt->bind_param("i", $restaurantId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Restaurant not found, redirect to listing
    header('Location: restaurant-listing.php');
    exit;
}

$restaurant = $result->fetch_assoc();
$stmt->close();

$pageTitle = $restaurant['name'] . " - FoodDelivery";
include 'includes/header.php';
?>

<!-- Restaurant Banner -->
<section class="restaurant-banner">
    <div class="container">
        <div class="banner-content">
            <div class="banner-image">
                <div class="image-placeholder large"><?php echo htmlspecialchars($restaurant['icon']); ?></div>
            </div>
            <div class="banner-info">
                <h1><?php echo $restaurant['name']; ?></h1>
                <p class="restaurant-cuisine"><?php echo $restaurant['cuisine']; ?></p>
                <div class="restaurant-meta">
                <span class="rating">⭐ <?php echo number_format($restaurant['rating'], 1); ?></span>
                <span class="delivery-time"><?php echo htmlspecialchars($restaurant['delivery_time']); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Menu Section -->
<section class="menu-section">
    <div class="container">
        <h2 class="section-title">Menu</h2>
        
        <div class="menu-category">
            <h3 class="menu-category-title">Popular</h3>
            <div class="menu-items">
                <?php
                // Load menu items from database
                $stmt = $conn->prepare("SELECT id, name, description, price, category FROM menu_items WHERE restaurant_id = ? AND is_available = 1 ORDER BY category, name");
                $stmt->bind_param("i", $restaurantId);
                $stmt->execute();
                $menuResult = $stmt->get_result();
                
                // Group menu items by category
                $menuByCategory = [];
                while ($item = $menuResult->fetch_assoc()) {
                    $category = $item['category'] ?: 'Popular';
                    if (!isset($menuByCategory[$category])) {
                        $menuByCategory[$category] = [];
                    }
                    $menuByCategory[$category][] = $item;
                }
                $stmt->close();
                
                // Display menu items grouped by category
                foreach ($menuByCategory as $category => $menuItems):
                ?>
                <div class="menu-category">
                    <h3 class="menu-category-title"><?php echo htmlspecialchars($category); ?></h3>
                    <div class="menu-items">
                <?php
                foreach ($menuItems as $item):
                ?>
                <div class="menu-item">
                    <div class="menu-item-content">
                        <div class="menu-item-info">
                            <h4><?php echo $item['name']; ?></h4>
                            <p class="menu-item-description"><?php echo $item['description']; ?></p>
                            <span class="price">$<?php echo number_format($item['price'], 2); ?></span>
                        </div>
                        <button class="btn btn-primary" onclick="addToCart(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>', <?php echo $item['price']; ?>, '<?php echo htmlspecialchars($restaurant['name'], ENT_QUOTES); ?>')">
                            Add to Cart
                        </button>
                    </div>
                </div>
                <?php 
                endforeach;
                ?>
                    </div>
                </div>
                <?php 
                endforeach;
                
                // If no menu items found
                if (empty($menuByCategory)):
                ?>
                <div class="menu-category">
                    <p>No menu items available at this time.</p>
                </div>
                <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

