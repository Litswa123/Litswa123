<?php
session_start();
include('../db.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM products WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: manage_products.php");
        exit();
    } else {
        echo "Error deleting product: " . mysqli_error($conn);
    }
}
?>
