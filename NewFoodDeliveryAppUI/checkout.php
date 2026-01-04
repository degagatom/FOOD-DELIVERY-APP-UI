<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Redirect to cart if cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$pageTitle = "Checkout - FoodDelivery";
include 'includes/header.php';

// Calculate totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$deliveryFee = 2.99;
$tax = $subtotal * 0.1;
$total = $subtotal + $deliveryFee + $tax;
?>

<!-- Checkout Page -->
<section class="checkout-page">
    <div class="container">
        <h1 class="page-title">Checkout</h1>
        
        <div class="checkout-wrapper">
            <!-- Delivery Address Form -->
            <div class="checkout-section">
                <h2>Delivery Address</h2>
                <form action="process-checkout.php" method="POST" class="checkout-form">
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" name="fullName" required placeholder="John Doe">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required placeholder="+1 234 567 8900">
                    </div>
                    <div class="form-group">
                        <label for="address">Street Address</label>
                        <input type="text" id="address" name="address" required placeholder="123 Main Street">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" required placeholder="New York">
                        </div>
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" id="state" name="state" required placeholder="NY">
                        </div>
                        <div class="form-group">
                            <label for="zipCode">ZIP Code</label>
                            <input type="text" id="zipCode" name="zipCode" required placeholder="10001">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="instructions">Delivery Instructions (Optional)</label>
                        <textarea id="instructions" name="instructions" rows="3" placeholder="Leave at door, ring bell, etc."></textarea>
                    </div>

                    <!-- Payment Options -->
                    <div class="payment-section">
                        <h2>Payment Method</h2>
                        <div class="payment-options">
                            <label class="payment-option">
                                <input type="radio" name="paymentMethod" value="card" checked>
                                <span>💳 Credit/Debit Card</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="paymentMethod" value="cash">
                                <span>💵 Cash on Delivery</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="paymentMethod" value="paypal">
                                <span>🅿️ PayPal</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-large btn-block">Place Order</button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="order-summary-sidebar">
                <h2>Order Summary</h2>
                <div class="order-items">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="order-item">
                        <span class="order-item-name"><?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['quantity']; ?></span>
                        <span class="order-item-price">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="order-totals">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee</span>
                        <span>$<?php echo number_format($deliveryFee, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Tax</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

