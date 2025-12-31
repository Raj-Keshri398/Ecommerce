<?php
session_start();
require_once 'connect.php';

// Create DBConnect object and get the connection
$db = new DBConnect();
$conn = $db->db_handle;

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']); // Sanitize input

// Fetch user phone from users table
$user_sql = "SELECT user_phone FROM users WHERE user_id = $user_id";
$user_result = $conn->query($user_sql);
if (!$user_result) {
    die("User query failed: " . $conn->error);
}
$user = $user_result->fetch_assoc();
$user_phone = $conn->real_escape_string($user['user_phone']);  // Escape for safety

// Fetch order history with JOIN on orders and order_items
$order_sql = "
SELECT o.order_id, 
    oi.item_total, 
    o.order_date, 
    oi.product_name, 
    oi.product_image, 
    oi.product_price, 
    oi.product_quantity,
    oi.item_status 
FROM orders o
JOIN order_items oi ON o.order_id = oi.order_id
WHERE o.user_id = $user_id
ORDER BY o.order_date DESC
";

$order_result = $conn->query($order_sql);
if (!$order_result) {
    die("Order query failed: " . $conn->error);
}

// (Optional) Cart count if you have it
$count = 0;
if (isset($_SESSION['cart'])) {
    $count = count($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Heritage Aromas - Premium quality products with best prices" />
    <title>My Profile - Heritage Aromas</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"/>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet"/>

    <!-- Custom CSS -->
    <link href="Assests/Css/style.css" rel="stylesheet"/>
    <link href="Assests/Css/login.css" rel="stylesheet"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet" />
    
    <style>
        .profile-orders {
            max-width: 1100px;
            margin: 8rem auto 4rem;
            padding: 2rem 1rem;
            background: #fff;
            border-radius: 0.25rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .profile-orders h2 {
            margin-bottom: 2rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            text-align: center;
            color: #333;
        }

        .profile-orders .table-responsive {
            max-height: 450px;
            overflow-y: auto;
            border-radius: 0.25rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
        }

        .profile-orders table {
            min-width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .profile-orders table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            vertical-align: middle;
            border-bottom: 2px solid #dee2e6;
        }

        .profile-orders table tbody tr:hover {
            background-color: #f1f3f5;
        }

        .profile-orders img.img-thumbnail {
            max-width: 80px;
            max-height: 80px;
            object-fit: cover;
            border-radius: 0.25rem;
        }

        .profile-orders .alert {
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .profile-orders .table-responsive thead {
                display: none;
            }

            .profile-orders .table-responsive tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 10px;
            }

            .profile-orders .table-responsive tbody td {
                display: block;
                text-align: right;
                padding-left: 50%;
                position: relative;
                border: none;
                border-bottom: 1px solid #eee;
                font-size: 14px;
            }

            .profile-orders .table-responsive tbody td::before {
                position: absolute;
                left: 15px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: 600;
                text-align: left;
                color: #555;
            }

            /* Add specific labels for each column */
            .profile-orders .table-responsive tbody td:nth-child(1)::before { content: "Order ID"; }
            .profile-orders .table-responsive tbody td:nth-child(2)::before { content: "Date"; }
            .profile-orders .table-responsive tbody td:nth-child(3)::before { content: "Total Cost"; }
            .profile-orders .table-responsive tbody td:nth-child(4)::before { content: "Status"; }
            .profile-orders .table-responsive tbody td:nth-child(5)::before { content: "Product Image"; }
            .profile-orders .table-responsive tbody td:nth-child(6)::before { content: "Product Name"; }
            .profile-orders .table-responsive tbody td:nth-child(7)::before { content: "Product Price"; }
            .profile-orders .table-responsive tbody td:nth-child(8)::before { content: "Quantity"; }

            .profile-orders .table-responsive tbody td:last-child {
                border-bottom: none;
            }
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<section class="profile-orders">
    <h2>Order History</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success text-center">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <?php if ($order_result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total Cost</th>
                        <th>Status</th>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th>Product Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $order_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars(date("d M Y", strtotime($order['order_date']))) ?></td>
                        <td>₹<?= number_format($order['item_total'], 2) ?></td>
                        <td>
                            <?= htmlspecialchars(ucfirst($order['item_status'])) ?>

                            <?php if (in_array($order['item_status'], ['Pending', 'Delivery'])): ?>
                                <form method="POST" action="cancel_order.php" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($order['product_name']) ?>">
                                    <button type="submit" class="btn btn-sm btn-danger mt-1" onclick="return confirm('Are you sure you want to cancel this product?')">Cancel</button>
                                </form>
                            <?php endif; ?>

                            <?php if ($order['item_status'] === 'Delivery'): ?>
                                <form method="POST" action="confirm_delivery.php" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($order['product_name']) ?>">
                                    <button type="submit" class="btn btn-sm btn-success mt-1">Confirm Delivery</button>
                                </form>
                            <?php endif; ?>


                        </td>
                        <td>
                            <img src="Assets/imgs/<?= htmlspecialchars($order['product_image']) ?>" alt="Product Image" class="img-thumbnail" />
                        </td>
                        <td><?= htmlspecialchars($order['product_name']) ?></td>
                        <td>₹<?= number_format($order['product_price'], 2) ?></td>
                        <td><?= intval($order['product_quantity']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">No order history available.</p>
    <?php endif; ?>
</section>

<?php include('footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
