<?php
session_start();

require_once 'connect.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['order_id']) || !isset($_POST['product_name'])) {
    header("Location: login.php");
    exit();
}

$db = new DBConnect();
$conn = $db->db_handle;

$order_id = intval($_POST['order_id']);
$product_name = $conn->real_escape_string($_POST['product_name']);
$user_id = intval($_SESSION['user_id']);

// Update status to Cancelled (stock is not touched)
$sql = "
UPDATE order_items
SET item_status = 'Cancelled'
WHERE order_id = $order_id 
  AND product_name = '$product_name'
  AND user_id = $user_id
  AND item_status IN ('Pending', 'Delivery')";

if (mysqli_query($conn, $sql)) {
    $_SESSION['message'] = "Product has been cancelled successfully.";
} else {
    $_SESSION['message'] = "Failed to cancel the product.";
}

header("Location: order_history.php");
exit();
?>
