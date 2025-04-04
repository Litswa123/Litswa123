<?php
session_start();
include('db.php');

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product details from the database
$product_query = "SELECT * FROM products WHERE id = $product_id";
$product_result = mysqli_query($conn, $product_query);

// Check if the product exists
if (mysqli_num_rows($product_result) > 0) {
    $product = mysqli_fetch_assoc($product_result);
} else {
    echo "Product not found!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $product['name']; ?> - Product Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Online Grocery Shop</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="cart.php">Cart</a>
            <a href="orders.php">Orders</a>
            <a href="logout.php">Logout</a>
            <a href="admin/login.php">Admin</a>
        </nav>
    </header>

    <main class="container">
        <h2>Product Details</h2>

        <div class="product-detail">
            <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-img">
            <h3><?php echo $product['name']; ?></h3>
            <p>Price: Ksh <?php echo $product['price']; ?></p>
            <p>Description: <?php echo $product['description']; ?></p>
            <p>Category: <?php echo $product['category']; ?></p>

            <form action="cart.php" method="GET">
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <button type="submit">Add to Cart</button>
            </form>
        </div>

        <a href="index.php">Back to Home</a>
    </main>

    <footer>
        <div class="footer-content">
            <div class="about">
                <h3>About Us</h3>
                <p>Welcome to Online Grocery Shop, your number one source for fresh and quality groceries.</p>
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
