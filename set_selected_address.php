<?php
session_start();

require_once 'connect.php';
$db = new DBConnect();
$conn = $db->db_handle;


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['selected_address_id'])) {
    $_SESSION['selected_address_id'] = $_POST['selected_address_id'];
    header("Location: order_place_summary.php");
    exit();
} else {
    echo "No address selected. <a href='addresshistory.php'>Go back</a>";
}
?>
