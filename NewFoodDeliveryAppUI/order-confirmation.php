<?php
session_start();

// Check if order exists
if (!isset($_SESSION['order'])) {
    header('Location: index.php');
    exit;
}

$order = $_SESSION['order'];
$pageTitle = "Order Confirmed - FoodDelivery";
include 'includes/header.php';

// Calculate order total
$subtotal = 0;
foreach ($order['items'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$deliveryFee = 2.99;
$tax = $subtotal * 0.1;
$total = $subtotal + $deliveryFee + $tax;
?>

<!-- Order Confirmation -->
<section class="order-confirmation">
    <div class="container">
        <div class="confirmation-content">
            <div class="confirmation-icon">✅</div>
            <h1>Order Confirmed!</h1>
            <p class="confirmation-message">Thank you for your order. We're preparing your food now.</p>
            
            <div class="order-details">
                <h2>Order Details</h2>
                <div class="detail-row">
                    <span>Order Number:</span>
                    <span>#<?php echo htmlspecialchars($order['order_number']); ?></span>
                </div>
                <div class="detail-row">
                    <span>Delivery Address:</span>
                    <span><?php echo htmlspecialchars($order['address'] . ', ' . $order['city'] . ', ' . $order['state'] . ' ' . $order['zipCode']); ?></span>
                </div>
                <div class="detail-row">
                    <span>Payment Method:</span>
                    <span><?php echo ucfirst($order['paymentMethod']); ?></span>
                </div>
                <div class="detail-row">
                    <span>Total Amount:</span>
                    <span class="total-amount">$<?php echo number_format($total, 2); ?></span>
                </div>
            </div>

            <div class="confirmation-actions">
                <a href="index.php" class="btn btn-primary">Back to Home</a>
                <a href="restaurant-listing.php" class="btn btn-secondary">Order More</a>
            </div>
        </div>
    </div>
</section>

<?php
// Clear order from session after displaying
unset($_SESSION['order']);
include 'includes/footer.php';
?>

