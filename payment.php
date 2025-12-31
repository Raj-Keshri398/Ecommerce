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

// ✅✅ FIX: get the selected address from session so it can be submitted in the form
$selected_address_id = $_SESSION['selected_address_id'] ?? null;

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
    <meta charset="UTF-8">
    <title>Payment - Heritage Aromas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="Assests/Css/style.css">
    <link rel="stylesheet" href="Assests/Css/checkout.css">
    <style>
        #upi-options, #debit-card, #credit-card {
            display: none;
        }

        section.container h5 {
            margin-top: 200px;
            font-size: 1.5rem;
            color: #333;
            font-weight: 600;
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<section class="container mb-5">
    <h5>Total Amount: <strong>₹<?php echo htmlspecialchars($total); ?></strong></h5>
    <form method="post" action="server/place_order.php">

        <!-- Total Amount -->
        <input type="hidden" name="total_amount" value="<?php echo number_format($total, 2); ?>">
        
        <!-- Pass cart products -->
        <?php foreach ($_SESSION['cart'] as $product_id => $product): ?>
            <input type="hidden" name="products[<?php echo $product_id; ?>][product_id]" value="<?php echo $product['product_id']; ?>">
            <input type="hidden" name="products[<?php echo $product_id; ?>][quantity]" value="<?php echo $product['product_quantity']; ?>">
        <?php endforeach; ?>

        <!-- Pass selected address -->
        <input type="hidden" name="selected_address_id" value="<?php echo $selected_address_id; ?>">

        <!-- Payment Options -->
        <div class="mb-3 mt-4">
            <label><strong>Select Payment Method:</strong></label><br>
            <div class="form-check"><input class="form-check-input" type="radio" name="payment_method" value="Cash on Delivery" required><label class="form-check-label">Cash on Delivery</label></div>
            <div class="form-check"><input class="form-check-input" type="radio" name="payment_method" value="UPI"><label class="form-check-label">UPI</label></div>
            <div class="form-check"><input class="form-check-input" type="radio" name="payment_method" value="Debit Card"><label class="form-check-label">Debit Card</label></div>
            <div class="form-check"><input class="form-check-input" type="radio" name="payment_method" value="Credit Card"><label class="form-check-label">Credit Card</label></div>
        </div>

        <!-- UPI -->
        <div id="upi-options" class="extra-section">
            <label>Select UPI App:</label><br>
            <div class="form-check"><input type="radio" name="upi_app" value="Google Pay" class="form-check-input"><label class="form-check-label">Google Pay</label></div>
            <div class="form-check"><input type="radio" name="upi_app" value="PhonePe" class="form-check-input"><label class="form-check-label">PhonePe</label></div>
            <div class="form-check"><input type="radio" name="upi_app" value="Paytm" class="form-check-input"><label class="form-check-label">Paytm</label></div>
        </div>

        <!-- Debit Card -->
        <div id="debit-card" class="extra-section">
            <label>Debit Card Details:</label>
            <input type="text" class="form-control mb-2" placeholder="Card Number" name="card_number">
            <input type="text" class="form-control mb-2" placeholder="Expiry (MM/YY)" name="expiry">
            <input type="text" class="form-control mb-2" placeholder="CVV" name="cvv">
        </div>

        <!-- Credit Card -->
        <div id="credit-card" class="extra-section">
            <label>Credit Card Details:</label>
            <input type="text" class="form-control mb-2" placeholder="Card Number" name="card_number">
            <input type="text" class="form-control mb-2" placeholder="Expiry (MM/YY)" name="expiry">
            <input type="text" class="form-control mb-2" placeholder="CVV" name="cvv">
        </div>

        <input type="submit" name="payment_successfully" class="btn btn-success mt-3" value="Payment Successfully">
        
    </form>
</section>

<?php include('footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const upiDiv = document.getElementById('upi-options');
    const debitDiv = document.getElementById('debit-card');
    const creditDiv = document.getElementById('credit-card');
    const form = document.querySelector('form');

    function hideAll() {
        upiDiv.style.display = 'none';
        debitDiv.style.display = 'none';
        creditDiv.style.display = 'none';
    }

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            hideAll();
            if (this.value === 'UPI') upiDiv.style.display = 'block';
            if (this.value === 'Debit Card') debitDiv.style.display = 'block';
            if (this.value === 'Credit Card') creditDiv.style.display = 'block';
        });
    });

    form.addEventListener('submit', function (e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
        const upiApp = document.querySelector('input[name="upi_app"]:checked')?.value;

        if (paymentMethod === "UPI") {
            e.preventDefault(); // Stop form temporarily

            const amount = "<?php echo htmlspecialchars($total); ?>"; // Get dynamic PHP total

            let upiUrl = "";

            switch (upiApp) {
                case "Google Pay":
                    upiUrl = `upi://pay?pa=keshriraj093-1@okicici&pn=HeritageAromas&tn=Payment for order&am=${amount}&cu=INR`;
                    break;
                case "PhonePe":
                    upiUrl = `phonepe://pay?pa=keshriraj093-1@okicici&pn=HeritageAromas&tn=Payment for order&am=${amount}&cu=INR`;
                    break;
                case "Paytm":
                    upiUrl = `paytmmp://pay?pa=keshriraj093-1@okicici&pn=HeritageAromas&tn=Payment for order&am=${amount}&cu=INR`;
                    break;
            }

            if (upiUrl !== "") {
                // Redirect to UPI app
                window.location.href = upiUrl;

                // After 3 seconds, auto-submit the form
                setTimeout(() => {
                    form.submit();
                }, 3000);
            } else {
                alert("Please select a UPI app before proceeding.");
            }
        }
    });
});
</script>

</body>
</html>
