<?php
session_start();
require_once '../connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Poppins', sans-serif;
        }
        .table-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 12px rgba(0,0,0,0.1);
            padding: 20px;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .table-responsive thead {
                display: none;
            }
            .table-responsive tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                padding: 10px;
                border-radius: 8px;
            }
            .table-responsive tbody td {
                display: block;
                text-align: right;
                font-size: 14px;
                padding-left: 50%;
                position: relative;
                border: none;
                border-bottom: 1px solid #eee;
            }
            .table-responsive tbody td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: 600;
                text-align: left;
                color: #555;
            }
            .table-responsive tbody td:last-child {
                border-bottom: none;
            }
        }
    </style>
</head>
<body>
<?php include('seller_navbar.php'); ?>
<a href="product.php" class="btn btn-secondary m-3"><i class='bx bx-arrow-back'></i> Back</a>
<div class="container">
    <form method="post" action="productdetails.php" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="t1" class="form-control" placeholder="Search by Product Name or ID">
        </div>
        <div class="col-auto">
            <button type="submit" name="b1" class="btn btn-primary">Submit</button>
            <button type="submit" name="b2" class="btn btn-danger">Delete</button>
            <button type="submit" name="b5" class="btn btn-success">All Products</button>
        </div>
    </form>
</div>
<?php
class ProductDetails extends DBconnect {
    public function __construct() {
        parent::__construct();
    }

    public function showAllProducts() {
        if ($this->db_handle && isset($_SESSION['seller_id'])) {
            $seller_id = $_SESSION['seller_id'];
            $result = mysqli_query($this->db_handle, 
                "SELECT * FROM products WHERE seller_id = '$seller_id' ORDER BY product_id DESC");

            if ($result) {
                $this->renderTable($result);
            } else {
                echo "<p class='text-danger'>Failed to fetch products: " . mysqli_error($this->db_handle) . "</p>";
            }
        }
    }

    public function searchProduct() {
        if ($this->db_handle && isset($_SESSION['seller_id'])) {
            $q = mysqli_real_escape_string($this->db_handle, $_POST['t1']);
            $seller_id = $_SESSION['seller_id'];

            $result = mysqli_query($this->db_handle, 
                "SELECT * FROM products 
                 WHERE (product_id = '$q' OR product_name LIKE '%$q%') 
                 AND seller_id = '$seller_id'");

            if (!$result) {
                echo "<script>alert('Search query failed: " . mysqli_error($this->db_handle) . "');</script>";
                return;
            }

            $this->renderTable($result);
        }
    }

    public function deleteProduct() {
        if ($this->db_handle && isset($_SESSION['seller_id'])) {
            $d = mysqli_real_escape_string($this->db_handle, $_POST['t1']);
            $seller_id = $_SESSION['seller_id'];

            $delete = "DELETE FROM products 
                       WHERE (product_id = '$d' OR product_name LIKE '%$d%') 
                       AND seller_id = '$seller_id'";

            if (mysqli_query($this->db_handle, $delete)) {
                echo "<script>alert('Product Deleted Successfully'); window.location.href='productdetails.php';</script>";
            } else {
                echo "<script>alert('Deletion failed: " . mysqli_error($this->db_handle) . "');</script>";
            }
        }
    }

    private function renderTable($result) {
        if (mysqli_num_rows($result) == 0) {
            echo "<p class='text-center text-warning mt-3'>No products found.</p>";
            return;
        }

        echo "<div class='table-container table-responsive'>";
        echo "<table class='table table-bordered table-striped table-hover'>
                <thead class='table-dark text-center'>
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Weight</th>
                        <th>Quantity</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Special Offer</th>
                    </tr>
                </thead>
                <tbody>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td data-label='Product ID'>{$row['product_id']}</td>
                    <td data-label='Name'>{$row['product_name']}</td>
                    <td data-label='Category'>{$row['product_category']}</td>
                    <td data-label='Weight'>{$row['product_weight']}</td>
                    <td data-label='Quantity'>{$row['product_quantity']}</td>
                    <td data-label='Description'>{$row['product_description']}</td>
                    <td data-label='Price'>₹{$row['product_price']}</td>
                    <td data-label='Special Offer'>{$row['product_special_offer']}%</td>
                  </tr>";

            echo "<tr><td colspan='8'>";
            for ($i = 1; $i <= 5; $i++) {
                $imgField = "product_image" . ($i == 1 ? "" : $i);
                $img = $row[$imgField];

                if ($img) {
                    echo "<img src='Assets/imgs/{$img}' alt='Image {$i}' style='width:60px;height:60px;object-fit:cover;border-radius:5px;box-shadow:0 0 3px rgba(0,0,0,0.2); margin-right:5px;'>";
                } else {
                    echo "<span style='display:inline-block;width:60px;height:60px;border:1px dashed #ccc;border-radius:5px;margin-right:5px;'></span>";
                }
            }
            echo "</td></tr>";
        }

        echo "</tbody></table></div>";
    }
}

$ob = new ProductDetails();
if (isset($_POST['b5'])) $ob->showAllProducts();
if (isset($_POST['b1'])) $ob->searchProduct();
if (isset($_POST['b2'])) $ob->deleteProduct();
?>

<?php include('seller_footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
