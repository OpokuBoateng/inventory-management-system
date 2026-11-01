<?php

require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Invalid request method.");
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1)  {
    exit("Invalid category ID.");
}

try {
    $stmt = $conn->prepare("DELETE FROM categories WHERE id =?");

    $stmt->bind_param("i", $id);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $stmt->close();

        header("Location: index.php");
        exit;
    }

    $stmt->close();

    exit("Category not found.");
} catch (mysqli_sql_exception $e) {
    error_log("Category deletion failed: "  . $e->getMessage());

    if ((int) $e->getCode() === 1451) {
        $error = "This category cannot be deleted because it is assigned to one or more products";
    } else {
        $error = "Unable to delete the category. Please try again.";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Deletion</title>
</head>
<body>
   <h1>Category Deletion</h1>

   <p style="color : red"> <?php echo
     htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?>
   </p>

   <p><a href="index.php">Back to Categories</a></p>
</body>
</html>



