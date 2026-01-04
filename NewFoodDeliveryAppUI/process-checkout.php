<?php
// Start session
session_start();
require_once 'config.php';

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user is logged in
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }

    // Validate cart is not empty
    if (empty($_SESSION['cart'])) {
        header('Location: cart.php');
        exit;
    }

    // Get form data
    $fullName = isset($_POST['fullName']) ? trim($_POST['fullName']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';
    $state = isset($_POST['state']) ? trim($_POST['state']) : '';
    $zipCode = isset($_POST['zipCode']) ? trim($_POST['zipCode']) : '';
    $instructions = isset($_POST['instructions']) ? trim($_POST['instructions']) : '';
    $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : 'card';

    // Basic validation
    if (empty($fullName) || empty($phone) || empty($address) || empty($city) || empty($state) || empty($zipCode)) {
        $_SESSION['checkout_error'] = 'Please fill in all required fields.';
        header('Location: checkout.php');
        exit;
    }

    // Calculate totals
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $deliveryFee = 2.99;
    $tax = $subtotal * 0.1;
    $total = $subtotal + $deliveryFee + $tax;

    // Generate unique order number
    $orderNumber = 'ORD' . date('Ymd') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

    // Connect to database
    $conn = getDBConnection();
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Get user ID
        $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : NULL;
        
        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, full_name, phone, address, city, state, zip_code, instructions, payment_method, subtotal, delivery_fee, tax, total, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("isssssssssdddd", $userId, $orderNumber, $fullName, $phone, $address, $city, $state, $zipCode, $instructions, $paymentMethod, $subtotal, $deliveryFee, $tax, $total);
        $stmt->execute();
        $orderId = $conn->insert_id;
        $stmt->close();
        
        // Insert order items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, item_name, item_price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($_SESSION['cart'] as $item) {
            $itemSubtotal = $item['price'] * $item['quantity'];
            $stmt->bind_param("iisidi", $orderId, $item['id'], $item['name'], $item['price'], $item['quantity'], $itemSubtotal);
            $stmt->execute();
        }
        $stmt->close();
        
        // Commit transaction
        $conn->commit();
        
        // Store order information in session for confirmation page
        $_SESSION['order'] = [
            'id' => $orderId,
            'order_number' => $orderNumber,
            'fullName' => $fullName,
            'phone' => $phone,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'zipCode' => $zipCode,
            'instructions' => $instructions,
            'paymentMethod' => $paymentMethod,
            'items' => $_SESSION['cart'],
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'tax' => $tax,
            'total' => $total,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // Clear cart after successful order
        $_SESSION['cart'] = [];

        // Redirect to order confirmation page
        header('Location: order-confirmation.php');
        exit;
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $_SESSION['checkout_error'] = 'Order processing failed. Please try again.';
        header('Location: checkout.php');
        exit;
    }
} else {
    header('Location: checkout.php');
    exit;
}
?>

