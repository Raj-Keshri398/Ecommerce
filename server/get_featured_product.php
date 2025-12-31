<?php
// Include the database connection
require_once 'connect.php';  // Using require_once to prevent multiple inclusions

// Create an instance of DBConnect class
$db = new DBConnect();
$conn = $db->db_handle;  // Get the connection object

// Fetch Garam Masala products (LIMIT 8)
$stmt_garam = $conn->prepare("SELECT * FROM products WHERE product_category = 'Garam Masala' LIMIT 8");
$stmt_garam->execute();
$garam_masala_products = $stmt_garam->get_result();  // Get results

// Fetch Seeds products (LIMIT 8)
$stmt_seeds = $conn->prepare("SELECT * FROM products WHERE product_category = 'Seeds' LIMIT 8");
$stmt_seeds->execute();
$seeds_products = $stmt_seeds->get_result();  // Get results

?>
