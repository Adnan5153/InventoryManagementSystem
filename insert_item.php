<?php
session_start();

// Include your database connection file here
require 'db.php'; // Adjust the path as necessary to point to where your db.php file is located

// Proceed with your form handling and database operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $labName = $_POST['lab_name'];
    $itemName = $_POST['item_name'];
    $quantity = $_POST['quantity'];

    // Prepare your SQL statement
    try {
        $sql = "INSERT INTO item (lab_name, item_name, quantity) VALUES (:lab_name, :item_name, :quantity)";
        $stmt = $pdo->prepare($sql);

        // Bind values
        $stmt->bindParam(':lab_name', $labName);
        $stmt->bindParam(':item_name', $itemName);
        $stmt->bindParam(':quantity', $quantity);

        // Execute and redirect or error handle
        if ($stmt->execute()) {
            // Success, redirect to inventory page with success message
            header("Location: lab-inventory.php?success=1");
            exit();
        } else {
            // Error, redirect to inventory page with error message
            header("Location: lab-inventory.php?error=1");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header("Location: lab-inventory.php?error=1");
        exit();
    }
}
?>
