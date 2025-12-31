<?php
session_start();

require_once 'connect.php'; // Your DB connection class/file

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit();
}

// Calculate cart counts and totals for navbar (optional)
$count = 0;
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $count += $item['product_quantity'];
    $total += $item['product_price'] * $item['product_quantity'];
}
$_SESSION['total'] = $total;


// Address class for saving address linked to logged-in user
class Address extends DBconnect {
    public function __construct() {
        parent::__construct();
    }

    public function save() {
        $user_id = $_SESSION['user_id'];

        // Sanitize POST inputs
        $name = mysqli_real_escape_string($this->db_handle, $_POST['name']);
        $email = mysqli_real_escape_string($this->db_handle, $_POST['email']);
        $phone = mysqli_real_escape_string($this->db_handle, $_POST['phone']);
        $city = mysqli_real_escape_string($this->db_handle, $_POST['city']);
        $pincode = mysqli_real_escape_string($this->db_handle, $_POST['pincode']);
        $address = mysqli_real_escape_string($this->db_handle, $_POST['address']);

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email address'); window.history.back();</script>";
            exit;
        }

        // Validate phone (only digits, exactly 10 digits)
        if (!preg_match('/^[0-9]{10}$/', $phone)) {
            echo "<script>alert('Phone number must be exactly 10 digits and contain only numbers'); window.history.back();</script>";
            exit;
        }

        // Validate pincode (only digits, exactly 6 digits)
        if (!preg_match('/^[0-9]{6}$/', $pincode)) {
            echo "<script>alert('Pincode must be exactly 6 digits and contain only numbers'); window.history.back();</script>";
            exit;
        }

        // Insert address
        $query = "INSERT INTO addresses (user_id, name, email, phone, city, pincode, address) 
                  VALUES ('$user_id', '$name', '$email', '$phone', '$city', '$pincode', '$address')";

        if (mysqli_query($this->db_handle, $query)) {
            echo "<script>alert('Address Saved Successfully'); window.location.href='addressHistory.php';</script>";
        } else {
            echo "<script>alert('Error saving address');</script>";
        }
    }
}

// If form submitted
if (isset($_POST['save_address'])) {
    $address = new Address();
    $address->save();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Heritage Aromas - Premium quality products with best prices">
    <title>Add New Address</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="Assests/Css/style.css">
    <link rel="stylesheet" href="Assests/Css/checkout.css">
</head>
<body>

<!-- Navbar -->
<?php include('navbar.php'); ?>

<!-- Address Form -->
<div class="container my-5">
    <div class="card p-4 shadow rounded">
        <h4 class="mb-4">Add New Address</h4>
        <form action="" method="post">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                        required pattern="\d{10}" maxlength="10" minlength="10"
                        title="Phone number must be exactly 10 digits and only numbers">

                </div>
                <div class="col-md-6">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="pincode" class="form-label">Pincode</label>
                    <input type="text" class="form-control" id="pincode" name="pincode"
                        required pattern="\d{6}" maxlength="6" minlength="6"
                        title="Pincode must be exactly 6 digits and only numbers">

                </div>
                <div class="col-md-6">
                    <label for="address" class="form-label">Full Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" name="save_address" class="btn btn-primary">Save Address</button>
            </div>
        </form>
    </div>
</div>

<!-- Footer -->
<?php include('footer.php'); ?>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
