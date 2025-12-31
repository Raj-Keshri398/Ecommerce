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

// Fetch order data for this seller
$sql = "
SELECT 
    o.order_id,
    o.order_date,
    o.payment_method,
    o.user_address,
    o.user_phone,
    o.user_city,
    oi.product_name,
    oi.product_quantity,
    oi.item_total,
    oi.item_status,
    u.user_id,
    u.user_name AS customer_name,
    u.user_email
FROM 
    orders o
JOIN 
    order_items oi ON o.order_id = oi.order_id
JOIN 
    users u ON o.user_id = u.user_id
JOIN 
    products p ON oi.product_id = p.product_id
WHERE 
    p.seller_id = '$seller_id'
ORDER BY o.order_date DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Seller Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>

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
    .badge-status {
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 14px;
    }
    .badge-pending {
      background-color: #facc15;
      color: #000;
    }
    .badge-completed {
      background-color: #16a34a;
      color: white;
    }
    .badge-cancelled {
      background-color: #ef4444;
      color: white;
    }
    .customer-box {
      text-align: left;
      font-size: 14px;
      line-height: 1.4;
    }
    .customer-box strong {
      color: #374151;
    }

    /* 🔽 Responsive Table for Mobile */
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

<div class="container">
  <h2 class="text-xl font-semibold mt-5">Customer Orders</h2>
  <div class="table-container table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark text-center align-middle">
            <tr>
                <th>Order ID</th>
                <th>Customer Info</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr class="text-center align-middle" id="orderRow<?= $row['order_id'] ?>">
                    <td data-label="Order ID">#<?= htmlspecialchars($row['order_id']) ?></td>
                    <td data-label="Customer Info" class="customer-box">
                        <strong>Name:</strong> <?= htmlspecialchars($row['customer_name']) ?><br>
                        <strong>Email:</strong> <?= htmlspecialchars($row['user_email']) ?><br>
                        <strong>Phone:</strong> <?= htmlspecialchars($row['user_phone']) ?><br>
                        <strong>City:</strong> <?= htmlspecialchars($row['user_city']) ?><br>
                        <strong>Address:</strong> <?= htmlspecialchars($row['user_address']) ?>
                    </td>
                    <td data-label="Product"><?= htmlspecialchars($row['product_name']) ?></td>
                    <td data-label="Qty"><?= htmlspecialchars($row['product_quantity']) ?></td>
                    <td data-label="Total">₹<?= htmlspecialchars($row['item_total']) ?></td>
                    <td data-label="Status">
                      <?php
                        $item_status = $row['item_status'];
                        if ($item_status === 'Pending') {
                            echo '<span class="badge-status badge-pending">Pending</span>';
                        } elseif ($item_status === 'Delivery') {
                            echo '<span class="badge-status badge-pending">Delivery</span>';
                        } elseif ($item_status === 'Completed') {
                            echo '<span class="badge-status badge-completed">Completed</span>';
                        } else {
                            echo '<span class="badge-status badge-cancelled">Cancelled</span>';
                        }
                      ?>
                    </td>
                    <td data-label="Payment"><?= htmlspecialchars($row['payment_method']) ?></td>
                    <td data-label="Date"><?= date('d-M-Y', strtotime($row['order_date'])) ?></td>

                    <td data-label="Action">
                      <a href="order_invoice.php?order_id=<?= $row['order_id'] ?>" class="btn btn-primary btn-sm mb-1">
                        <i class="fa fa-print"></i> Print
                      </a>
                      <a href="customer_orders.php?user_id=<?= $row['user_id'] ?>" class="btn btn-info btn-sm">
                        <i class="fa fa-user"></i> Detail
                      </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="9" class="text-center text-muted">No orders found for your products.</td>
          </tr>
        <?php endif; ?>
        </tbody>
    </table>
  </div>
</div>

<?php include('seller_footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
