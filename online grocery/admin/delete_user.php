<?php
session_start();
include('../db.php'); // Ensure database connection

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle user deletion
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $delete_query = "DELETE FROM users WHERE id = $user_id";

    if (mysqli_query($conn, $delete_query)) {
        header('Location: manage-users.php?success=User deleted successfully');
        exit();
    } else {
        header('Location: manage-users.php?error=Error deleting user');
        exit();
    }
} else {
    header('Location: manage-users.php?error=No user specified');
    exit();
}
