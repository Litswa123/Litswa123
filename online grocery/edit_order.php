<?php
session_start();
include('../db.php'); // Ensure database connection

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch order details
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $query = "SELECT * FROM orders WHERE id = $order_id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $order = mysqli_fetch_assoc($result);
    } else {
        $error = "Order not found!";
    }
}

// Handle order update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_order'])) {
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $update_query = "UPDATE orders SET status = '$status' WHERE id = $order_id";
    if (mysqli_query($conn, $update_query)) {
        $success = "Order updated successfully!";
        // Redirect to manage orders page
        header('Location: manage_orders.php');
        exit();
    } else {
        $error = "Error updating order: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Order</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="manage_products.php">Manage Products</a>
        <a
