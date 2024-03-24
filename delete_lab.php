<?php
session_start();
require 'db.php'; // Ensure this path is correct for your database connection

// Check if the form was submitted and the lab_id is present
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['lab_id'])) {
    $lab_id = $_POST['lab_id'];

    // Prepare the delete statement
    $sql = "DELETE FROM lab WHERE lab_id = :lab_id";

    try {
        $stmt = $pdo->prepare($sql);
        // Bind the lab_id parameter
        $stmt->bindParam(':lab_id', $lab_id, PDO::PARAM_INT);

        // Attempt to execute
        if ($stmt->execute()) {
            // If success, redirect to lab list with a success message
            $_SESSION['success_message'] = "Lab successfully deleted.";
        } else {
            // If an error occurred, redirect back with an error message
            $_SESSION['error_message'] = "Failed to delete the lab.";
        }
    } catch (PDOException $e) {
        // Catch any PDO errors and set an error message
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    }

    // Redirect to the lab listing page
    header("Location: lab.php");
    exit();
} else {
    // If the necessary data wasn't submitted, redirect to the lab list
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: lab.php");
    exit();
}
?>
