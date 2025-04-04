<?php
session_start();
include('db.php');

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch categories
$categories_query = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_query);
$categories = [];
while ($cat = mysqli_fetch_assoc($categories_result)) {
    $categories[$cat['name']] = [];
}

// Fetch products grouped by category
$products_query = "SELECT * FROM products";
$products_result = mysqli_query($conn, $products_query);
while ($row = mysqli_fetch_assoc($products_result)) {
    if (isset($categories[$row['category']])) {
        $categories[$row['category']][] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Online Grocery Shop</title>
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
        <h2>Available Products</h2>
        <?php foreach ($categories as $category => $products) { ?>
            <h3><?php echo $category; ?></h3>
            <div class="product-list">
                <?php foreach ($products as $product) { ?>
                    <div class="product">
                        <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        <h3><?php echo $product['name']; ?></h3>
                        <p>Price: Ksh<?php echo $product['price']; ?></p>
                        <a href="product.php?id=<?php echo $product['id']; ?>">View Details</a>
                        <a href="cart.php?id=<?php echo $product['id']; ?>">Add to Cart</a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
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
