<?php
session_start();

include('connect.php'); // Adjust path if needed
$db = new DBConnect();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = mysqli_real_escape_string($db->db_handle, $_POST['name']);
    $email = mysqli_real_escape_string($db->db_handle, $_POST['email']);
    $phone = mysqli_real_escape_string($db->db_handle, $_POST['phone']);
    $subject = mysqli_real_escape_string($db->db_handle, $_POST['subject']);
    $message = mysqli_real_escape_string($db->db_handle, $_POST['message']);

    $sql = "INSERT INTO contact_messages (name, email, phone, subject, message) 
            VALUES ('$name', '$email', '$phone', '$subject', '$message')";

    if (mysqli_query($db->db_handle, $sql)) {
        $success = "Message sent successfully!";
    } else {
        $error = "Error: " . mysqli_error($db->db_handle);
    }
}
?>

<!-- HTML starts below -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Heritage Aromas - Premium quality products with best prices">
    <title>Contact</title>
    <!-- Preload critical resources -->
    <!-- Bootstrap CSS (preloaded for performance) -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"></noscript>
    
    <!-- Font Awesome -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"></noscript>
    
    <!-- Custom CSS -->
    <link rel="preload" href="Assests/Css/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="Assests/Css/style.css"></noscript>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Playfair+Display:wght@700&display=swap"></noscript>
</head>
<body>
    <!-- Navigation -->
    <?php include('navbar.php'); ?>

    <div class="container mt-5 pt-5">
        <h3 class="text-center mb-4">Contact Us</h3>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input required type="text" class="form-control" name="name" id="name" placeholder="Your Name">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input required type="email" class="form-control" name="email" id="email" placeholder="Your Email">
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone</label>
                    <input required type="text" class="form-control" name="phone" id="phone" placeholder="Your Phone">
                </div>
                <div class="col-md-6">
                    <label for="subject" class="form-label">Subject</label>
                    <input required type="text" class="form-control" name="subject" id="subject" placeholder="Subject">
                </div>
                <div class="col-12">
                    <label for="message" class="form-label">Message</label>
                    <textarea required class="form-control" name="message" id="message" rows="5" placeholder="Your message..."></textarea>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-warning">Send Message</button>
            </div>
        </form>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
