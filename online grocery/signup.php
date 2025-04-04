<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $check_query = "SELECT * FROM users WHERE email='$email'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $error = "Email already exists!";
    } else {
        // Insert user
        $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            header('Location: login.php'); // Redirect to login after signup
            exit();
        } else {
            $error = "Error signing up. Try again!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - Online Grocery Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Sign Up</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

    <footer>
        <div class="footer-content">
            <div class="about">
                <h3>About Us</h3>
                <p>Your trusted online grocery store. Fresh groceries delivered fast.</p>
            </div>
            <div class="contact">
                <h3>Contact Us</h3>
                <p>Email: support@groceryshop.com</p>
                <p>Phone: +254 712 345 678</p>
            </div>
        </div>
        <p class="copyright">© 2025 Online Grocery Shop. All rights reserved.</p>
    </footer>
</body>
</html>
