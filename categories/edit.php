<?php

require_once "../config/database.php";

$error = "";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Invalid category ID");
}

$stmt = $conn->prepare("SELECT id, name FROM categories WHERE id = ?");

$stmt->bind_param("i",  $id);
$stmt->execute();

$result = $stmt->get_result();
$category = $result->fetch_assoc();

$stmt->close();

if (!$category) {
    exit("Category not found");
}

$name = $category["name"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");

    if ($name === "") {
        $error = "Category name cannot  be empty";
    } elseif (strlen($name) > 100)  {
        $error =  "Category  name cannot exceed 100 characters.";
    } else {
        $stmt =  $conn->prepare(
            "SELECT id FROM categories WHERE name = ? AND id != ?"
        );

        $stmt->bind_param("si", $name, $id);
        $stmt->execute();

        $duplicate_result = $stmt->get_result();
        $duplicate_exists = $duplicate_result->num_rows > 0;

        $stmt->close();

        if ($duplicate_exists) {
            $error = "Another category already uses this name";
        } else {
            $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->bind_param("si",  $name, $id);

            if ($stmt->execute()) {
                $stmt->close();

                header("Location: index.php");
                exit;
            }

            error_log("Category update failed: " . $stmt->error);
            $stmt->close();

            $error = "Unable to update the category. Please try again.";
        }
    }
}
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>

    <h1>Edit Category</h1>

    <?php if ($error != ""): ?>
      <p style="color:red";>
          <?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8");  ?>
      </p>
    <?php endif; ?>

    <form action="edit.php?id=<?php echo (int) $id; ?>" method="POST">
        <label for="name">Category Name:</label>

        <input  type="text" id="name" name="name" maxlength="100" value="<?php echo
          htmlspecialchars($name, ENT_QUOTES, "UTF-8"); ?>" required>

        <br><br>
        <button type="submit">Update Category</button>
    </form>

    <p><a href="index.php">Back to Category</a></p>
 </head>
 <body>

 </body>
 </html>