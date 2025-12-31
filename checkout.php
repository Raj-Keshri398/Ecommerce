<?php
session_start(); // Start session to access session variables


if (!empty($_SESSION['cart'])) {
    // Cart has products, continue to checkout page
} else {
    // Cart empty, send to home
    header('Location: index.php');
    exit();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Calculate cart item count
$count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['product_quantity'];
    }
}

// Calculate total amount
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['product_price'] * $item['product_quantity'];
    }
    $_SESSION['total'] = $total;

}



?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Heritage Aromas - Premium quality products with best prices">
    <title>checkout</title>
    
    <!-- Preload critical resources -->
    <!-- Bootstrap CSS (preloaded for performance) -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"></noscript>
    
    <!-- Font Awesome -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"></noscript>
    
    <!-- Custom CSS -->
    <link rel="preload" href="Assests/Css/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="Assests/Css/checkout.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap"></noscript>
    <style>
        #upi-options,
        #debit-card,
        #credit-card {
            display: none;
        }
    </style>
</head>
<body>

    <!-- Navigation - Using Bootstrap navbar component -->
    <?php include('navbar.php'); ?>

    <!--checkout-->
    <section class="checkout-section">
            <div class="container mt-5">
            <h2 class="text-center">Checkout</h2>
            <form id="checkout-form" method="POST" action="payment.php">
                <!-- Basic Info -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Name</label>
                        <input type="text" name="name" required class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" required class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Phone</label>
                        <input type="tel" name="phone" required class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>City</label>
                        <input type="text" name="city" required class="form-control">
                    </div>
                    <div class="col-12 mb-3">
                        <label>Address</label>
                        <input type="text" name="address" required class="form-control">
                    </div>
                </div>

                <!-- Total -->
                <h5>Total: ₹<?= number_format($total, 2) ?></h5>

                <!-- Hidden fields for passing data -->
                <input type="hidden" name="total_amount" value="<?= $total ?>">
                <input type="hidden" name="address" value="<?= $_POST['address'] ?>">

                <!-- Button -->
                <input type="submit" name="place_order" class="btn btn-success" id="submit-btn" value="Place Order">
            </form>

        </div>
    </section>


    <!-- Footer -->
    <?php include('footer.php'); ?>

<!-- Bootstrap JavaScript Bundle with Popper (deferred for performance) -->
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>