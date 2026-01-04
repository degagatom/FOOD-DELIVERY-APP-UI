<?php
require_once 'config.php';

$pageTitle = "Restaurants - FoodDelivery";
include 'includes/header.php';

// Get category filter from URL
$categoryFilter = isset($_GET['category']) ? strtolower($_GET['category']) : 'all';

// Load restaurants from database
$conn = getDBConnection();
$query = "SELECT id, name, cuisine, rating, delivery_time, icon, is_featured FROM restaurants WHERE is_active = 1 ORDER BY is_featured DESC, name";
$result = $conn->query($query);
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>All Restaurants</h1>
        <p>Discover amazing restaurants near you</p>
    </div>
</section>

<!-- Filters Section -->
<section class="filters-section">
    <div class="container">
        <div class="filters-wrapper">
            <div class="filter-tabs">
                <button class="filter-tab <?php echo $categoryFilter === 'all' ? 'active' : ''; ?>" onclick="filterRestaurants('all')">All</button>
                <button class="filter-tab <?php echo $categoryFilter === 'pizza' ? 'active' : ''; ?>" onclick="filterRestaurants('pizza')">Pizza</button>
                <button class="filter-tab <?php echo $categoryFilter === 'burger' ? 'active' : ''; ?>" onclick="filterRestaurants('burger')">Burgers</button>
                <button class="filter-tab <?php echo $categoryFilter === 'sushi' ? 'active' : ''; ?>" onclick="filterRestaurants('sushi')">Sushi</button>
                <button class="filter-tab <?php echo $categoryFilter === 'chinese' ? 'active' : ''; ?>" onclick="filterRestaurants('chinese')">Chinese</button>
                <button class="filter-tab <?php echo $categoryFilter === 'indian' ? 'active' : ''; ?>" onclick="filterRestaurants('indian')">Indian</button>
            </div>
        </div>
    </div>
</section>

<!-- Restaurants Grid -->
<section class="restaurants-listing">
    <div class="container">
        <div class="restaurants-grid" id="restaurantsGrid">
            <?php
            if ($result && $result->num_rows > 0):
                while ($restaurant = $result->fetch_assoc()):
                    // Determine category for filtering (extract from cuisine)
                    $cuisineLower = strtolower($restaurant['cuisine']);
                    $dataCategory = 'all';
                    if (strpos($cuisineLower, 'pizza') !== false) $dataCategory = 'pizza';
                    elseif (strpos($cuisineLower, 'burger') !== false) $dataCategory = 'burger';
                    elseif (strpos($cuisineLower, 'sushi') !== false) $dataCategory = 'sushi';
                    elseif (strpos($cuisineLower, 'chinese') !== false || strpos($cuisineLower, 'asian') !== false) $dataCategory = 'chinese';
                    elseif (strpos($cuisineLower, 'indian') !== false || strpos($cuisineLower, 'curry') !== false) $dataCategory = 'indian';
            ?>
            <div class="restaurant-card" data-category="<?php echo $dataCategory; ?>" onclick="window.location.href='restaurant-detail.php?id=<?php echo $restaurant['id']; ?>'">
                <div class="restaurant-image">
                    <div class="image-placeholder"><?php echo htmlspecialchars($restaurant['icon']); ?></div>
                    <?php if ($restaurant['is_featured']): ?>
                        <span class="restaurant-badge">Popular</span>
                    <?php endif; ?>
                </div>
                <div class="restaurant-info">
                    <h3><?php echo htmlspecialchars($restaurant['name']); ?></h3>
                    <div class="restaurant-meta">
                        <span class="rating">⭐ <?php echo number_format($restaurant['rating'], 1); ?></span>
                        <span class="delivery-time"><?php echo htmlspecialchars($restaurant['delivery_time']); ?></span>
                    </div>
                    <p class="restaurant-cuisine"><?php echo htmlspecialchars($restaurant['cuisine']); ?></p>
                </div>
            </div>
            <?php
                endwhile;
            else:
            ?>
            <div class="empty-state">
                <p>No restaurants found.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

