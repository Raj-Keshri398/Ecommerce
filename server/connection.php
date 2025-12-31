<?php
$conn = new mysqli("localhost", "root", "", "e-commerce");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
