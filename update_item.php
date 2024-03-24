<?php
session_start();
require 'db.php'; // Ensure this path correctly points to your database connection script

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_id'])) {
    // Retrieve form data
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];

    // Validation (optional)
    if (empty($item_name) || empty($quantity)) {
        $_SESSION['error_message'] = 'Item name and quantity are required.';
        header("Location: lab-inventory.php");
        exit();
    }

    // Update item in the database
    $sql = "UPDATE item SET item_name = :item_name, quantity = :quantity WHERE item_id = :item_id";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':item_name', $item_name, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Update successful
            $_SESSION['success_message'] = 'Item updated successfully.';
        } else {
            // Update failed
            $_SESSION['error_message'] = 'Failed to update item.';
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    }

    // Redirect back to the lab-inventory page
    header("Location: lab-inventory.php");
    exit();
} else {
    // Redirect to lab-inventory.php if the form wasn't submitted or item_id isn't set
    header("Location: lab-inventory.php");
    exit();
}
?>
