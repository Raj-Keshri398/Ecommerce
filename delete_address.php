<?php
session_start();
require_once 'connect.php';
$db = new DBConnect();
$conn = $db->db_handle;

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$address_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Delete only if the address belongs to the logged-in user
$sql = "DELETE FROM addresses WHERE address_id = $address_id AND user_id = " . $_SESSION['user_id'];
if (mysqli_query($conn, $sql)) {
    header("Location: addresshistory.php");
    exit();
} else {
    echo "Error deleting address: " . mysqli_error($conn);
}
?>
