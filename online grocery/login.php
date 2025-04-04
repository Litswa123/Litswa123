<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['user_id'] = mysqli_fetch_assoc($result)['id'];
        header('Location: index.php');
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Online Grocery Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>

    <footer>
        <div class="footer-content">
            <div class="about">
                <h3>About Us</h3>
                <p>Your trusted online grocery store. Fresh groceries delivered fast.</p>
            </div>
            <div class="contact">
                <h3>Contact Us</h3>
                <p>Email: -support@groceryshop.com</p>
                <p>Phone: +254 712 345 678</p>
            </div>
        </div>
        <p class="copyright">© 2025 Online Grocery Shop. All rights reserved.</p>
    </footer>
</body>
</html>
