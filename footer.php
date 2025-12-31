<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Heritage Aromas - Premium quality products with best prices">
    <title>Heritage Aromas - Premium Products</title>
    
    <!-- Preload critical resources -->
    <!-- Bootstrap CSS (preloaded for performance) -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"></noscript>
    
    <!-- Font Awesome -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"></noscript>
    
    <!-- Custom CSS -->
    <link rel="preload" href="Assests/Css/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap"></noscript>
</head>
<body>

    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container text-center text-md-start">
            <div class="row text-center text-md-start">
                <!-- About -->
                <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Heritage Aromas</h5>
                    <p>Premium quality products with the best taste and price. We bring nature's best to your table.</p>
                </div>

                <!-- Products -->
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Products</h5>
                    <p><a href="shop.php#spices-section" class="text-decoration-none">Spices</a></p>
                    <p><a href="shop.php#seeds-section" class="text-decoration-none">Seeds</a></p>
                </div>

                <!-- Useful Links -->
                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Useful Links</h5>
                    <p><a href="javascript:void(0);" onclick="goToAccount();" class="text-decoration-none">Your Account</a></p>
                    <p><a href="javascript:void(0);" onclick="goToSellerAccount();" class="text-decoration-none">Become an Affiliate</a></p>
                </div>


                <!-- Contact -->
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Contact</h5>
                    <p><i class="fas fa-envelope me-3"></i>
                        <a href="mailto:info@heritagearomas.com" class="text-decoration-none">info@heritagearomas.com</a>
                    </p>

                </div>
            </div>

            <!-- Social media -->
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

<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function goToAccount() {
        <?php if (isset($_SESSION['user_id'])): ?>
            window.location.href = "useraccount.php";
        <?php else: ?>
            window.location.href = "myprofile.php";
        <?php endif; ?>
    }

    function goToSellerAccount() {
        <?php if (isset($_SESSION['seller_id'])): ?>
            window.location.href = "seller/SellerDashboard.php";
        <?php else: ?>
            window.location.href = "seller/seller_login_register.php";
        <?php endif; ?>
    }
</script>

</body>
</html>

