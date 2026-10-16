<?php

require_once "../config/database.php";

// Get and validate the product ID from the URL
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Invalid product ID");
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$product = $result->fetch_assoc();

$stmt->close();

if (!$product) {
    exit("Product not found");
}

$categories = $conn->query( "SELECT id, name FROM categories ORDER BY name" );

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $price = $_POST["price"] ?? "";
    $quantity = $_POST["quantity"] ?? "";
    $category_id =trim($_POST["category_id"] ?? "");
    // var_dump($category_id);
    // exit;

    if ($name === "") {
        exit("Error: Product name cannot be empty.");
    }

    if (!is_numeric($price) || (float) $price < 0 || !is_finite( (float) $price) ) {
        exit("Error: Please enter a valid, non-negative price.");
    }

    if (
        filter_var($quantity, FILTER_VALIDATE_INT) === false || (int) $quantity < 0
    ) {
        exit("Error: Quantity must be a non-negative whole number.");
    }

    if (
        !ctype_digit($category_id) || (int)$category_id < 1
    ) {
        exit("Error: Please select a valid category.");
    }

   $category_check = $conn->prepare("SELECT id FROM categories WHERE id = ?");

   $category_check->bind_param("i", $category_id);

   $category_check->execute();
   $category_result = $category_check->get_result();
   $category_exists = $category_result->num_rows > 0;
   $category_check->close();

   if (!$category_exists) {
    exit("Error: The selected category does not exist.");
   }

    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, quantity = ?, category_id = ? WHERE id = ?");

    $stmt ->bind_param(
        "sdiii", $name, $price, $quantity, $category_id, $id
    );

    if ($stmt->execute()) {
        $stmt ->close();

        header("Location: index.php");
        exit;
    }

    error_log("Products update failed " . $stmt->error);
     $stmt->close();

    exit("Unable to update the product. Please try again.");
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
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($product["name"], ENT_QUOTES, "UTF-8"); ?>"
        maxlength="150" required>

        <br><br>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" min="0" step="0.01" value="<?php echo $product["price"]; ?>" required>

        <br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="0" step="1"
        value="<?php echo htmlspecialchars((string) $product["quantity"], ENT_QUOTES, "UTF-8"); ?>" required>

        <br><br>

        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <?php while ($category = $categories->fetch_assoc()): ?>
                <option
                    value="<?php echo $category["id"]; ?>"
                    <?php if ( (int) $category["id"] === (int) $product["category_id"]) {
                        echo "Selected";
                    }
                    ?>
                >
                    <?php echo htmlspecialchars($category["name"], ENT_QUOTES, "UTF-8"); ?>
                </option>
            <?php endwhile; ?>
        </select>


        <br><br>

        <button type="submit">Update Product</button>

    </form>
    <p><a href="index.php">Back to Products</a></p>

</body>
</html>