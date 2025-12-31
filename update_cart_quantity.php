<?php
session_start();
header('Content-Type: application/json');

if (isset($_POST['product_id']) && isset($_POST['product_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = max(1, (int)$_POST['product_quantity']); // prevent 0 or negative

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['product_quantity'] = $new_quantity;

        $product_price = $_SESSION['cart'][$product_id]['product_price'];
        $subtotal = $product_price * $new_quantity;

        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['product_price'] * $item['product_quantity'];
        }

        echo json_encode([
            'subtotal' => number_format($subtotal, 2),
            'total' => number_format($total, 2)
        ]);
    } else {
        echo json_encode(['error' => 'Product not found in cart']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
