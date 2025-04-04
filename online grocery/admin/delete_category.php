<?php
session_start();
include('../db.php'); // Ensure database connection

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Get category ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Category ID");
}

$category_id = intval($_GET['id']);

// Delete category
$delete_query = "DELETE FROM categories WHERE id = $category_id";
if (mysqli_query($conn, $delete_query)) {
    header("Location: manage_categories.php?success=Category deleted successfully");
    exit();
} else {
    die("Error deleting category: " . mysqli_error($conn));
}
?>
