<?php
session_start();
require 'db.php'; // Ensure this path is correct

// Check if the form was submitted and the assigned_id is available
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assigned_id'])) {
    $assignedId = $_POST['assigned_id'];

    // Prepare a delete SQL statement
    $sql = "DELETE FROM assignment WHERE assigned_id = :assigned_id";

    try {
        $stmt = $pdo->prepare($sql);

        // Bind the assigned_id parameter
        $stmt->bindParam(':assigned_id', $assignedId, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            // If successful, redirect with a success message
            $_SESSION['success_message'] = "Assignment deleted successfully.";
            header("Location: lab-assignment.php");
            exit();
        } else {
            // If not successful, redirect with an error message
            $_SESSION['error_message'] = "Failed to delete the assignment.";
            header("Location: lab-assignment.php");
            exit();
        }
    } catch (PDOException $e) {
        // Handle potential errors, such as database connection issues
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header("Location: lab-assignment.php?error=1");
        exit();
    }
} else {
    // Redirect back if the form wasn't submitted correctly or the assigned_id is missing
    header("Location: lab-assignment.php?error=2");
    exit();
}
?>
