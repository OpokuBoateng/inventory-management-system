<?php require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location:  index.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id <  1) {
    exit("Invalid product ID.");
}
$stmt = $conn->prepare("DELETE FROM  products WHERE id = ?");

$stmt->bind_param("i", $id);

if($stmt->execute()) {

    if ($stmt->affected_rows > 0)   {
        $stmt->close();

        header("Location: index.php");
        exit;
    }

    $stmt->close();
    exit("Product not found or already deleted");
}

error_log("Product deletion failed: " . $stmt->error);

$stmt->close();

exit("Unable to delete the product. Please try again.");
