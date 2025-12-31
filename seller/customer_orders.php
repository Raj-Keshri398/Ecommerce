<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['seller_id'])) {
    header("Location: seller_login_register.php");
    exit;
}

$db = new DBConnect();
$conn = $db->db_handle;

$seller_id = $_SESSION['seller_id'];
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id === 0) {
    die("Invalid customer.");
}

// Fetch user info
$user_info_query = "SELECT user_name, user_email FROM users WHERE user_id = '$user_id'";
$user_info_result = mysqli_query($conn, $user_info_query);
$user_info = mysqli_fetch_assoc($user_info_result);

// Fetch all purchases of this user from the current seller
$order_query = "
    SELECT 
        o.order_id,
        o.order_date,
        o.payment_method,
        oi.item_status,
        oi.product_name,
        oi.product_quantity,
        oi.item_total
    FROM 
        orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    WHERE 
        o.user_id = '$user_id' AND 
        p.seller_id = '$seller_id'
    ORDER BY o.order_date DESC";

$order_result = mysqli_query($conn, $order_query);
$order_count = mysqli_num_rows($order_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Customer Purchase Detail</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      background-color: #f9fafb;
      font-family: 'Poppins', sans-serif;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    .table th, .table td {
      vertical-align: middle;
    }

    @media (max-width: 768px) {
  .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  .table thead {
    display: none;
  }

  .table td {
    display: block;
    width: 100%;
    text-align: right;
    position: relative;
    padding-left: 50%;
    white-space: normal;
  }

  .table td::before {
    content: attr(data-label);
    position: absolute;
    left: 0;
    width: 50%;
    padding-left: 15px;
    font-weight: bold;
    text-align: left;
  }

  .table tr {
    margin-bottom: 1rem;
    display: block;
    border: 1px solid #dee2e6;
  }
}

  </style>
</head>
<body>

<?php include('seller_navbar.php'); ?>

<div class="container my-5">
  <div class="card p-4">
    <h4 class="mb-3">Customer Purchase Summary</h4>
    <p><strong>Name:</strong> <?= htmlspecialchars($user_info['user_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user_info['user_email']) ?></p>
    <p><strong>Total Orders from You:</strong> <?= $order_count ?></p>

    <?php if ($order_count > 0): ?>
      <hr>
      <h5>Purchase History</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-hover mt-3">
          <thead class="table-dark text-center">
            <tr>
              <th>Order ID</th>
              <th>Product</th>
              <th>Qty</th>
              <th>Total</th>
              <th>Status</th>
              <th>Payment</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <?php while ($row = mysqli_fetch_assoc($order_result)): ?>
              <tr>
                <td data-label="Order ID">#<?= $row['order_id'] ?></td>
                <td data-label="Product"><?= htmlspecialchars($row['product_name']) ?></td>
                <td data-label="Qty"><?= $row['product_quantity'] ?></td>
                <td data-label="Total">₹<?= $row['item_total'] ?></td>
                <td data-label="Status">
                  <?php
                    $status = $row['item_status'];
                    if ($status === 'Pending') {
                        echo '<span class="badge bg-warning text-dark">Pending</span>';
                    } elseif ($status === 'Completed') {
                        echo '<span class="badge bg-success">Completed</span>';
                    } else {
                        echo '<span class="badge bg-danger">Cancelled</span>';
                    }
                  ?>
                </td>
                <td data-label="Payment"><?= $row['payment_method'] ?></td>
                <td data-label="Date"><?= date('d M Y', strtotime($row['order_date'])) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>

        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info mt-4">This customer hasn't purchased from you yet.</div>
    <?php endif; ?>
  </div>
</div>

<?php include('seller_footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
