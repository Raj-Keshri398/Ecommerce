<?php
session_start(); // Start session to access session variables

// Check if the order has been placed
if (!isset($_SESSION['order_placed'])) {
    header('Location: index.php'); // Redirect to homepage if no order is placed
    exit();
}

// Reset order_placed session variable to avoid redirecting again
unset($_SESSION['order_placed']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Heritage Aromas - Premium quality products with best prices">
    <title>Order Confirmation</title>
    
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
</head>
<body>

    <!-- Navigation -->
    <?php include('navbar.php'); ?>

    <!-- Order Confirmation -->
    <section class="order-confirmation-section py-5">
        <div class="container text-center">
            <h2 class="form-weight-bold">Thank You for Your Order!</h2>
            <hr class="mx-auto">
            <p>Your order has been successfully placed. We will process it and notify you once it’s on the way.</p>

            <div class="confirmation-details">
                <h4>Order Summary</h4>
                <p><strong>Total Amount:</strong> ₹<?= number_format($_SESSION['total'], 2); ?></p>
                <p><strong>Payment Method:</strong> <?= htmlspecialchars($_SESSION['payment_method']); ?></p>

                <?php if (isset($_SESSION['payment_details'])): ?>
                    <p><strong>Payment Details:</strong> <?= htmlspecialchars($_SESSION['payment_details']); ?></p>
                <?php endif; ?>
            </div>

            <p class="mt-4">Thank you for shopping with Heritage Aromas. Your order will be shipped soon!</p>
            <a href="index.php" class="btn btn-primary">Back to Home</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include('footer.php'); ?>

    <!-- Bootstrap JavaScript Bundle with Popper (deferred for performance) -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
