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

// Fetch existing category details
$query = "SELECT * FROM categories WHERE id = $category_id";
$result = mysqli_query($conn, $query);
$category = mysqli_fetch_assoc($result);

if (!$category) {
    die("Category not found.");
}

// Handle category update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

    $update_query = "UPDATE categories SET name = '$category_name' WHERE id = $category_id";
    if (mysqli_query($conn, $update_query)) {
        header("Location: manage_categories.php?success=Category updated successfully");
        exit();
    } else {
        $error = "Error updating category: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit Category</h2>

        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

        <form method="POST">
            <label>Category Name:</label>
            <input type="text" name="category_name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
            <button type="submit">Update Category</button>
        </form>

        <a href="manage_categories.php">Back to Categories</a>
    </div>
</body>
</html>
