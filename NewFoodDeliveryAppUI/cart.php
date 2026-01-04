<?php
$pageTitle = "Cart - FoodDelivery";
include 'includes/header.php';
?>

<!-- Cart Page -->
<section class="cart-page">
    <div class="container">
        <h1 class="page-title">Your Cart</h1>
        
        <?php if (empty($_SESSION['cart'])): ?>
            <!-- Empty Cart -->
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <h2>Your cart is empty</h2>
                <p>Add some delicious items to get started!</p>
                <a href="restaurant-listing.php" class="btn btn-primary">Browse Restaurants</a>
            </div>
        <?php else: ?>
            <div class="cart-wrapper">
                <!-- Cart Items -->
                <div class="cart-items" id="cartItems">
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $index => $item):
                        $itemTotal = $item['price'] * $item['quantity'];
                        $total += $itemTotal;
                    ?>
                    <div class="cart-item" data-index="<?php echo $index; ?>">
                        <div class="cart-item-image">
                            <div class="image-placeholder small">🍽️</div>
                        </div>
                        <div class="cart-item-info">
                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                            <p class="cart-item-restaurant"><?php echo htmlspecialchars($item['restaurant']); ?></p>
                            <span class="cart-item-price">$<?php echo number_format($item['price'], 2); ?></span>
                        </div>
                        <div class="cart-item-controls">
                            <button class="quantity-btn" onclick="updateQuantity(<?php echo $index; ?>, -1)">-</button>
                            <span class="quantity"><?php echo $item['quantity']; ?></span>
                            <button class="quantity-btn" onclick="updateQuantity(<?php echo $index; ?>, 1)">+</button>
                        </div>
                        <div class="cart-item-total">
                            <span class="item-total">$<?php echo number_format($itemTotal, 2); ?></span>
                        </div>
                        <button class="remove-btn" onclick="removeFromCart(<?php echo $index; ?>)">×</button>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="subtotal">$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee</span>
                        <span>$2.99</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax</span>
                        <span id="tax">$<?php echo number_format($total * 0.1, 2); ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span id="cartTotal">$<?php echo number_format($total + 2.99 + ($total * 0.1), 2); ?></span>
                    </div>
                    <a href="checkout.php" class="btn btn-primary btn-large btn-block">Proceed to Checkout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

