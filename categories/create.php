<?php
require_once "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");

    if ($name === "") {
        $error = "Category name cannot be empty.";
    } elseif (strlen($name) > 100) {
        $error = "Category name cannot exceed 100 characters";
    } else {
        $stmt = $conn->prepare("SELECT  id FROM categories WHERE name = ?");

        $stmt->bind_param("s", $name);
        $stmt->execute();

        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;

        $stmt->close();

        if ($exists) {
            $error  = "This category already exists";
        } else {
            $stmt  = $conn->prepare("INSERT INTO categories (name) VALUES (?)");

            $stmt->bind_param("s", $name);

            if($stmt->execute()) {
                $stmt->close();

                header("location: index.php");
                exit;
            }

            error_log("Category insertion: " . $stmt->error);
            $stmt->close();

            $error = "Unable to add the category. Please try again.";
        }
    }


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
</head>
<body>
    <h1>Add Category</h1>

    <?php if ($error !== ""): ?>
        <p style="color:red;">
            <?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?>
        </p>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="name">Category Name:</label>

        <input type="text" name="name" id="name" maxlength="100"
            value="<?php echo htmlspecialchars($name ??  "", ENT_QUOTES, "UTF-8" ); ?>" required
        >

        <br><br>

        <button type="submit">Add Category</button>
    </form>

    <p><a href="index.php">Back to Categories</a></p>

</body>
</html>