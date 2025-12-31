<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Placed Successfully</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap & Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .success-wrapper {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-card {
            max-width: 500px;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        @media (max-width: 576px) {
            h2 {
                font-size: 1.5rem;
            }

            .lead {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container success-wrapper">
    <div class="alert alert-success p-4 text-center success-card">
        <h2 class="mb-3">
            <i class="fa fa-check-circle me-2"></i>Order Placed Successfully!
        </h2>
        <p class="lead mb-4">Thank you for shopping with <strong>Heritage Aromas</strong>.</p>
        <a href="index.php" class="btn btn-primary px-4">Add More from Shop</a>
    </div>
</div>

<?php include('footer.php'); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
