<?php
session_start();

// Sirf seller ka session remove karo
unset($_SESSION['seller_id']);
unset($_SESSION['name']);

// Redirect to seller login
header("Location: seller_login_register.php");
exit;
?>
