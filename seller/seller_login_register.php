<?php
ob_start(); // Start output buffering to avoid "headers already sent" errors
session_start();
require_once '../connect.php';

class Sellers extends DBConnect {
    public function __construct() {
        parent::__construct();
    }

    public function register() {
        if ($this->db_handle) {
            $name = mysqli_real_escape_string($this->db_handle, $_POST["t1"]);
            $phone = mysqli_real_escape_string($this->db_handle, $_POST["t2"]);
            $email = mysqli_real_escape_string($this->db_handle, $_POST["t3"]);
            $password = $_POST["t4"];
            $confirmPassword = $_POST["t5"];

            // Check if email already exists
            $check = "SELECT * FROM sellers WHERE email = '$email'";
            $result = mysqli_query($this->db_handle, $check);
            if (mysqli_num_rows($result) > 0) {
                echo "<script>alert('Email already registered'); window.onload = function() { toggleForm('register'); };</script>";
                return;
            }

            // Validate password
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/', $password)) {
                echo "<script>alert('Password must include uppercase, lowercase, number, and special character'); window.onload = function() { toggleForm('register'); };</script>";
                return;
            }

            if ($password !== $confirmPassword) {
                echo "<script>alert('Passwords do not match'); window.onload = function() { toggleForm('register'); };</script>";
                return;
            }

            $escapedPassword = mysqli_real_escape_string($this->db_handle, $password);

            $insert = "INSERT INTO sellers (name, phone, email, password) 
                       VALUES ('$name', '$phone', '$email', '$escapedPassword')";
            if (mysqli_query($this->db_handle, $insert)) {
                echo "<script>alert('Registered successfully');</script>";
            } else {
                echo "<script>alert('Registration failed'); window.onload = function() { toggleForm('register'); };</script>";
            }
        }
    }

    public function login() {
        if ($this->db_handle) {
            $email = mysqli_real_escape_string($this->db_handle, $_POST["t1"]);
            $password = mysqli_real_escape_string($this->db_handle, $_POST["t2"]);

            $query = "SELECT * FROM sellers WHERE email = '$email' AND password = '$password'";
            $result = mysqli_query($this->db_handle, $query);

            if ($result && mysqli_num_rows($result) == 1) {
                $seller = mysqli_fetch_assoc($result);
                $_SESSION['seller_id'] = $seller['seller_id'];
                $_SESSION['seller_email'] = $seller['email'];
                $_SESSION['seller_name'] = $seller['name'];

                // Use header() for redirect without JS
                header("Location: SellerDashboard.php");
                exit;
            } else {
                echo "<script>alert('Invalid Email or Password'); window.onload = function() { toggleForm('login'); };</script>";
            }
        }
    }
}

$ob = new Sellers();
if (isset($_POST["b1"])) {
    $ob->register();
}
if (isset($_POST["b2"])) {
    $ob->login();
}

ob_end_flush(); // End output buffering
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Heritage Aromas - Premium quality products with best prices">
    <title>Login/Register</title>
    
    <!-- Preload critical resources -->
    <!-- Bootstrap CSS (preloaded for performance) -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"></noscript>
    
    <!-- Font Awesome -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"></noscript>
    
    <!-- Custom CSS -->
    <link rel="preload" href="../Assests/Css/login.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap"></noscript>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
        <div class="container">
            <a class="navbar-brand" href="SellerDashboard.php">Seller Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../shop.php">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="../contact.php">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Seller Login/Register Form -->
    <div class="form-wrapper">
        <div class="form-slider" id="formSlider">

            <!-- Login Form -->
            <div class="form-content" id="loginForm">
                <h2 class="text-center">Seller Login</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="t1" required />
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" name="t2" required />
                    </div>
                    <button type="submit" name="b2" class="btn btn-primary w-100">Login</button>
                    <div class="text-center">
                        <button class="btn-link" onclick="toggleForm('register')">Don't have an account? Register</button>
                    </div>
                </form>
            </div>

            <!-- Register Form -->
            <div class="form-content" id="registerForm">
                <h2 class="text-center">Seller Register</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" class="form-control" name="t1" required />
                    </div>
                    <div class="mb-3">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" name="t2" required />
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="t3" required />
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" name="t4" id="password" required />
                        <small class="form-text text-muted">Must include at least 1 uppercase, 1 lowercase, 1 number, and 1 special character.</small>
                    </div>
                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" name="t5" required />
                    </div>
                    <button type="submit" name="b1" class="btn btn-success w-100">Register</button>
                    <div class="text-center">
                        <button class="btn-link" onclick="toggleForm('login')">Already have an account? Login</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <?php include('seller_footer.php'); ?>
<!-- Bootstrap JavaScript Bundle with Popper (deferred for performance) -->
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleForm(type) {
      const slider = document.getElementById('formSlider');
      if (type === 'register') {
        slider.style.transform = 'translateX(-400px)';
      } else {
        slider.style.transform = 'translateX(0)';
      }
    }

    // special character code for password
    document.querySelector('form[action="register.php"]').addEventListener('submit', function(e) {
      const password = document.getElementById('password').value;
      const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;

      if (!regex.test(password)) {
        e.preventDefault(); // Stop form submission
        alert("Password must include at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.");
      }
    });
  </script>
</body>
</html>