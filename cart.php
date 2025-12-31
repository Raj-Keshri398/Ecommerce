<?php
session_start();


// Add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_array = array(
        'product_id' => $product_id,
        'product_name' => $_POST['product_name'],
        'product_price' => $_POST['product_price'],
        'product_image' => $_POST['product_image'],
        'product_quantity' => $_POST['product_quantity']
    );

    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] = $product_array;
    } else {
        $_SESSION['cart'] = array($product_id => $product_array);
    }
}

// Remove from cart
if (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link href="Assests/Css/cart.css" rel="stylesheet">
</head>
<body>

<?php include('navbar.php'); ?>

<section class="cart container mt-5 py-5">
    <div class="container mt-5">
        <h2 class="fw-bold">Your Cart</h2>
        <hr>
    </div>
    <div class="table-responsive">
        <table class="table mt-5">
            <thead class="table-warning">
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th class="text-end">Sub Total</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                $total = 0;
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $value) {
                        $subtotal = $value['product_price'] * $value['product_quantity'];
                        $total += $subtotal;
            ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="Assests/imgs/<?php echo $value['product_image']; ?>" alt="<?php echo $value['product_name']; ?>" width="60" class="me-3">
                            <div>
                                <p class="mb-1 fw-semibold"><?php echo $value['product_name']; ?></p>
                                <small>RS <?php echo number_format($value['product_price'], 2); ?></small><br>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>">
                                    <input type="submit" name="remove_product" class="btn btn-link text-danger p-0" value="remove">
                                </form>
                            </div>
                        </div>
                    </td>
                    <td style="width: 120px;">
                        <input type="number"
                               class="form-control quantity-input"
                               min="1"
                               value="<?php echo $value['product_quantity']; ?>"
                               data-id="<?php echo $value['product_id']; ?>"
                               onchange="updateQuantity(this)">
                    </td>
                    <td class="text-end subtotal" data-id="<?php echo $value['product_id']; ?>">
                        RS <?php echo number_format($subtotal, 2); ?>
                    </td>
                </tr>
            <?php
                    }
                } else {
                    echo '<tr><td colspan="3" class="text-center">Your cart is empty.</td></tr>';
                }
            ?>
            </tbody>
        </table>
    </div>

    <div class="cart-total mt-4">
        <table class="table">
            <tbody>
                <tr>
                    <td>Sub Total</td>
                    <td class="text-end" id="subtotal">RS <?php echo number_format($total, 2); ?></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td class="text-end" id="total">RS <?php echo number_format($total, 2); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="text-end mt-4">
        <form method="POST" action="order_place_summary.php">
            <input class="btn btn-primary px-4" value="Place Order" name="Checkout" type="submit">
        </form>
    </div>
</section>

<?php include('footer.php'); ?>

<script>
function updateQuantity(input) {
    const productId = input.dataset.id;
    const quantity = input.value;

    fetch('update_cart_quantity.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `product_id=${productId}&product_quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            document.querySelector(`.subtotal[data-id='${productId}']`).textContent = 'RS ' + data.subtotal;
            document.getElementById('subtotal').textContent = 'RS ' + data.total;
            document.getElementById('total').textContent = 'RS ' + data.total;
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
