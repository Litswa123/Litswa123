<?php
session_start();
include('../db.php'); // Ensure database connection

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch users
$query = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $user_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM users WHERE id = $user_id";

    if (mysqli_query($conn, $delete_query)) {
        header('Location: manage-users.php?success=User deleted successfully');
        exit();
    } else {
        header('Location: manage-users.php?error=Error deleting user');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .user-table th, .user-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .user-table th {
            background: #333;
            color: white;
            font-weight: bold;
        }
        .user-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .user-table tr:hover {
            background: #f1f1f1;
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
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
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
        <h2>Manage Users</h2>

        <!-- Display success or error messages -->
        <?php if (isset($_GET['success'])): ?>
            <p style="color: green;"><?php echo $_GET['success']; ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <p style="color: red;"><?php echo $_GET['error']; ?></p>
        <?php endif; ?>

        <table class="user-table">
            <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                        <a href="manage-users.php?delete_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
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
