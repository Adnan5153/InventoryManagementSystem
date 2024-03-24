<?php
session_start();
require 'db.php'; // Ensure this path correctly points to your database connection script.

// Check if the form was submitted.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assigned_id'])) {
    // Capture input from the form.
    $assignedId = $_POST['assigned_id'];
    $empName = $_POST['emp_name'];
    $labName = $_POST['lab_name'];

    // Prepare the SQL statement to update the assignment.
    $sql = "UPDATE assignment SET lab_name = :lab_name, emp_name = :emp_name WHERE assigned_id = :assigned_id";

    try {
        $stmt = $pdo->prepare($sql);

        // Bind the parameters to the statement.
        $stmt->bindParam(':lab_name', $labName, PDO::PARAM_STR);
        $stmt->bindParam(':emp_name', $empName, PDO::PARAM_STR);
        $stmt->bindParam(':assigned_id', $assignedId, PDO::PARAM_INT);

        // Execute the statement.
        $stmt->execute();

        // Redirect back to the lab assignment page with a success message.
        $_SESSION['success_message'] = "Lab assignment updated successfully.";
        header("Location: lab-assignment.php"); // Ensure this location is correct.
        exit();
    } catch (PDOException $e) {
        // Handle potential errors, such as issues with the database connection.
        $_SESSION['error_message'] = "Database error during the update: " . $e->getMessage();
        header("Location: lab-assignment.php?error=1"); // Ensure this location is correct.
        exit();
    }
} else {
    // Redirect back if the form wasn't submitted correctly.
    header("Location: lab-assignment.php?error=2"); // Ensure this location is correct.
    exit();
}
?>
