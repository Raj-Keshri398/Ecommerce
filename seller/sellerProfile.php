<?php
session_start();

// If user is already logged in, redirect to useraccount.php
if (isset($_SESSION['user_id'])) {
    header("Location: SellerDashboard.php");
    exit();
}

// Cart count placeholder
$count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Heritage Aromas - Premium quality products with best prices">
    <title>Heritage Aromas - Premium Products</title>

    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"></noscript>

    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"></noscript>

    <link rel="preload" href="../Assests/Css/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="../Assests/Css/style.css"></noscript>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap"></noscript>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg py-3 fixed-top">
        <div class="container">
            <h2 class="navbar-brand" aria-label="Heritage Aromas Home">HERITAGE AROMAS</h2>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../shop.php">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="../contact.php">Contact Us</a></li>
                </ul>
                <div class="d-flex">
                    <a href="cart.php" class="nav-link position-relative mx-2">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if ($count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $count ?></span>
                        <?php endif; ?>
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="Useraccount.php" class="nav-link mx-2" aria-label="User Account">
                            <i class="fas fa-user" aria-hidden="true"></i> Account
                        </a>
                    <?php else: ?>
                        <a href="myprofile.php" class="nav-link mx-2" aria-label="Login">
                            <i class="fas fa-user" aria-hidden="true"></i> Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Login Info -->
    <div class="text-center my-5">
        <h1>Account</h1>
        <p>Log in to get more offer</p>
        <a href="seller_login_register.php" class="btn btn-primary px-4 py-2">Login</a>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4 mt-5">
        <div class="container text-center text-md-start">
            <div class="row text-center text-md-start">
                <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Heritage Aromas</h5>
                    <p>Premium quality products with the best taste and price. We bring nature's best to your table.</p>
                </div>
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Products</h5>
                    <p><a href="#" class="text-decoration-none">Spices</a></p>
                    <p><a href="#" class="text-decoration-none">Seeds</a></p>
                    <p><a href="#" class="text-decoration-none">Oils</a></p>
                    <p><a href="#" class="text-decoration-none">Blends</a></p>
                </div>
                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Useful Links</h5>
                    <p><a href="#" class="text-decoration-none">Your Account</a></p>
                    <p><a href="#" class="text-decoration-none">Become an Affiliate</a></p>
                    <p><a href="#" class="text-decoration-none">Shipping Info</a></p>
                    <p><a href="#" class="text-decoration-none">Help</a></p>
                </div>
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Contact</h5>
                    <p><i class="fas fa-home me-3"></i> 123 Aroma Lane, Spice City</p>
                    <p><i class="fas fa-envelope me-3"></i> info@heritagearomas.com</p>
                    <p><i class="fas fa-phone me-3"></i> +123 456 7890</p>
                    <p><i class="fas fa-print me-3"></i> +123 456 7891</p>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <a href="#" class="me-4"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="me-4"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="me-4"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="me-4"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
            <p class="mb-0">&copy; 2025 Heritage Aromas. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
