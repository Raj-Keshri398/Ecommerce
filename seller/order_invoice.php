<?php
require_once '../connect.php';
session_start();

if (!isset($_SESSION['seller_id'])) {
    header("Location: seller_login_register.php");
    exit;
}

if (!isset($_GET['order_id'])) {
    die("Order ID missing.");
}

$order_id = intval($_GET['order_id']);
$seller_id = intval($_SESSION['seller_id']);

$db = new DBConnect();
$conn = $db->db_handle;

// ✅ Step 1: Check if the seller has any product in this order
$check_sql = "
SELECT COUNT(*) AS cnt
FROM orders o
JOIN order_items oi ON o.order_id = oi.order_id
JOIN products p ON oi.product_id = p.product_id
WHERE o.order_id = $order_id AND p.seller_id = $seller_id
";
$check_result = mysqli_query($conn, $check_sql);
$check = mysqli_fetch_assoc($check_result);

if ($check['cnt'] == 0) {
    die("Unauthorized access. This order is not related to your products.");
}

// ✅ Step 2: Update order status to 'Delivery' only if it's currently 'Pending'
$update_sql = "
UPDATE order_items oi
JOIN products p ON oi.product_id = p.product_id
SET oi.item_status = 'Delivery'
WHERE oi.order_id = $order_id AND p.seller_id = $seller_id AND oi.item_status = 'Pending'";

mysqli_query($conn, $update_sql);

// ✅ Step 3: Fetch full order details for this seller's products
$sql = "
SELECT 
    o.order_id,
    o.order_status,
    o.order_date,
    o.payment_method,
    o.user_address,
    o.user_city,
    o.user_phone,
    u.user_name,
    u.user_email,
    oi.product_name,
    oi.product_quantity,
    oi.product_price,
    oi.item_total
FROM 
    orders o
JOIN 
    order_items oi ON o.order_id = oi.order_id
JOIN 
    users u ON o.user_id = u.user_id
JOIN 
    products p ON oi.product_id = p.product_id
WHERE 
    o.order_id = $order_id AND p.seller_id = $seller_id
";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Order found, but no items belong to this seller.");
}

$row = mysqli_fetch_assoc($result); // First row for header info
?>

<!DOCTYPE html>
<html>
<head>
  <title>Order Invoice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      padding: 20px;
      font-family: 'Arial', sans-serif;
      background-color: #f8f9fa;
    }
    .invoice-box {
      max-width: 850px;
      margin: auto;
      padding: 30px;
      border: 1px solid #dee2e6;
      background: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .invoice-box h2 {
      margin-bottom: 20px;
      color: #333;
    }
    .no-print {
      margin-top: 20px;
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body>

<div class="invoice-box">
  <h2>Order Invoice</h2>
  
  <p><strong>Order ID:</strong> #<?= $row['order_id'] ?></p>
  <p><strong>Order Date:</strong> <?= date('d-M-Y', strtotime($row['order_date'])) ?></p>
  <p><strong>Status:</strong> <?= htmlspecialchars(ucfirst($row['order_status'])) ?></p>
  <p><strong>Payment Method:</strong> <?= $row['payment_method'] ?></p>

  <hr>

  <h5>Customer Info</h5>
  <p>
    <strong>Name:</strong> <?= htmlspecialchars($row['user_name']) ?><br>
    <strong>Email:</strong> <?= htmlspecialchars($row['user_email']) ?><br>
    <strong>Phone:</strong> <?= htmlspecialchars($row['user_phone']) ?><br>
    <strong>Address:</strong> <?= htmlspecialchars($row['user_address']) ?>, <?= htmlspecialchars($row['user_city']) ?>
  </p>

  <hr>

  <h5>Product Info</h5>
  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>Product</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Item Total</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      mysqli_data_seek($result, 0); // Reset pointer
      $grand_total = 0;
      while ($item = mysqli_fetch_assoc($result)) :
          $grand_total += $item['item_total'];
      ?>
      <tr>
        <td><?= htmlspecialchars($item['product_name']) ?></td>
        <td><?= intval($item['product_quantity']) ?></td>
        <td>₹<?= number_format($item['product_price'], 2) ?></td>
        <td>₹<?= number_format($item['item_total'], 2) ?></td>
      </tr>
      <?php endwhile; ?>
      <tr>
        <td colspan="3" class="text-end"><strong>Grand Total</strong></td>
        <td><strong>₹<?= number_format($grand_total, 2) ?></strong></td>
      </tr>
    </tbody>
  </table>

  <div class="text-center no-print">
    <a href="#" onclick="window.print()" class="btn btn-success">Download / Print</a>
    <a href="seller_order.php" class="btn btn-secondary">Back</a>
  </div>
</div>

</body>
</html>
