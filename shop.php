<?php
session_start();
include('connect.php');

$db = new DBConnect();
$conn = $db->db_handle;

// Fetch categorized products
$spices_query = "SELECT * FROM products WHERE product_category = 'Garam Masala' ORDER BY product_id DESC";
$seeds_query = "SELECT * FROM products WHERE product_category = 'Seeds' ORDER BY product_id DESC";

$spices_result = mysqli_query($conn, $spices_query);
if (!$spices_result) {
    die("Spices Query Error: " . mysqli_error($conn));
}

$seeds_result = mysqli_query($conn, $seeds_query);
if (!$seeds_result) {
    die("Seeds Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Heritage Aromas - Premium quality products with best prices">
    <title>Shop</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="Assests/Css/style.css">

    <style>
        html {
            scroll-behavior: smooth;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding-bottom: 1rem;
        }

        .card-body .btn {
            margin-top: 0.5rem;
            padding: 10px 16px;
            font-weight: 600;
            border-radius: 6px;
        }

        .product.card {
            transition: transform 0.2s;
        }

        .product.card:hover {
            transform: scale(1.03);
        }

        .card-img-top {
            height: 200px;
            object-fit: contain;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <?php include('navbar.php'); ?>

    <!-- Spices Section -->
    <section id="spices-section" class="my-5 pb-5">
        <div class="container text-center mt-5 py-5">
            <h3>Spices</h3>
            <hr class="w-25 mx-auto">
        </div>
        <div class="container">
            <div class="row g-4">
                <?php while ($row = mysqli_fetch_assoc($spices_result)) : ?>
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                        <div class="product card h-100 border-0" data-id="<?= $row['product_id'] ?>">
                            <img src="Assests/imgs/<?= $row['product_image'] ?>" class="card-img-top img-fluid p-3 product-click" alt="<?= $row['product_name'] ?>" data-id="<?= $row['product_id'] ?>">
                            <div class="card-body text-center">
                                <div class="star text-warning mb-2">★★★★★</div>
                                <h5 class="card-title product-click fw-bold" data-id="<?= $row['product_id'] ?>"><?= $row['product_name'] ?></h5>
                                <h4 class="text-primary fw-bold">₹<?= number_format($row['product_price'], 2) ?></h4>
                                <p class="text-muted"><?= $row['product_weight'] ?>kg</p>
                                <button class="btn btn-dark w-100 product-click" data-id="<?= $row['product_id'] ?>">Buy Now</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Seeds Section -->
    <section id="seeds-section" class="my-5 pb-5">
        <div class="container text-center mt-5 py-5">
            <h3>Seeds</h3>
            <hr class="w-25 mx-auto">
        </div>
        <div class="container">
            <div class="row g-4">
                <?php while ($row = mysqli_fetch_assoc($seeds_result)) : ?>
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                        <div class="product card h-100 border-0" data-id="<?= $row['product_id'] ?>">
                            <img src="Assests/imgs/<?= $row['product_image'] ?>" class="card-img-top img-fluid p-3 product-click" alt="<?= $row['product_name'] ?>" data-id="<?= $row['product_id'] ?>">
                            <div class="card-body text-center">
                                <div class="star text-warning mb-2">★★★★★</div>
                                <h5 class="card-title product-click fw-bold" data-id="<?= $row['product_id'] ?>"><?= $row['product_name'] ?></h5>
                                <h4 class="text-primary fw-bold">₹<?= number_format($row['product_price'], 2) ?></h4>
                                <p class="text-muted"><?= $row['product_weight'] ?>kg</p>
                                <button class="btn btn-dark w-100 product-click" data-id="<?= $row['product_id'] ?>">Buy Now</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include('footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Product click handler -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".product-click").forEach(el => {
                el.addEventListener("click", function () {
                    const id = this.getAttribute("data-id");
                    if (id) {
                        window.location.href = `single_product.php?product_id=${id}`;
                    }
                });
            });
        });
    </script>

</body>
</html>
