
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Heritage Aromas - Premium quality products with best prices">
  <title>Heritage Aromas - Premium Products</title>

  <!-- Bootstrap -->
  <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"></noscript>

  <!-- Font Awesome -->
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"></noscript>

  <!-- Custom CSS -->
  <link rel="preload" href="Assests/Css/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap"></noscript>

  <style>
    @media (max-width: 992px) {
      .navbar .right-icons {
        order: -1;
        margin-right: auto;
      }

      .navbar .left-icons {
        margin-left: auto;
      }
    }
  </style>
</head>
<body>

  <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <div class="container d-flex justify-content-between align-items-center px-2">
    <!-- Brand -->
    <div class="d-flex align-items-center right-icons">
      <a class="navbar-brand mb-0" href="index.php">
        <h2 class="mb-0" style="font-size: 1.4rem;">HERITAGE AROMA</h2>
      </a>
    </div>

    <!-- Toggle Button -->
    <div class="d-flex align-items-center">
      <button id="manualToggle" class="navbar-toggler me-2" type="button" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

    </div>

    <!-- Menu -->
    <div class="collapse navbar-collapse px-2" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Blog</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
      </ul>
    </div>

    <!-- Right Icons -->
    <div class="d-flex align-items-center right-icons">
      <!-- Cart -->
      <a href="cart.php" class="nav-link position-relative mx-1">
        <i class="fas fa-shopping-cart"></i>
        <?php $count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          <?= $count ?>
        </span>
      </a>

      <!-- Login / Account -->
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="Useraccount.php" class="nav-link mx-1"><i class="fas fa-user"></i><span class="d-none d-sm-inline small"> Account</span></a>
      <?php else: ?>
        <a href="myprofile.php" class="nav-link mx-1"><i class="fas fa-user"></i><span class="d-none d-sm-inline small"> Login</span></a>
      <?php endif; ?>

      <!-- Seller -->
      <?php if (isset($_SESSION['seller_id'])): ?>
        <a href="seller/SellerDashboard.php" class="nav-link mx-1"><i class="fas fa-store"></i><span class="d-none d-sm-inline small"> Seller</span></a>
      <?php else: ?>
        <a href="seller/SellerProfile.php" class="nav-link mx-1"><i class="fas fa-store"></i><span class="d-none d-sm-inline small"> Seller</span></a>
      <?php endif; ?>
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
</html>
