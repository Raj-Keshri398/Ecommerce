<?php
session_start();

// Sirf customer ka session remove karo
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);

// Redirect to customer login
header("Location: login.php");
exit;
?>
