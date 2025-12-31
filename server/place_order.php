<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
require_once '../connect.php';

// Redirect if accessed directly without form submission
if (!isset($_POST['payment_successfully'])) {
    header("Location: ../payment.php");
    exit();
}

$db = new DBConnect();
$conn = $db->db_handle;

// Get session data
$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? []; // format: [product_id => full product array]
$address_id = $_SESSION['selected_address_id'];
$order_date = date('Y-m-d H:i:s');

// Handle empty cart
if (empty($cart)) {
    die("Cart is empty.");
}

// Get payment info
$payment_method = $_POST['payment_method'] ?? '';
$upi_app = $_POST['upi_app'] ?? '';
$card_number = $_POST['card_number'] ?? '';
$expiry = $_POST['expiry'] ?? '';
$cvv = $_POST['cvv'] ?? '';

$order_status = 'Pending';

// Prepare payment details string
$payment_details = ($payment_method === "UPI") ? "UPI App: $upi_app" :
    (in_array($payment_method, ["Debit Card", "Credit Card"]) ? "Card ending with " . substr($card_number, -4) : $payment_method);

// Get address info
$phone = $city = $address = '';
if ($address_id) {
    $stmt_addr = $conn->prepare("SELECT phone, city, address FROM addresses WHERE address_id = ? AND user_id = ?");
    $stmt_addr->bind_param("ii", $address_id, $user_id);
    $stmt_addr->execute();
    $stmt_addr->bind_result($phone, $city, $address);
    $stmt_addr->fetch();
    $stmt_addr->close();
}

// Prepare SQL queries
$order_sql = "INSERT INTO orders 
    (custom_order_id, user_id, user_phone, user_city, user_address, order_cost, order_status, order_date,
     payment_method, payment_details, upi_app, card_number, expiry, cvv)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$item_sql = "INSERT INTO order_items 
    (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date, item_total)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Loop through the cart and insert each product as a separate order
foreach ($cart as $item) {
    $pid = $item['product_id'];
    $product_name = $item['product_name'];
    $product_price = floatval($item['product_price']);
    $product_image = $item['product_image'];
    $qty_int = intval($item['product_quantity']);
    $item_total = $product_price * $qty_int;

    // Generate custom order ID
    $prefix = strtoupper(substr(preg_replace("/[^A-Za-z]/", '', $product_name), 0, 3));
    $prefix = str_pad($prefix, 3, 'X'); // In case name is too short
    $custom_order_id = $prefix . rand(100000, 999999);

    // Insert into orders table
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param(
        "sisssdssssssss",
        $custom_order_id,
        $user_id,
        $phone,
        $city,
        $address,
        $item_total,
        $order_status,
        $order_date,
        $payment_method,
        $payment_details,
        $upi_app,
        $card_number,
        $expiry,
        $cvv
    );

    if (!$stmt->execute()) {
        die("Order insert failed: " . $stmt->error);
    }

    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert into order_items table
    $item_stmt = $conn->prepare($item_sql);
    $item_stmt->bind_param(
        "iissdiisd", 
        $order_id,
        $pid,
        $product_name,
        $product_image,
        $product_price,
        $qty_int,
        $user_id,
        $order_date,
        $item_total
    );

    if (!$item_stmt->execute()) {
        die("Item insert failed: " . $item_stmt->error);
    }

    $item_stmt->close();
}

// Clear session data
unset($_SESSION['cart'], $_SESSION['total'], $_SESSION['selected_address_id']);

// Redirect to home with alert
header("Location: ../order_success.php");
exit();

?>
