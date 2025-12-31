<?php
session_start();

require_once 'connect.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Unauthorized access.");
}

$user_id = intval($_SESSION['user_id']);
$order_id = intval($_POST['order_id']);
$product_name = $_POST['product_name'];

$db = new DBConnect();
$conn = $db->db_handle;

// ✅ Step 1: Get the matching item from order_items
$sql = "SELECT * FROM order_items 
        WHERE order_id = $order_id 
          AND product_name = ? 
          AND item_status = 'Delivery'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $product_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['message'] = "Item not found or already confirmed.";
    header("Location: order_history.php");
    exit();
}

$item = $result->fetch_assoc();
$product_id = $item['product_id'];
$qty = $item['product_quantity'];

// ✅ Step 2: Update item status to Completed
$update_item = "UPDATE order_items 
                SET item_status = 'Completed' 
                WHERE order_id = $order_id AND product_name = ?";
$stmt = $conn->prepare($update_item);
$stmt->bind_param("s", $product_name);
$stmt->execute();

// ✅ Step 3: Deduct from products table
$update_stock = "UPDATE products 
                 SET product_quantity = product_quantity - ? 
                 WHERE product_id = ?";
$stmt = $conn->prepare($update_stock);
$stmt->bind_param("ii", $qty, $product_id);
$stmt->execute();

// ✅ Step 4 (optional): If all items in this order are 'Completed', update order status
$check_all = mysqli_query($conn, "SELECT COUNT(*) AS pending 
                                  FROM order_items 
                                  WHERE order_id = $order_id AND item_status != 'Completed'");
$pending = mysqli_fetch_assoc($check_all)['pending'];

if ($pending == 0) {
    mysqli_query($conn, "UPDATE orders SET order_status = 'Completed' WHERE order_id = $order_id");
}

// ✅ Step 5: Redirect with message
$_SESSION['message'] = "Thank you! Your delivery has been confirmed.";
header("Location: order_history.php");
exit();
?>