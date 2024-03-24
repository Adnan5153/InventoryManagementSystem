<?php
session_start();
require 'db.php'; // Ensure this path correctly points to your database connection script

// Check if item_id is set from the form submission
if (isset($_POST['item_id']) && !empty($_POST['item_id'])) {
    $item_id = $_POST['item_id'];

    // Prepare SQL statement to delete item
    $sql = "DELETE FROM item WHERE item_id = :item_id";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Deletion successful
            $_SESSION['success_message'] = 'Item deleted successfully.';
        } else {
            // Deletion failed
            $_SESSION['error_message'] = 'Failed to delete item.';
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    }
} else {
    // item_id not set
    $_SESSION['error_message'] = 'Item ID not provided.';
}

// Redirect back to the lab-inventory page
header("Location: lab-inventory.php");
exit();
?>
