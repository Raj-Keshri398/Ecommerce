<?php
session_start();
require_once 'connect.php';
$db = new DBConnect();
$conn = $db->db_handle;

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$address_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch existing address
$sql = "SELECT * FROM addresses WHERE address_id = $address_id AND user_id = " . $_SESSION['user_id'];
$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows($result) === 0) {
    echo "Address not found or you don't have permission to edit it.";
    exit();
}

$address = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address_text = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);

    $update = "UPDATE addresses SET name='$name', address='$address_text', city='$city', pincode='$pincode' WHERE address_id=$address_id AND user_id=" . $_SESSION['user_id'];
    if (mysqli_query($conn, $update)) {
        header("Location: addresshistory.php");
        exit();
    } else {
        echo "Error updating address: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Address</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .container {
        width: 100%;
        max-width: 600px;
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 30px;
        text-align: center;
    }

    .form-group label {
        font-weight: 500;
    }

    .btn {
        min-width: 120px;
    }

    @media (max-width: 768px) {
        .container {
            max-width: 100%;
            
            border-radius: 0;
            box-shadow: none;
            padding: 0px 0px;
        }

        .btn {
            width: 100%;
            margin-bottom: 10px;
        }

        .btn + .btn {
            margin-left: 0;
        }
    }

    @media (max-width: 480px) {
        h2 {
            font-size: 1.4rem;
        }

        .form-group label {
            font-size: 14px;
        }

        .form-control {
            font-size: 14px;
            padding: 8px;
        }
    }
</style>
</head>

<body>
<div class="wrapper">
    <div class="container">
        <h2>Edit Address</h2>
        <form method="post">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($address['name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control" required><?= htmlspecialchars($address['address']) ?></textarea>
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($address['city']) ?>" required>
            </div>
            <div class="form-group">
                <label>Pincode</label>
                <input type="text" name="pincode" class="form-control" value="<?= htmlspecialchars($address['pincode']) ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update Address</button>
            <a href="addresshistory.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
</body>
