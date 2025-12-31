<?php
require_once '../connect.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



class Product extends DBconnect {
    public $a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k,$l;
    public function __construct() {
        parent::__construct();
    }

    public function save() {
        // Enable error reporting for development (remove in production)
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if ($this->db_handle) {
            $product_name = mysqli_real_escape_string($this->db_handle, $_POST["t1"]);
            $product_category = mysqli_real_escape_string($this->db_handle, $_POST["t2"]);
            $product_weight = mysqli_real_escape_string($this->db_handle, $_POST["t3"]);
            $product_quantity = intval($_POST["t4"]);
            $product_description = mysqli_real_escape_string($this->db_handle, $_POST["t5"]);
            $product_price = floatval($_POST["t6"]);
            $product_special_offer = floatval($_POST["t7"]);
            $seller_id = intval($_SESSION['seller_id']);

            // Check if product already exists
            $check_sql = "SELECT product_id FROM products WHERE product_name = '$product_name' AND seller_id = '$seller_id'";
            $check_result = mysqli_query($this->db_handle, $check_sql);

            if (mysqli_num_rows($check_result) > 0) {
                echo "<script>alert('Product Already Exists. Please use a different name.');</script>";
                return;
            }

            // Image upload
            $target_dir = "Assets/imgs/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $images = array_fill(1, 5, '');

            for ($i = 1; $i <= 5; $i++) {
                $input = "img$i";
                if (!empty($_FILES[$input]['name'])) {
                    $img_name = basename($_FILES[$input]['name']);
                    $tmp_path = $_FILES[$input]['tmp_name'];
                    $destination = $target_dir . $img_name;

                    if (move_uploaded_file($tmp_path, $destination)) {
                        $images[$i] = $img_name;
                    } else {
                        echo "<script>alert('Image $i upload failed.');</script>";
                    }
                }
            }

            // Insert into database
            $insert_sql = "
                INSERT INTO products 
                (
                    product_name, product_category, product_weight, product_quantity, product_description, 
                    product_image, product_image2, product_image3, product_image4, product_image5, 
                    product_price, product_special_offer, seller_id
                ) 
                VALUES 
                (
                    '$product_name', '$product_category', '$product_weight', '$product_quantity', '$product_description', 
                    '{$images[1]}', '{$images[2]}', '{$images[3]}', '{$images[4]}', '{$images[5]}', 
                    '$product_price', '$product_special_offer', '$seller_id'
                )
            ";

            if (mysqli_query($this->db_handle, $insert_sql)) {
                echo "<script>alert('Product Saved Successfully!');</script>";
                echo "<script>window.location.href='product.php';</script>";
            } else {
                $error_msg = mysqli_error($this->db_handle);
                echo "<script>alert('Error saving product: $error_msg');</script>";
            }

        } else {
            echo "<script>alert('Database connection failed');</script>";
        }
    }


    public function edit() {
        // Optional: Enable error reporting for development (comment out in production)
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if ($this->db_handle) {
            $seller_id = intval($_SESSION['seller_id']);

            $product_name = mysqli_real_escape_string($this->db_handle, $_POST['t1']);
            $product_category = mysqli_real_escape_string($this->db_handle, $_POST['t2']);
            $product_weight = mysqli_real_escape_string($this->db_handle, $_POST['t3']);
            $product_quantity = intval($_POST['t4']);
            $product_description = mysqli_real_escape_string($this->db_handle, $_POST['t5']);
            $product_price = floatval($_POST['t6']);
            $product_special_offer = floatval($_POST['t7']);

            // IMAGE HANDLING
            $target_dir = "Assets/imgs/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $images = [];
            for ($i = 1; $i <= 5; $i++) {
                $input = "img$i";
                $images[$i] = $_POST["old_img$i"] ?? '';

                if (!empty($_FILES[$input]['name'])) {
                    $img_name = basename($_FILES[$input]['name']);
                    $tmp_path = $_FILES[$input]['tmp_name'];
                    $destination = $target_dir . $img_name;

                    if (move_uploaded_file($tmp_path, $destination)) {
                        $images[$i] = $img_name;
                    } else {
                        echo "<script>alert('Image $i upload failed.');</script>";
                    }
                }
            }

            // Update query
            $sql = "
                UPDATE products 
                SET 
                    product_category = '$product_category', 
                    product_weight = '$product_weight', 
                    product_quantity = '$product_quantity', 
                    product_description = '$product_description', 
                    product_price = '$product_price', 
                    product_special_offer = '$product_special_offer',
                    product_image = '{$images[1]}', 
                    product_image2 = '{$images[2]}', 
                    product_image3 = '{$images[3]}', 
                    product_image4 = '{$images[4]}', 
                    product_image5 = '{$images[5]}' 
                WHERE 
                    product_name = '$product_name' 
                    AND seller_id = '$seller_id'
            ";

            if (mysqli_query($this->db_handle, $sql)) {
                echo "<script>alert('Product Updated'); window.location.href='product.php';</script>";
            } else {
                $err = mysqli_error($this->db_handle);
                echo "<script>alert('Update Failed: $err');</script>";
            }
        } else {
            echo "<script>alert('Database connection failed');</script>";
        }
    }


    public function search()
    {
        if ($this->db_handle) {
            // Query to fetch the product details based on the product name entered
            $result = mysqli_query($this->db_handle, "SELECT * FROM products WHERE product_name='$_POST[t1]'");
            
            while ($db_field = mysqli_fetch_assoc($result)) {
                // Assign fetched data to class properties
                $this->a = $db_field['product_name'];
                $this->b = $db_field['product_category'];
                $this->c = $db_field['product_weight'];
                $this->d = $db_field['product_quantity'];
                $this->e = $db_field['product_description'];
                $this->f = $db_field['product_image'];
                $this->g = $db_field['product_image2'];
                $this->h = $db_field['product_image3'];
                $this->i = $db_field['product_image4'];
                $this->j = $db_field['product_image5'];
                $this->k = $db_field['product_price'];
                $this->l = $db_field['product_special_offer'];
            }
        }
    }


    
}

$ob = new Product();
if (isset($_REQUEST["b1"])) {
    $ob->save();
}

if (isset($_REQUEST["b2"])) {
    $ob->edit();
}

if (isset($_REQUEST["b3"])) {
    $ob->search();
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
    
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <style>
        .btn-action {
            display: inline-block; /* Align buttons in a row */
            width: auto; /* Auto width for each button */
            margin-right: 20px; /* Space between buttons */
            height: 40px; /* Ensuring buttons have the same height */
        }

        .btn-detail {
            background-color: #000;
            color: #fff;
        }


        @media (max-width: 768px) {
            form .row > .col {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .row.mb-3 img {
                display: block;
                margin-top: 10px;
                width: 100%;
                height: auto;
            }
            .btn-action {
                width: 100%;
                margin-bottom: 10px;
            }
            .btn-detail {
                width: 100%;
            }
        }
    
    </style>
</head>
<body>
    <!-- Navigation - Using Bootstrap navbar component -->
    <?php include('seller_navbar.php'); ?>

    <!-- Adding Product in DataBase form-->
    <section class="Container">
            <div class="container mt-5 pt-5">
            <h2 class="text-center mb-4">Add New Product</h2>
            <form action='product.php' method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="t1" value="<?php echo htmlspecialchars($ob->a); ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Product Category</label>
                        <input type="text" class="form-control" name="t2" value="<?php echo htmlspecialchars($ob->b); ?>">
                    </div>
                    <div class="col">
                        <label class="form-label">Product Weight</label>
                        <input type="text" class="form-control" name="t3" value="<?php echo htmlspecialchars($ob->c); ?>">
                    </div>
                    <div class="col">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="t4" value="<?php echo htmlspecialchars($ob->d); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="t5" rows="3"><?php echo htmlspecialchars($ob->e); ?></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Main Image</label>
                        <input type="file" class="form-control" name="img1">
                            <?php if ($ob->f): ?>
                                <img src="Assets/imgs/<?php echo $ob->f; ?>" alt="Product Image" width="100" height="100">
                            <?php endif; ?>   
                    </div>
                    <div class="col">
                        <label class="form-label">Image 2</label>
                        <input type="file" class="form-control" name="img2">
                            <?php if ($ob->g): ?>
                                <img src="Assets/imgs/<?php echo $ob->g; ?>" alt="Product Image 2" width="100" height="100">
                            <?php endif; ?>
                    </div>
                    <div class="col">
                        <label class="form-label">Image 3</label>
                        <input type="file" class="form-control" name="img3">
                            <?php if ($ob->h): ?>
                                <img src="Assets/imgs/<?php echo $ob->h; ?>" alt="Product Image 3" width="100" height="100">
                            <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Image 4</label>
                        <input type="file" class="form-control" name="img4">
                            <?php if ($ob->h): ?>
                                <img src="Assets/imgs/<?php echo $ob->i; ?>" alt="Product Image 3" width="100" height="100">
                            <?php endif; ?>
                    </div>
                    <div class="col">
                        <label class="form-label">Image 5</label>
                        <input type="file" class="form-control" name="img5">
                            <?php if ($ob->h): ?>
                                <img src="Assets/imgs/<?php echo $ob->j; ?>" alt="Product Image 3" width="100" height="100">
                            <?php endif; ?>   
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" name="t6"  value="<?php echo htmlspecialchars($ob->k); ?>">
                    </div>
                    <div class="col">
                        <label class="form-label">Special Offer (%)</label>
                        <input type="number" class="form-control" name="t7" value="<?php echo htmlspecialchars($ob->l); ?>">
                    </div>
                </div>

                <button type="submit" name="b1" class="btn btn-primary btn-action">Add Product</button>
                <button type="submit" name="b2" class="btn btn-primary btn-action">Edit Product</button>
                <button type="submit" name="b3" class="btn btn-primary btn-action">Search</button>
            </form>
            <form action='productdetails.php' method="POST" enctype="multipart/form-data">
                <div class="d-flex justify-content-end">
                    <button type="submit" name="b4" class="btn btn-detail">Details</button>
                </div>
            </form>


        </div>
    </section>
     

    <!-- Footer -->
    <?php include('seller_footer.php'); ?>

    <!-- Bootstrap JavaScript Bundle with Popper (deferred for performance) -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>