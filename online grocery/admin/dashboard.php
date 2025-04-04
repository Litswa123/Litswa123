<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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
        .dashboard-card {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }
        .card {
            background:rgb(83, 120, 156);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
            width: calc(33.33% - 20px);
            text-align: center;
        }
        .card h3 {
            margin-bottom: 10px;
        }
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: absolute;
            bottom: 0;
            width: 100%;
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
        <h2>Welcome, Admin</h2>
        <div class="dashboard-card">
            <div class="card">
                <h3>Products</h3>
                <p>Manage and update products.</p>
                <a href="manage_products.php">Go to Products</a>
            </div>
            <div class="card">
                <h3>Orders</h3>
                <p>View and process customer orders.</p>
                <a href="manage_orders.php">Go to Orders</a>
            </div>
            <div class="card">
                <h3>Users</h3>
                <p>Manage registered users.</p>
                <a href="manage_users.php">Go to Users</a>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Online Grocery Shop - Admin Panel</p>
    </footer>
</body>
</html>
