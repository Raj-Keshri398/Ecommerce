<?php
include('connect.php');

$db = new DBConnect();

if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $query = "SELECT * FROM products WHERE product_id = ?";
    if ($stmt = mysqli_prepare($db->db_handle, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
        } else {
            header('Location: index.php');
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Error in SQL preparation: " . mysqli_error($db->db_handle));
    }
} else {
    header('Location: index.php');
    exit();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Product Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <style>
        /* --------------------- Main Styles -------------------- */

        .small-img-group {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .small-img-col {
            flex: 1 1 22%;
            max-width: 100px;
            cursor: pointer;
        }

        .small-img-col img {
            width: 100%;
            object-fit: cover;
        }

        .sections {
            margin-top: 0;
        }

        .sections .second {
            margin-left: 80px;
        }

        .sections h6 {
            color: rgb(24, 22, 22);
            font-size: 20px;
        }

        .sections input {
            width: 50px;
            height: 40px;
            padding-left: 10px;
            font-size: 16px;
            margin-right: 10px;
        }

        .sections input:focus {
            outline: none;
        }

        .sections .btn {
            background-color: rgb(230, 121, 20);
            padding: 8px;
            color: #fff;
            opacity: 1;
        }

        .sections .btn:hover {
            background-color: #222222;
        }

        #related-product .product {
            cursor: pointer;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        #related-product .product:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        #related-product .product img {
            width: 100%;
            height: 180px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        #related-product .card-body {
            display: flex;
            flex-direction: column;
            align-items: center; 
        }

        #related-product .btn {
            width: 75%;
            max-width: 200px;
            text-align: center;
        }


        /* --------------------- Responsive Styles -------------------- */

        @media (max-width: 768px) {
            .single-product .row {
                flex-direction: column;
            }

            .sections .second {
                margin-left: 0 !important;
                margin-top: 2rem !important;
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .small-img-group {
                justify-content: center;
                margin-top: 1rem;
            }

            .small-img-col {
                flex: 1 1 22%;
                max-width: 80px;
            }

            .sections input {
                width: 80px;
                height: 36px;
                font-size: 16px;
                margin-bottom: 1rem;
            }

            .sections .btn {
                font-size: 16px;
                padding: 8px 16px;
            }

            .single-product form {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 10px;
            }

            .sections h3, .sections h4, .sections h6 {
                font-size: 18px;
            }

            .sections span {
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            #related-product .card-body {
                padding: 1rem 0.5rem;
                text-align: center;
            }

            #related-product h5,
            #related-product h4,
            #related-product p {
                font-size: 14px;
            }

            #related-product .btn {
                font-size: 14px;
                padding: 6px 12px;
                width: 100%;
            }

            #related-product .product {
                width: 100%;
            }

            .sections input {
                width: 60px;
                height: 36px;
                font-size: 14px;
            }

            .sections .btn {
                font-size: 14px;
                padding: 6px 10px;
            }
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<section class="container single-product my-5 pt-5 sections">
    <div class="row mt-5">
        <div class="col-lg-5 col-md-6 col-sm-12">
            <img class="img-fluid w-100 pb-1" src="Assests/imgs/<?php echo htmlspecialchars($product['product_image']); ?>" id="mainImg"/>
            <div class="small-img-group">
                <?php 
                $images = ['product_image2', 'product_image3', 'product_image4', 'product_image5'];
                foreach ($images as $image) {
                    if (!empty($product[$image])) {
                        echo '<div class="small-img-col">
                                <img src="Assests/imgs/'.$product[$image].'" width="100%" class="small-img" />
                              </div>';
                    }
                }
                ?>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12 second">
            <h6><?php echo htmlspecialchars($product['product_category']); ?></h6>
            <h3 class="py-4"><?php echo htmlspecialchars($product['product_name']); ?></h3>
            <h2>RS <?php echo htmlspecialchars($product['product_price']); ?></h2>

            <form method="POST" action="cart.php">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>" />
                <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['product_image']); ?>" />
                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" />
                <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['product_price']); ?>" />
                <input type="number" name="product_quantity" value="1" min="1" />
                <button class="btn" name="add_to_cart" type="submit">Add to Cart</button>
            </form>

            <h4 class="mt-5 mb-5">Product Details</h4>
            <span><?php echo htmlspecialchars($product['product_description']); ?></span>
        </div>
    </div>
</section>

<section id="related-product" class="my-5 pb-5">
    <div class="container-fluid text-center mt-5 py-5">
        <h3>Related Products</h3>
        <hr class="w-25 mx-auto">
    </div>

    <div class="container-fluid px-3">
        <div class="row g-4">
            <?php
            $related_stmt = mysqli_prepare($db->db_handle, "SELECT * FROM products WHERE product_category = ? AND product_id != ? LIMIT 4");
            mysqli_stmt_bind_param($related_stmt, "si", $product['product_category'], $product['product_id']);
            mysqli_stmt_execute($related_stmt);
            $related_result = mysqli_stmt_get_result($related_stmt);

            while ($related = mysqli_fetch_assoc($related_result)) {
                echo '
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="product card h-100 border-0">
                        <img src="Assests/imgs/'.$related['product_image'].'" class="card-img-top img-fluid p-3" alt="'.$related['product_name'].'">
                        <div class="card-body">
                            <div class="star text-warning mb-2">★★★★★</div>
                            <h5 class="card-title">'.$related['product_name'].'</h5>
                            <h4 class="text-primary">₹'.$related['product_price'].'</h4>
                            <p class="text-muted">1kg</p>
                            <a href="single_product.php?product_id='.$related['product_id'].'" class="btn btn-dark">Buy Now</a>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
</section>

<?php include('footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mainImg = document.getElementById('mainImg');
        const smallImgs = document.getElementsByClassName('small-img');

        Array.from(smallImgs).forEach(smallImg => {
            smallImg.addEventListener('click', function() {
                mainImg.src = this.src;
            });
        });
    });
</script>

</body>
</html>
