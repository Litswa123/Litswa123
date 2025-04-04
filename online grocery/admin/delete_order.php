<?php
session_start();
include('../db.php'); // Ensure database connection

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle order deletion
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Prepare the delete query
    $delete_query = "DELETE FROM orders WHERE id = $order_id";

    // Execute the query and check if it was successful
    if (mysqli_query($conn, $delete_query)) {
        // Redirect to manage orders page with a success message
        header('Location: manage_orders.php?success=Order deleted successfully');
        exit();
    } else {
        // If there's an error, redirect with an error message
        header('Location: manage_orders.php?error=Error deleting order');
        exit();
    }
} else {
    // If no order ID is provided, redirect with an error message
    header('Location: manage_orders.php?error=Order ID not provided');
    exit();
}
?>
