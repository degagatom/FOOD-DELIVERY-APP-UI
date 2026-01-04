<?php
// Handle AJAX add to cart requests
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = isset($_POST['itemId']) ? (int)$_POST['itemId'] : 0;
    $itemName = isset($_POST['itemName']) ? trim($_POST['itemName']) : '';
    $itemPrice = isset($_POST['itemPrice']) ? (float)$_POST['itemPrice'] : 0;
    $restaurant = isset($_POST['restaurant']) ? trim($_POST['restaurant']) : '';

    if ($itemId > 0 && !empty($itemName) && $itemPrice > 0) {
        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if item already exists in cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $itemId) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        // Add new item if not found
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $itemId,
                'name' => $itemName,
                'price' => $itemPrice,
                'restaurant' => $restaurant,
                'quantity' => 1
            ];
        }

        echo json_encode([
            'success' => true,
            'cartCount' => count($_SESSION['cart']),
            'message' => 'Item added to cart!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid item data.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>

