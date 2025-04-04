<?php
session_start();
include('db.php');

// Fetch all orders from the orders table
$query = "SELECT * FROM orders ORDER BY ID DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Orders</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .order-table th, .order-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .order-table th {
            background: #333;
            color: white;
            font-weight: bold;
        }
        .order-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .order-table tr:hover {
            background: #f1f1f1;
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
        </nav>
    </header>

    <div class="container">
        <h2>Your Orders</h2>

        <?php if (mysqli_num_rows($result) == 0) { ?>
            <p class="no-orders">You have no orders yet. Start shopping!</p>
        <?php } else { ?>
            <table class="order-table">
                <tr>
                    <th>Order No</th>
                    <th>Amount</th>
                    <th>Phone</th>
                    <th>Checkout Request ID</th>
                    <th>Merchant Request ID</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['OrderNo']; ?></td>
                        <td>Ksh <?php echo number_format($row['Amount'], 2); ?></td>
                        <td><?php echo $row['Phone']; ?></td>
                        <td><?php echo $row['CheckoutRequestID']; ?></td>
                        <td><?php echo $row['MerchantRequestID']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>

    <footer>
        <p>&copy; 2025 Online Grocery Shop. All rights reserved.</p>
    </footer>
</body>
</html>
