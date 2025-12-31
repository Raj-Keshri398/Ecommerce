<?php
session_start();


// Prevent cached access
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

require_once 'connect.php';
$db = new DBConnect();
$conn = $db->db_handle;

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Select Address - Heritage Aromas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" />
    <style>
        body {
            background-color: #f9f9f9;
        }

        h2 {
            margin-bottom: 30px;
        }

        .address-card {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .form-check-input {
            margin-right: 10px;
        }

        .form-check-label {
            cursor: pointer;
            font-size: 15px;
        }

        .action-buttons .btn {
            margin-right: 8px;
        }

        .btn-primary {
            margin-top: 20px;
        }

        .btn-link {
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
        }
    </style>

</head>
<body>
<div class="container mt-5">
    <h2>Select a Delivery Address</h2>
    <form action="set_selected_address.php" method="post">
        <?php
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM addresses WHERE user_id = $user_id";
        $result = $conn->query($sql);

        if (!$result) {
            die("<div class='alert alert-danger'>Database query failed: " . $conn->error . "</div>");
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='address-card'>";
                    echo "<div class='form-check mb-3'>";
                        echo "<input class='form-check-input' type='radio' name='selected_address_id' value='" . htmlspecialchars($row['address_id']) . "' id='address" . htmlspecialchars($row['address_id']) . "' required>";
                        echo "<label class='form-check-label' for='address" . htmlspecialchars($row['address_id']) . "'>";
                        echo "<strong>" . htmlspecialchars($row['name']) . "</strong><br>";
                        echo nl2br(htmlspecialchars($row['address'])) . "<br>";
                        echo htmlspecialchars($row['city']) . ", " . htmlspecialchars($row['pincode']);
                        echo "</label>";
                    echo "</div>";

                        // Edit & Delete buttons
                    echo "<div class='action-buttons mt-2'>";
                        echo "<a href='edit_address.php?id=" . $row['address_id'] . "' class='btn btn-sm btn-warning'>Edit</a> ";
                        echo "<a href='delete_address.php?id=" . $row['address_id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this address?');\">Delete</a>";
                    echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No saved addresses found.</p>";
        }
        ?>
        <button type="submit" class="btn btn-primary">Confirm Address</button>
    </form>

    <a href="address.php" class="btn btn-link mt-3">+ Add New Address</a>
</div>
</body>
</html>
