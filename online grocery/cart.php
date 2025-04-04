<?php
session_start();
include('db.php');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding to cart
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1; // Start with quantity 1
    } else {
        $_SESSION['cart'][$product_id]++; // Increase quantity
    }
}

// Handle removing item
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .cart-table th, .cart-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .cart-table th {
            background: #333;
            color: white;
            font-weight: bold;
        }
        .cart-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .cart-table tr:hover {
            background: #f1f1f1;
        }
        .remove-btn {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .remove-btn:hover {
            background: darkred;
        }
    </style>
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

    <div class="container">
        <h2>Your Cart</h2>
        <table class="cart-table">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php 
            $total = 0;
            foreach ($_SESSION['cart'] as $id => $quantity) {
                $query = "SELECT * FROM products WHERE id=$id";
                $result = mysqli_query($conn, $query);
                if ($product = mysqli_fetch_assoc($result)) {
                    $subtotal = $product['price'] * $quantity;
                    $total += $subtotal;
                    echo "<tr>
                        <td>{$product['name']}</td>
                        <td>Ksh " . number_format($product['price'], 2) . "</td>
                        <td>{$quantity}</td>
                        <td>Ksh " . number_format($subtotal, 2) . "</td>
                        <td><a href='cart.php?remove={$id}' class='remove-btn'>Remove</a></td>
                    </tr>";
                }
            }
            ?>
            <tr>
                <th colspan="3">Total</th>
                <th>Ksh <?php echo number_format($total, 2); ?></th>
                <th></th>
            </tr>
        </table>

        <a href="checkout.php">Proceed to Checkout</a>
    </div>

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
