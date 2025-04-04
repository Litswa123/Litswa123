<?php
session_start();
include('../db.php'); // Ensure database connection

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle product addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_POST['image']; // Assuming image path is provided manually
    $description = $_POST['description'];

    $query = "INSERT INTO products (name, category, price, stock, image, description, created_at) 
              VALUES ('$name', '$category', '$price', '$stock', '$image', '$description', NOW())";

    if (mysqli_query($conn, $query)) {
        $success = "Product added successfully!";
    } else {
        $error = "Error adding product: " . mysqli_error($conn);
    }
}

// Fetch all products
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
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
        <h2>Manage Products</h2>

        <!-- Success/Error Messages -->
        <?php if (isset($success)) echo "<p style='color: green;'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

        <!-- Add Product Form -->
        <div class="form-container">
            <h3>Add New Product</h3>
            <form method="POST">
                <input type="text" name="name" placeholder="Product Name" required>
                <input type="text" name="category" placeholder="Category" required>
                <input type="text" name="price" placeholder="Price" required>
                <input type="text" name="stock" placeholder="Stock Quantity" required>
                <input type="text" name="image" placeholder="Image Path (e.g., banana.jpg)" required>
                <textarea name="description" placeholder="Product Description" rows="3" required></textarea>
                <button type="submit" name="add_product">Add Product</button>
            </form>
        </div>

        <!-- Display Products -->
        <h3>Existing Products</h3>
        <table class="product-table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Image</th>
                <th>Description</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td>Ksh <?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td>
                        <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="product-img">
                    </td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                    <td>
                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?');">Delete</a>
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
