<?php
require_once "../config/database.php";

// process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $quantity = trim($_POST["quantity"] ?? "");
    $category_id = trim($_POST["category_id"] ?? "");

    if ($name === "") {
        exit("Error: Product name cannot be empty.");
    }

    if (strlen($name) > 150) {
        exit("Error: Product name cannot be exceed 150 characters");
    }

    if (!is_numeric($price) || !is_finite( (float) $price )  || (float) $price < 0) {
        exit("Error: Please enter a valid, non-negative price.");
    }

    if (filter_var($quantity, FILTER_VALIDATE_INT) === false || (int) $quantity < 0) {
        exit("Error: Quantity must be a non-negative whole number.");
    }

    if (!ctype_digit($category_id) || (int) $category_id < 1) {
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

    $stmt = $conn->prepare(
        "INSERT INTO products (name, price, quantity, category_id) VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param("sdii", $name, $price, $quantity, $category_id);

    if ($stmt->execute()) {
        $stmt->close();

        header("Location: index.php");
        exit;
    }

    error_log("Product insertion failed: " . $stmt->error);

    $stmt->close();

    exit("Unable to add the product. Please try again");
}

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
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

        <label for="name">Product Name:</label>
        <input type="text" name="name"  id="name" maxlength="150" required>

        <br><br>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" min="0" step="0.01" required>

        <br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" min="0" step="1" name="quantity" required>

        <br><br>

        <label for="category_id">Category ID:</label>

        <select name="category_id" id="category_id" required>

            <option value="">Select a category</option>

            <?php while ($category = $categories->fetch_assoc()): ?>

                <option value="<?php echo (int) $category["id"]; ?>"><?php echo htmlspecialchars(
                    $category["name"], ENT_QUOTES, "UTF-8"); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <br><br>

        <button type="submit">Add Product</button>
    </form>

    <p><a href="index.php">Back to Products</a></p>
</body>
</html>