<?php
session_start();
include('../db.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];
    $description = $_POST['description'];

    $query = "UPDATE products SET name='$name', category='$category', price='$price', stock='$stock', image='$image', description='$description' WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: manage_products.php");
        exit();
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
    <h2>Edit Product</h2>
    <form method="POST">
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
        <input type="text" name="category" value="<?php echo $product['category']; ?>" required>
        <input type="text" name="price" value="<?php echo $product['price']; ?>" required>
        <input type="text" name="stock" value="<?php echo $product['stock']; ?>" required>
        <input type="text" name="image" value="<?php echo $product['image']; ?>" required>
        <textarea name="description" required><?php echo $product['description']; ?></textarea>
        <button type="submit" name="update_product">Update Product</button>
    </form>
</body>
</html>
