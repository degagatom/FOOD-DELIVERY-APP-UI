<?php
// Handle cart update requests
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $index = isset($_POST['index']) ? (int)$_POST['index'] : -1;

    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty.']);
        exit;
    }

    if ($action === 'update' && $index >= 0 && $index < count($_SESSION['cart'])) {
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        if ($quantity <= 0) {
            // Remove item
            array_splice($_SESSION['cart'], $index, 1);
        } else {
            $_SESSION['cart'][$index]['quantity'] = $quantity;
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $deliveryFee = 2.99;
        $tax = $subtotal * 0.1;
        $total = $subtotal + $deliveryFee + $tax;

        echo json_encode([
            'success' => true,
            'cartCount' => count($_SESSION['cart']),
            'subtotal' => number_format($subtotal, 2),
            'tax' => number_format($tax, 2),
            'total' => number_format($total, 2)
        ]);
    } elseif ($action === 'remove' && $index >= 0 && $index < count($_SESSION['cart'])) {
        array_splice($_SESSION['cart'], $index, 1);

        // Calculate totals
        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $deliveryFee = 2.99;
        $tax = $subtotal * 0.1;
        $total = $subtotal + $deliveryFee + $tax;

        echo json_encode([
            'success' => true,
            'cartCount' => count($_SESSION['cart']),
            'subtotal' => number_format($subtotal, 2),
            'tax' => number_format($tax, 2),
            'total' => number_format($total, 2)
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action or index.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>

