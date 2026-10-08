<?php
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $category_id = $_POST["category_id"];

    $sql = "INSERT INTO products (name, price, quantity, category_id) VALUES ('$name', '$price', '$quantity', '$category_id')";
    if ($conn->query($sql)) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$categories = $conn->query("SELECT id, name FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
    <h1>Add Product</h1>

    <form action="" method="POST">
        <label for="">Product Name:</label>
        <input type="text" name="name" required>

        <br><br>

        <label for="">Price:</label>
        <input type="number" name="price" step="0.01" required>

        <br><br>

        <label for="">Quantity:</label>
        <input type="number" name="quantity" required>

        <br><br>

        <label for="">Category ID:</label>
        <select name="category_id" id="" required>
            <option value="">Select a category</option>

            <?php while ($category = $categories->fetch_assoc()): ?>

                <option value="<?php echo $category["id"]; ?>"><?php echo $category["name"]; ?></option>
            <?php endwhile; ?>
        </select>

        <br><br>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>