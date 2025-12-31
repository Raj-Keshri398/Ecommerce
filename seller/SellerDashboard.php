<?php
session_start();

if (!isset($_SESSION['seller_id'])) {
    header("Location: seller_login_register.php");
    exit;
}

require_once '../connect.php';
$db = new DBConnect();
$conn = $db->db_handle;

// Prevent page caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$seller_id = $_SESSION['seller_id'];
$today = date('Y-m-d');

// ---------- Total Sales ----------
$total_sales_query = "SELECT SUM(oi.product_quantity * oi.product_price) AS total_sales
                      FROM order_items oi
                      INNER JOIN products p ON oi.product_id = p.product_id
                      WHERE p.seller_id = ?";
$stmt1 = $conn->prepare($total_sales_query);
$stmt1->bind_param("i", $seller_id);
$stmt1->execute();
$result1 = $stmt1->get_result();
$total_sales = $result1->fetch_assoc()['total_sales'] ?? 0;

// ---------- Today's Sales ----------
$today_sales_query = "SELECT SUM(oi.product_quantity * oi.product_price) AS today_sales
                      FROM order_items oi
                      INNER JOIN orders o ON oi.order_id = o.order_id
                      INNER JOIN products p ON oi.product_id = p.product_id
                      WHERE p.seller_id = ? AND DATE(o.order_date) = ?";
$stmt2 = $conn->prepare($today_sales_query);
$stmt2->bind_param("is", $seller_id, $today);
$stmt2->execute();
$result2 = $stmt2->get_result();
$today_sales = $result2->fetch_assoc()['today_sales'] ?? 0;

// ---------- Expenses & Profit ----------
$total_expense = $total_sales * 0.05;
$today_expense = $today_sales * 0.05;

$total_profit = ($total_sales * 0.2) - $total_expense;
$today_profit = ($today_sales * 0.2) - $today_expense;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Seller Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet"/>
  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Poppins', sans-serif;
    }
    .dashboard-container {
      padding: 30px;
    }
    .stats-box {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 20px;
      text-align: center;
      margin-bottom: 20px;
    }
    .stats-box h4 {
      font-size: 16px;
      color: #555;
    }
    .stats-box h2 {
      font-size: 28px;
      margin-top: 10px;
      color: #007bff;
    }
    .order-table {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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

<!-- Include Seller Navbar -->
<?php include('seller_navbar.php'); ?>

<div class="container dashboard-container">
  <div class="row g-4">
    <div class="col-md-4">
      <div class="stats-box">
        <h4>Total Sales</h4>
        <h2>₹<?= number_format($total_sales) ?></h2>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stats-box">
        <h4>Today Sales</h4>
        <h2>₹<?= number_format($today_sales) ?></h2>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stats-box">
        <h4>Total Profit</h4>
        <h2>₹<?= number_format($total_profit) ?></h2>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stats-box">
        <h4>Today Profit</h4>
        <h2>₹<?= number_format($today_profit) ?></h2>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stats-box">
        <h4>Total Expenses</h4>
        <h2>₹<?= number_format($total_expense) ?></h2>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stats-box">
        <h4>Today Expenses</h4>
        <h2>₹<?= number_format($today_expense) ?></h2>
      </div>
    </div>
  </div>

  <div class="order-table mt-5">
    <h3 class="mb-4">Today's Online Product Orders</h3>
    <div class="table-responsive">
    <table class="table table-striped">
      <thead class="table-dark">
        <tr>
          <th>#Order ID</th>
          <th>Customer Name</th>
          <th>Product</th>
          <th>Qty</th>
          <th>Total</th>
          <th>Status</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT 
                    o.order_id,
                    u.user_name AS customer_name,
                    p.product_name,
                    oi.product_quantity,
                    (oi.product_quantity * oi.product_price) AS total_amount,
                    oi.item_status,
                    o.order_date
                FROM order_items oi
                INNER JOIN orders o ON oi.order_id = o.order_id
                INNER JOIN users u ON o.user_id = u.user_id
                INNER JOIN products p ON oi.product_id = p.product_id
                WHERE p.seller_id = ? AND DATE(o.order_date) = ?
                ORDER BY o.order_date DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $seller_id, $today);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td data-label='Order ID'>#{$row['order_id']}</td>
                      <td data-label='Customer'>{$row['customer_name']}</td>
                      <td data-label='Product'>{$row['product_name']}</td>
                      <td data-label='Qty'>{$row['product_quantity']}</td>
                      <td data-label='Total'>₹" . number_format($row['total_amount']) . "</td>
                      <td data-label='Status'><span class='badge bg-" . 
                          ($row['item_status'] == 'Completed' ? 'success' : 'warning') . 
                          "'>{$row['item_status']}</span></td>
                      <td data-label='Date'>" . date('d-M-Y', strtotime($row['order_date'])) . "</td>
                    </tr>";
            }
        } else {
          echo "<tr><td colspan='7' class='text-center'>No orders found for today.</td></tr>";
        }
        ?>
      </tbody>
    </table>
      </div>
  </div>
</div>

<?php include('seller_footer.php'); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
