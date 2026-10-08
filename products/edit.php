<?php

require_once "../config/database.php";

$id = $_GET["id"];

$sql = "SELECT * FROM products WHERE id = $id";

$result = $conn->query($sql);

$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $category_id = $_POST["category_id"];

    $sql = "UPDATE products SET name = '$name', price = '$price', quantity = '$quantity', category_id = '$category_id' WHERE id = $id";

    if ($conn->query($sql)) {
        echo "Product updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>

    <form action="" method="POST">
        <label for="Product Name:"></label>
        <input type="text" name="name" value="<?php echo $product["name"]; ?>" required>

        <br><br>

        <label for="">Price:</label>
        <input type="number" name="price" step="0.01" value="<?php echo $product["price"]; ?>" required>

        <br><br>

        <label for="">Quantity:</label>
        <input type="number" name="quantity" value="<?php echo $product["quantity"]; ?>" required>

        <br><br>

        <label for="">Category ID:</label>
        <input type="number" name="category_id" value="<?php echo $product["category_id"]; ?>" required>

        <br><br>

        <button type="submit">Update Product</button>

    </form>

</body>
</html>