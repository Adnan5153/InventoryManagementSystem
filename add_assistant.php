<?php
session_start();
require 'db.php'; // Make sure this path is correct

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the input from the form
    $empName = $_POST['emp_name'];
    $labName = $_POST['lab_name'];
    

    // Prepare the SQL statement to insert the new assignment
    $sql = "INSERT INTO assignment (lab_name, emp_name) VALUES (:lab_name, :emp_name)";

    try {
        $stmt = $pdo->prepare($sql);

        // Bind the parameters to the statement
        $stmt->bindParam(':lab_name', $labName, PDO::PARAM_STR);
        $stmt->bindParam(':emp_name', $empName, PDO::PARAM_STR);
        

        // Execute the statement
        $stmt->execute();

        // Redirect back to the lab assignment page with a success message
        $_SESSION['success_message'] = "Lab assignment added successfully.";
        header("Location: lab-assignment.php");
        exit();
    } catch (PDOException $e) {
        // Handle potential errors, such as a duplicate entry
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header("Location: lab-assignment.php?error=1");
        exit();
    }
} else {
    // Redirect back if the form wasn't submitted correctly
    header("Location: lab-assignment.php?error=2");
    exit();
}
?>
