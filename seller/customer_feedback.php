<?php
session_start();
if (!isset($_SESSION['seller_id'])) {
    header("Location: seller_login_register.php");
    exit;
}

require_once '../connect.php';
$db = new DBConnect();
$conn = $db->db_handle;

$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .table-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        /* Responsive Table like seller_order.php */
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

<div class="container my-5">
    <h3 class="text-center mb-4">Customer Feedback</h3>
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Received At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td data-label="#"><?php echo $i++; ?></td>
                                <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td data-label="Phone"><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td data-label="Subject"><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td data-label="Message"><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                                <td data-label="Received At"><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No feedback found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('seller_footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
