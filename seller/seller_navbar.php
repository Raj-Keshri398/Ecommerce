<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Font Awesome CDN (Make sure this is in your main layout <head> as well) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<head>
    <style>
        body{
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
<!-- Bootstrap Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 shadow-sm sticky-top">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="SellerDashboard.php">
            <i class="fas fa-store me-2"></i>Seller Panel
        </a>

        <!-- Toggle Button (Mobile) -->
        <button id="manualToggle" class="navbar-toggler me-2" type="button" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto text-center">
                <!-- Home -->
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </li>

                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link" href="SellerDashboard.php">
                        <i class="fas fa-chart-line me-1"></i>Dashboard
                    </a>
                </li>

                <!-- Product List -->
                <li class="nav-item">
                    <a class="nav-link" href="productdetails.php">
                        <i class="fas fa-list me-1"></i>Product List
                    </a>
                </li>

                <!-- Add Product -->
                <li class="nav-item">
                    <a class="nav-link" href="product.php">
                        <i class="fas fa-plus-circle me-1"></i>Add Product
                    </a>
                </li>

                <!-- Orders -->
                <li class="nav-item">
                    <a class="nav-link" href="seller_order.php">
                        <i class="fas fa-box me-1"></i>Orders
                    </a>
                </li>

                <!-- Feedback -->
                <li class="nav-item">
                    <a class="nav-link" href="customer_feedback.php">
                        <i class="fas fa-comments me-1"></i>Feedback
                    </a>
                </li>

                <!-- Seller Account or Become Seller -->
                <?php if (isset($_SESSION['seller_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="SellerDashboard.php">
                            <i class="fas fa-user me-1"></i>
                            <?= htmlspecialchars($_SESSION['seller_name']) ?>'s Account
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="seller_logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="SellerProfile.php">
                            <i class="fas fa-user-plus me-1"></i>Become a Seller
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

 <!-- Bootstrap Bundle (includes Popper) -->
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Toggle Fix -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const toggler = document.getElementById('manualToggle');
    const navbarCollapse = document.getElementById('navbarNav');

    const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
      toggle: false
    });

    toggler.addEventListener('click', function () {
      if (navbarCollapse.classList.contains('show')) {
        bsCollapse.hide();
      } else {
        bsCollapse.show();
      }
    });
  });

  /* Close the menu if user taps outside */
        document.addEventListener('click', function (e) {
            const nav = e.target.closest('.navbar');
            const collapse = document.querySelector('.navbar-collapse');
            if (!nav && collapse && collapse.classList.contains('show')) {
            bootstrap.Collapse.getInstance(collapse).hide();
            }
        });
</script>

</body>
