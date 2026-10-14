<?php
require_once "../config/database.php";

$sql = "SELECT products.id, products.name AS product, products.price, products.quantity, categories.name AS category FROM products
JOIN categories ON products.category_id = categories.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>
    <h1>Products</h1>
    <a href="create.php">Add New Product</a>

    <br><br>

    <table border="1" cellpadding="10" cellspacing="0" >
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($product = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $product["id"]; ?></td>
                        <td><?php echo htmlspecialchars($product["product"]); ?></td>
                        <td><?php echo number_format($product["price"], 2); ?></td>
                        <td><?php echo $product["quantity"]; ?></td>
                        <td><?php echo htmlspecialchars($product["category"]); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $product["id"]; ?>">Edit</a>
                            |
                            <a href="delete.php?id=<?php echo $product["id"]; ?>"
                             onclick="return confirm('Are you sure you want to delete this product?');">
                             Delete
                            </a>
                        </td>
                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>