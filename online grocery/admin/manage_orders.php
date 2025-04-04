<?php
session_start();
include('../db.php'); // Ensure database connection

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all orders
$result = mysqli_query($conn, "SELECT * FROM orders ORDER BY ID DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #333;
            color: white;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 15px;
        }
        .sidebar a:hover {
            background: #575757;
        }
        .container {
            margin-left: 260px;
            padding: 20px;
            background: white;
            width: calc(100% - 260px);
            min-height: 100vh;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .product-table th, .product-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .product-table th {
            background: #333;
            color: white;
            font-weight: bold;
        }
        .product-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .product-table tr:hover {
            background: #f1f1f1;
        }
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }
        .form-container {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        }
        .form-container input, .form-container button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            background: green;
            color: white;
            cursor: pointer;
        }
        .form-container button:hover {
            background: #575757;
        }
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .edit-btn, .delete-btn {
        padding: 5px 10px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        margin: 2px;
        display: inline-block;
    }
    .edit-btn {
        background: blue;
    }
    .delete-btn {
        background: red;
    }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="manage_products.php">Manage Products</a>
        <a href="manage_categories.php">Manage Categories</a>
        <a href="manage_orders.php">Manage Orders</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="reports.php">Reports & Sales</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Manage Orders</h2>
        <table class="product-table">
            <tr>
                <th>ID</th>
                <th>Order No</th>
                <th>Amount</th>
                <th>Phone</th>
                <th>Checkout Request ID</th>
                <th>Merchant Request ID</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['ID']; ?></td>
                    <td><?php echo $row['OrderNo']; ?></td>
                    <td>Ksh<?php echo $row['Amount']; ?></td>
                    <td><?php echo $row['Phone']; ?></td>
                    <td><?php echo $row['CheckoutRequestID']; ?></td>
                    <td><?php echo $row['MerchantRequestID']; ?></td>
                    <td>
                        <a href="edit_order.php?id=<?php echo $row['ID']; ?>" class="edit-btn">Edit</a>
                        <a href="delete_order.php?id=<?php echo $row['ID']; ?>" class="delete-btn" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <footer>
        <p>&copy; 2025 Online Grocery Shop - Admin Panel</p>
    </footer>
</body>
</html>
