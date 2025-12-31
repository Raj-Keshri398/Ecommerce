<?php
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


?>


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
<body>
    

    <!-- Navigation -->
    <?php include('navbar.php'); ?>


    <!-- Main Content -->
    <main>
        <section id="home">
            <div class="container">
                <div class="row align-items-center">
                <!-- Image: Right on desktop, top on mobile -->
                <div class="col-lg-6 order-1 order-lg-2 text-center mb-4 mb-lg-0">
                    <img src="Assests/imgs/brandimg.png" alt="Spice Banner" class="img-fluid hero-img" />
                </div>

                <!-- Text: Left on desktop, bottom on mobile -->
                <div class="col-lg-6 order-2 order-lg-1 text-start">
                    <h5>NEW ARRIVALS</h5>
                    <h1><span>Best Prices</span> This Season</h1>
                    <p>Best Taste and healthy product with best price</p>
                    <a href="shop.php" class="btn btn-primary">Shop Now</a>
                </div>
                </div>
            </div>
        </section>

        <!-- Brand Section -->
        <section id="brand">
            <div class="marquee-alternative mb-4">
            <span>🎉 Special Offer: Get 20% Off on First Purchase! Use Code: NEW20 🎉</span>
            </div>
        </section>
      
        <!-- New Products Section -->
        <section id="new" class="w-100">
            <!-- Bootstrap grid system -->
            <div class="row p-0 m-0">
                <!-- Product One -->
                <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
                    <img class="img-fluid" src="Assests/imgs/cardmom.jpg" alt="Nutmeg product" />
                    <div class="details">
                        <h2>Taste is best...</h2>
                        <!-- Bootstrap text utility class -->
                        <a href="shop.php" class="btn btn-outline-light text-uppercase">Shop Now</a>
                    </div>
                </div>

                <!-- Product Two -->
                <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
                    <img class="img-fluid" src="Assests/imgs/Cassia.jpg" alt="Cassia product" />
                    <div class="details">
                        <h2>Taste is healthy...</h2>
                        <a href="shop.php" class="btn btn-outline-light text-uppercase">Shop Now</a>
                    </div>
                </div>

                <!-- Product Three -->
                <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
                    <img class="img-fluid" src="Assests/imgs/Mace.jpg" alt="Mace product" />
                    <div class="details">
                        <h2>Taste is awsome...</h2>
                        <a href="shop.php" class="btn btn-outline-light text-uppercase">Shop Now</a>
                    </div>
                </div>
            </div>
        </section>

        <!--Featured-->
        <!-- Featured Section -->
        <section id="featured" class="my-5 pb-5">
            <div class="container text-center mt-5 py-5">
                <h3>Our Featured</h3>
                <hr class="w-25 mx-auto">
                <p>Here you can check out our products</p>
            </div>
            <div class="container">
                <div class="row g-4">

                    <?php include('server/get_featured_product.php'); ?>

                    <?php while($row = $garam_masala_products->fetch_assoc()){ ?>
                    <!-- Product 1 -->
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                        <div class="product card h-100 border-0" data-id="<?php echo $row['product_id']; ?>">
                        <img src="Assets/imgs/<?php echo $row['product_image']; ?>" class="card-img-top img-fluid p-3 product-click" alt="<?php echo $row['product_name']; ?>" data-id="<?php echo $row['product_id']; ?>">
                            <div class="card-body text-center">
                                <div class="star text-warning mb-2">★★★★★</div>
                                <h5 class="card-title p-name product-click" ><?php echo $row['product_name']; ?></h5>
                                <h4 class="text-primary p-price"><?php echo $row['product_price']; ?></h4>
                                <p class="text-muted p-weight"><?php echo $row['product_category']; ?></p>
                                <button class="btn btn-dark w-75 mx-auto product-click" data-id="<?php echo $row['product_id']; ?>">Buy Now</button>
                            </div>
                        </div>
                    </div>

                    <?php } ?>
                </div>

            </div>
        </section>


        <!-- Brand Section -->
        <section id="brand">
            <div class="marquee-alternative mb-4">
            <span>🎉 Special Offer: Get 20% Off on First Purchase! Use Code: NEW20 🎉</span>
            </div>
        </section>

        <!--Seeds-->
        <section id="featured" class="my-5">
            <div class="container text-center mt-5 py-5">
                <h3>Seeds</h3>
                <hr class="w-25 mx-auto">
                <p>Here you can check the seeds</p>
            </div>
            
            <div class="container">
                <div class="row g-4">
                <?php include('server/get_featured_product.php'); ?>

                <?php while($row = $seeds_products->fetch_assoc()){ ?>

                    <!-- Product 1 -->
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-4" data-id="<?php echo $row['product_id']; ?>">
                        <div class="product card h-100 border-0">
                            <img src="Assests/imgs/<?php echo $row['product_image']; ?>" class="card-img-top img-fluid p-3 product-click" alt="<?php echo $row['product_name']; ?>" data-id="<?php echo $row['product_id']; ?>">
                            <div class="card-body text-center">
                                <div class="star text-warning mb-2">★★★★★</div>
                                <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                                <h4 class="text-primary"><?php echo $row['product_price']; ?></h4>
                                <p class="text-muted"><?php echo $row['product_category']; ?></p>
                                <button class="btn btn-dark w-75 mx-auto product-click" data-id="<?php echo $row['product_id']; ?>">Buy Now</button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        </section>

    </main>

   <!-- Footer -->
    <?php include('footer.php'); ?>

    <!-- Bootstrap JavaScript Bundle with Popper (deferred for performance) -->
    
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Get all elements with the class 'product-click'
            const clickableElements = document.querySelectorAll(".product-click");

            clickableElements.forEach(element => {
                element.addEventListener("click", function () {
                    const productId = this.getAttribute("data-id");
                    if (productId) {
                        window.location.href = `single_product.php?product_id=${productId}`;
                    }
                });
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