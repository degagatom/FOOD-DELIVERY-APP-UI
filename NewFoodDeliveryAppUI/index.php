<?php
require_once 'config.php';

$pageTitle = "Home - FoodDelivery";
include 'includes/header.php';

// Load featured restaurants from database
$conn = getDBConnection();
$featuredQuery = "SELECT id, name, cuisine, rating, delivery_time, icon FROM restaurants WHERE is_featured = 1 AND is_active = 1 ORDER BY rating DESC LIMIT 4";
$featuredResult = $conn->query($featuredQuery);
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Delicious Food, Delivered Fast</h1>
            <p class="hero-subtitle">Order from your favorite restaurants and get it delivered to your doorstep</p>
            <a href="restaurant-listing.php" class="btn btn-primary btn-large">Order Now</a>
        </div>
        <div class="hero-image">
            <div class="hero-image-placeholder">
                <span>🍕🍔🍜</span>
            </div>
        </div>
    </div>
</section>

<!-- Food Categories Section -->
<section class="categories">
    <div class="container">
        <h2 class="section-title">Browse by Category</h2>
        <div class="categories-grid">
            <div class="category-card" onclick="filterByCategory('pizza')">
                <div class="category-icon">🍕</div>
                <h3>Pizza</h3>
            </div>
            <div class="category-card" onclick="filterByCategory('burger')">
                <div class="category-icon">🍔</div>
                <h3>Burgers</h3>
            </div>
            <div class="category-card" onclick="filterByCategory('sushi')">
                <div class="category-icon">🍣</div>
                <h3>Sushi</h3>
            </div>
            <div class="category-card" onclick="filterByCategory('chinese')">
                <div class="category-icon">🥡</div>
                <h3>Chinese</h3>
            </div>
            <div class="category-card" onclick="filterByCategory('indian')">
                <div class="category-icon">🍛</div>
                <h3>Indian</h3>
            </div>
            <div class="category-card" onclick="filterByCategory('drinks')">
                <div class="category-icon">🥤</div>
                <h3>Drinks</h3>
            </div>
            <div class="category-card" onclick="filterByCategory('dessert')">
                <div class="category-icon">🍰</div>
                <h3>Desserts</h3>
            </div>
            <div class="category-card" onclick="filterByCategory('salad')">
                <div class="category-icon">🥗</div>
                <h3>Salads</h3>
            </div>
        </div>
    </div>
</section>

<!-- Featured Restaurants Section -->
<section class="featured-restaurants">
    <div class="container">
        <h2 class="section-title">Featured Restaurants</h2>
        <div class="restaurants-grid">
            <?php
            if ($featuredResult && $featuredResult->num_rows > 0):
                while ($restaurant = $featuredResult->fetch_assoc()):
            ?>
            <div class="restaurant-card" onclick="window.location.href='restaurant-detail.php?id=<?php echo $restaurant['id']; ?>'">
                <div class="restaurant-image">
                    <div class="image-placeholder"><?php echo htmlspecialchars($restaurant['icon']); ?></div>
                    <span class="restaurant-badge">Popular</span>
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
            <p>No featured restaurants available.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Popular Food Items Section -->
<section class="popular-items">
    <div class="container">
        <h2 class="section-title">Popular Items</h2>
        <div class="items-grid">
            <div class="food-item-card">
                <div class="food-item-image">
                    <div class="image-placeholder">🍕</div>
                </div>
                <div class="food-item-info">
                    <h4>Margherita Pizza</h4>
                    <p class="food-item-restaurant">Pizza Palace</p>
                    <div class="food-item-footer">
                        <span class="price">$12.99</span>
                        <button class="btn btn-primary btn-small" onclick="addToCart(1, 'Margherita Pizza', 12.99, 'Pizza Palace')">Add</button>
                    </div>
                </div>
            </div>

            <div class="food-item-card">
                <div class="food-item-image">
                    <div class="image-placeholder">🍔</div>
                </div>
                <div class="food-item-info">
                    <h4>Classic Burger</h4>
                    <p class="food-item-restaurant">Burger King</p>
                    <div class="food-item-footer">
                        <span class="price">$8.99</span>
                        <button class="btn btn-primary btn-small" onclick="addToCart(2, 'Classic Burger', 8.99, 'Burger King')">Add</button>
                    </div>
                </div>
            </div>

            <div class="food-item-card">
                <div class="food-item-image">
                    <div class="image-placeholder">🍣</div>
                </div>
                <div class="food-item-info">
                    <h4>Salmon Sushi Roll</h4>
                    <p class="food-item-restaurant">Sushi Express</p>
                    <div class="food-item-footer">
                        <span class="price">$15.99</span>
                        <button class="btn btn-primary btn-small" onclick="addToCart(3, 'Salmon Sushi Roll', 15.99, 'Sushi Express')">Add</button>
                    </div>
                </div>
            </div>

            <div class="food-item-card">
                <div class="food-item-image">
                    <div class="image-placeholder">🍛</div>
                </div>
                <div class="food-item-info">
                    <h4>Butter Chicken</h4>
                    <p class="food-item-restaurant">Spice Garden</p>
                    <div class="food-item-footer">
                        <span class="price">$14.99</span>
                        <button class="btn btn-primary btn-small" onclick="addToCart(4, 'Butter Chicken', 14.99, 'Spice Garden')">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

