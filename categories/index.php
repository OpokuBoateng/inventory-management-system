<?php

require_once "../config/database.php";

$sql = "SELECT id,  name  FROM  categories ORDER BY id ASC";
$result = $conn->query($sql);

 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
 </head>
 <body>
    <p><a href="create.php">Add New Category</a></p>

    <table  border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($category = $result->fetch_assoc()): ?>
                <tr>
                    <td> <?php echo (int) $category["id"]; ?> </td>

                    <td>
                        <?php
                        echo  htmlspecialchars($category["name"], ENT_QUOTES, "UTF-8");
                        ?>
                    </td>

                    <td>
                        <a href="edit.php?id=<?php echo (int) $category["id"]; ?>">Edit</a>
                    </td>
                </tr>

            <?php endwhile; ?>
        </tbody>

    </table>

    <p><a href="../index.php">Back to Dashboard</a></p>

 </body>
 </html>