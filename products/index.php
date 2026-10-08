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
<?php
if($result->num_rows > 0) {
    while($product = $result->fetch_assoc()) {
        echo "Product: " . $product["product"] . "<br>";
        echo "Price: " . $product["price"] . "<br>";
        echo "Quantity: " . $product["quantity"] . "<br>";
        echo "Category: " . $product["category"] . "<br>";
        echo "<hr>";
    }

} else {
    echo "No products found";
}
?>
</body>
</html>