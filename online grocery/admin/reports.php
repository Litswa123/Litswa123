<?php
session_start();
include('../db.php'); // Ensure database connection

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Get total number of orders
$total_orders_query = "SELECT COUNT(*) AS total_orders FROM orders";
$total_orders_result = mysqli_query($conn, $total_orders_query);
$total_orders = mysqli_fetch_assoc($total_orders_result)['total_orders'];

// Get total sales (sum of all order amounts)
$total_sales_query = "SELECT SUM(Amount) AS total_sales FROM orders";
$total_sales_result = mysqli_query($conn, $total_sales_query);
$total_sales = mysqli_fetch_assoc($total_sales_result)['total_sales'];

// Get sales data by status (e.g., pending, processed, shipped, completed)
$sales_by_status_query = "SELECT status, COUNT(*) AS count, SUM(Amount) AS total_amount FROM orders GROUP BY status";
$sales_by_status_result = mysqli_query($conn, $sales_by_status_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Sales</title>
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

.sidebar h2 {
    text-align: center;
    color: #fff;
    margin-bottom: 20px;
}

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 15px;
    margin: 5px 0;
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

h2 {
    color: #333;
    font-size: 28px;
    margin-bottom: 20px;
}

.report-section {
    margin-bottom: 30px;
}

.report-section h3 {
    font-size: 22px;
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: white;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th {
    background: #333;
    color: white;
    font-weight: bold;
}

tr:nth-child(even) {
    background: #f9f9f9;
}

tr:hover {
    background: #f1f1f1;
}

a {
    color: green;
    text-decoration: none;
    font-size: 16px;
    margin-top: 20px;
    display: inline-block;
}

a:hover {
    color:rgb(137, 71, 107);
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

footer p {
    margin: 0;
    font-size: 14px;
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
        <h2>Sales Report</h2>

        <div class="report-section">
            <h3>Total Orders: <?php echo $total_orders; ?></h3>
            <h3>Total Sales: Ksh <?php echo number_format($total_sales, 2); ?></h3>
        </div>

        <div class="report-section">
            <h3>Sales by Order Status</h3>
            <table>
                <tr>
                    <th>Status</th>
                    <th>Number of Orders</th>
                    <th>Total Sales (Ksh)</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($sales_by_status_result)) { ?>
                    <tr>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['count']; ?></td>
                        <td>Ksh <?php echo number_format($row['total_amount'], 2); ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <a href="dashboard.php">Back to Dashboard</a>
    </div>

    <footer>
        <p>&copy; 2025 Online Grocery Shop - Admin Panel</p>
    </footer>
</body>
</html>
