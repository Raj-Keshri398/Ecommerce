<?php
session_start();


// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

require_once 'connect.php';

// Initialize DB connection
$db = new DBConnect();
$conn = $db->db_handle;

// Fetch user details securely
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT user_name, user_email, user_phone FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows === 0) {
    die("User not found.");
}
$user = $user_result->fetch_assoc();

// Cart item count
$count = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['product_quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Summary | Heritage Aromas</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="Assests/Css/style.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        .summary-card {
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 25px;
        }
        img.card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include('navbar.php'); ?>

    <!-- Main Content -->
    <div class="container my-5 pt-5">
        <h2 class="mb-4 text-center">Account Summary</h2>

        <!-- User Info -->
        <div class="summary-card bg-light">
            <h4 class="mb-3">Account Information</h4>
            <div class="row">
                <div class="col-md-4"><strong>Name:</strong> <?= htmlspecialchars($user['user_name']) ?></div>
                <div class="col-md-4"><strong>Email:</strong> <?= htmlspecialchars($user['user_email']) ?></div>
                <div class="col-md-4"><strong>Mobile:</strong> <?= htmlspecialchars($user['user_phone']) ?></div>
            </div>
        </div>

        <!-- User Actions -->
        <div class="d-flex flex-wrap gap-2">
            <a href="order_history.php" class="btn btn-primary">Order History</a>
            <a href="wishlist.php" class="btn btn-secondary">Wishlist</a>
            <a href="logout.php" class="btn btn-danger ms-auto">Logout</a>
        </div>
    </div>

    <!-- Footer -->
    <?php include('footer.php'); ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
