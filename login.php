<?php
require_once 'connect.php';


// Cart item count
$count = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['product_quantity'];
    }
}


class Users extends DBconnect {
    public function __construct() {
        parent::__construct();
    }

   public function save() {
        if ($this->db_handle) {
            $name = mysqli_real_escape_string($this->db_handle, $_POST["t1"]);
            $email = mysqli_real_escape_string($this->db_handle, $_POST["t2"]);
            $phone = mysqli_real_escape_string($this->db_handle, $_POST["t3"]);

            // Validate phone number format (only 10 digits)
            if (!preg_match('/^\d{10}$/', $phone)) {
                echo "<script>
                        alert('Phone number must be exactly 10 digits');
                        window.onload = function() {
                            toggleForm('register');
                        }
                    </script>";
                return;
            }

            // ✅ Check if phone already exists
            $phoneCheck = "SELECT * FROM users WHERE user_phone = '$phone'";
            $phoneResult = mysqli_query($this->db_handle, $phoneCheck);
            if (mysqli_num_rows($phoneResult) > 0) {
                echo "<script>
                        alert('Phone number already registered');
                        window.onload = function() {
                            toggleForm('register');
                        }
                    </script>";
                return;
            }

            $password = $_POST["t4"];
            $confirmPassword = $_POST["t5"];

            // ✅ Check if email already exists
            $emailCheck = "SELECT * FROM users WHERE user_email = '$email'";
            $emailResult = mysqli_query($this->db_handle, $emailCheck);
            if (mysqli_num_rows($emailResult) > 0) {
                echo "<script>
                        alert('Email already registered');
                        window.onload = function() {
                            toggleForm('register');
                        }
                    </script>";
                return;
            }

            // ✅ Password complexity check
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/', $password)) {
                echo "<script>
                        alert('Password must include uppercase, lowercase, number, and special character');
                        window.onload = function() {
                            toggleForm('register');
                        }
                    </script>";
                return;
            }

            // ✅ Password match check
            if ($password !== $confirmPassword) {
                echo "<script>
                        alert('Password and Confirm Password do not match');
                        window.onload = function() {
                            toggleForm('register');
                        }
                    </script>";
                return;
            }

            // Insert new user
            $escapedPassword = mysqli_real_escape_string($this->db_handle, $password);
            $insert_query = "INSERT INTO users (user_name, user_email, user_phone, user_password) 
                            VALUES ('$name', '$email', '$phone', '$escapedPassword')";

            if (mysqli_query($this->db_handle, $insert_query)) {
                echo "<script>alert('Register Successfully');</script>";
            } else {
                echo "<script>
                        alert('Registration failed: " . mysqli_error($this->db_handle) . "');
                        window.onload = function() {
                            toggleForm('register');
                        }
                    </script>";
            }
        } else {
            echo "<script>alert('Database connection failed');</script>";
        }
    }



    public function log() {
      if ($this->db_handle) {
          $email = mysqli_real_escape_string($this->db_handle, $_POST["t1"]);
          $password = mysqli_real_escape_string($this->db_handle, $_POST["t2"]);
  
          $query = "SELECT * FROM users WHERE user_email = '$email' AND user_password = '$password'";
          $result = mysqli_query($this->db_handle, $query);
  
          if ($result && mysqli_num_rows($result) == 1) {
              session_start();
              $user = mysqli_fetch_assoc($result);
              $_SESSION['user_id'] = $user['user_id'];
              $_SESSION['user_name'] = $user['user_name'];
              $_SESSION['user_email'] = $user['user_email'];
  
              echo "<script>window.location.href='Useraccount.php';</script>";
          } else {
              echo "<script>alert('Invalid Email or Password');</script>";
              echo "<script>window.onload = function() { toggleForm('login'); }</script>";
          }
      } else {
          echo "<script>alert('Database connection failed');</script>";
      }
  }
  
}

$ob = new Users();
if (isset($_REQUEST["b1"])) {
    $ob->save();
}

if (isset($_REQUEST["b2"])) {
  $ob->log();
}
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
    <link rel="preload" href="Assests/Css/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="Assests/Css/login.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap"></noscript>
</head>
<body>

    <!-- Navigation - Using Bootstrap navbar component -->
    <?php include('navbar.php'); ?>

    <div class="form-wrapper">
        <div class="form-slider" id="formSlider">
    
          <!-- Login Form -->
          <div class="form-content" id="loginForm">
            <h2 class="text-center">Login</h2>
            <form method="POST">
              <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control" name="t1" placeholder="Email" required />
              </div>
              <div class="mb-3">
                <label>Password</label>
                <input type="password" class="form-control" name="t2" placeholder="Password" required />
              </div>
              <button type="submit" name="b2" class="btn btn-primary w-100">Login</button>
              <div class="text-center">
                <button class="btn-link" onclick="toggleForm('register')">Don't have an account? Register</button>
              </div>
            </form>
          </div>
    
          <!-- Register Form -->
          <div class="form-content" id="registerForm">
            <h2 class="text-center">Register</h2>
            <form action='' method="POST">
              <div class="mb-3">
                <label>Name</label>
                <input type="text" class="form-control" placeholder="Name" name="t1" required />
              </div>
              <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control" placeholder="Email" name="t2" required />
              </div>
              <div class="mb-3">
                <label>Phone</label>
                <input type="tel" class="form-control" placeholder="Mobile Number" name="t3" id="phoneInput"
                      required pattern="\d{10}" maxlength="10" minlength="10" title="Enter 10-digit mobile number" />
              </div>
              <div class="mb-3">
                <label>Password</label>
                <input type="password" class="form-control" placeholder="Password" name="t4" id="password" required />
                  <small id="passwordHelp" class="form-text text-muted">
                    Must include at least 1 uppercase, 1 lowercase, 1 number, and 1 special character.
                  </small>
              </div>
              <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" class="form-control" placeholder="Confirm Password" name="t5" required />
              </div>
              <button type="submit" name="b1" class="btn btn-success w-100">Register</button>
              <div class="text-center">  
                <button class="btn-link" onclick="toggleForm('login')">Already have an account? Login</button>
              </div>
            </form>
          </div>
    
        </div>
      </div>
    


    <!-- Footer -->
    <?php include('footer.php'); ?>


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

    // only for 10 digit mobile number
    document.getElementById('phoneInput').addEventListener('input', function () {
      this.value = this.value.replace(/\D/g, '').slice(0, 10); // Only digits, max 10
    });

  </script>
</body>
</html>