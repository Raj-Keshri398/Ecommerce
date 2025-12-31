<?php
session_start();

// Prevent cached access
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

require_once 'connect.php';
$db = new DBConnect();
$conn = $db->db_handle;

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    $_SESSION['msg'] = "Your cart is empty!";
    header("Location: index.php");
    exit();
}

// Calculate cart count and total
$count = 0;
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $count += $item['product_quantity'];
    $total += $item['product_price'] * $item['product_quantity'];
}
$_SESSION['total'] = $total;

// Fetch user data securely
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT user_name, user_email, user_phone FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

// Fetch default address (if needed)
$stmt2 = $conn->prepare("SELECT * FROM addresses WHERE user_id = ?");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$address_result = $stmt2->get_result();
$address = $address_result->fetch_assoc();
$stmt2->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Heritage Aromas - Cart Summary</title>
     
     <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="Assests/Css/style.css" />
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #fff;
    }

    .summary-card, .address-info {
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin: 20px auto;
        max-width: 900px;
        background-color: #f8f9fa;
    }

    img.card-img-top {
        height: 200px;
        object-fit: cover;
    }

    .address-section h4 {
        font-size: 1.1rem;
        margin-bottom: 10px;
    }

    table img {
        max-width: 100%;
        height: auto;
    }

    .table td, .table th {
        vertical-align: middle;
        text-align: center;
    }

    @media (max-width: 992px) {
        .summary-card, .address-info {
            margin: 15px 10px;
            padding: 15px;
        }

        .summary-card h4, .address-info h4 {
            font-size: 1.1rem;
        }

        .table td, .table th {
            font-size: 0.9rem;
        }

        .btn {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 768px) {
        .address-info .row > div {
            margin-bottom: 10px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .summary-card h4,
        .address-info h4 {
            font-size: 1rem;
        }

        .summary-card table td,
        .summary-card table th {
            font-size: 0.85rem;
        }

        .summary-card img {
            height: 60px;
            width: auto;
        }

        .btn-lg {
            padding: 10px 20px;
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .summary-card,
        .address-info {
            padding: 10px;
        }

        .summary-card h4,
        .address-info h4 {
            font-size: 0.95rem;
        }

        .btn {
            width: 100%;
            margin-top: 10px;
        }

        .table td, .table th {
            padding: 0.5rem;
        }
    }
</style>

</head>
<body>

<!-- Navbar -->
<?php include('navbar.php'); ?>

<div style="margin-top: 90px;"></div>

<!-- User Account Info -->
<div class="summary-card">
    <h4>Account Information</h4>
    <div class="row">
        <div class="col-md-4"><strong>Name:</strong> <?= htmlspecialchars($user['user_name']) ?></div>
        <div class="col-md-4"><strong>Email:</strong> <?= htmlspecialchars($user['user_email']) ?></div>
        <div class="col-md-4"><strong>Mobile:</strong> <?= htmlspecialchars($user['user_phone']) ?></div>
    </div>
</div>

<!-- Address Info -->
<div class="address-info">
    <h4>Address Details</h4>
    <div class="address-section p-3 border rounded bg-light">
        <h4>Delivery Address
            <a href="addresshistory.php" class="btn btn-sm btn-outline-primary float-end">Change</a>
        </h4>

        <?php
        if (isset($_SESSION['selected_address_id'])) {
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $selected_id = $_SESSION['selected_address_id'];
            $user_id = $_SESSION['user_id'];

            $stmt = $conn->prepare("SELECT * FROM addresses WHERE address_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $selected_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                echo "<p><strong>" . htmlspecialchars($row['name']) . "</strong><br>";
                echo nl2br(htmlspecialchars($row['address'])) . "<br>";
                echo htmlspecialchars($row['city']) . ", " . htmlspecialchars($row['pincode']) . "</p>";
            } else {
                echo "<p class='text-danger'>Selected address not found.</p>";
            }

            $stmt->close();
            // Do NOT close connection here if more DB queries below
        } else {
            echo "<p class='text-warning'>No address selected. <a href='addresshistory.php'>Select an address</a></p>";
        }
        ?>
    </div>
</div>

<!-- Cart Summary Section -->
<div class="summary-card">
    <h4>Your Order Summary</h4>
    <?php if (!empty($_SESSION['cart'])): ?>
        <div class="table-responsive">
            <table class="table table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>MRP (RS)</th>
                        <th>Quantity</th>
                        <th>Subtotal (RS)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $item): 
                        $subtotal = $item['product_price'] * $item['product_quantity'];
                    ?>
                    <tr>
                        <td>
                            <img src="Assests/imgs/<?= htmlspecialchars($item['product_image']); ?>" alt="<?= htmlspecialchars($item['product_name']); ?>" style="height: 80px; width: auto;">
                        </td>
                        <td><?= htmlspecialchars($item['product_name']); ?></td>
                        <td><?= number_format($item['product_price'], 2); ?></td>
                        <td><?= (int)$item['product_quantity']; ?></td>
                        <td><?= number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total</strong></td>
                        <td><strong>RS <?= number_format($total, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Your cart is empty.</div>
    <?php endif; ?>
</div>


<!-- Proceed to Payment Button or Message -->
<div class="summary-card text-center">
    <?php if (!isset($_SESSION['selected_address_id'])): ?>
        <div class="alert alert-danger">
            Please select a delivery address before proceeding to payment.
        </div>
        <a href="addresshistory.php" class="btn btn-primary">Select Address</a>
    <?php else: ?>
        <form action="payment.php" method="post">
            <button type="submit" name="proceed_to_payment" class="btn btn-success btn-lg">Proceed to Payment</button>
        </form>
    <?php endif; ?>
</div>

<!-- Footer -->
<?php include('footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" defer></script>

</body>
</html>
