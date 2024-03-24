<?php
session_start();
require 'db.php'; // Ensure this path is correct
require 'security.php'; // Include security checks

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['lab_id'])) {
    // Assign variables from form data
    $lab_id = $_POST['lab_id'];
    $lab_name = $_POST['lab_name'];
    $room_number = $_POST['room_number'];

    // Validate input
    if (empty($lab_name) || empty($room_number)) {
        // Set error message and redirect back to the edit form
        $_SESSION['error_message'] = "Lab name and room number cannot be empty.";
        header("Location: edit_lab.php?lab_id=" . $lab_id);
        exit();
    }

    // Prepare an update statement
    $sql = "UPDATE lab SET lab_name = :lab_name, room_number = :room_number WHERE lab_id = :lab_id";

    try {
        $stmt = $pdo->prepare($sql);

        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":lab_name", $lab_name, PDO::PARAM_STR);
        $stmt->bindParam(":room_number", $room_number, PDO::PARAM_STR);
        $stmt->bindParam(":lab_id", $lab_id, PDO::PARAM_INT);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Records updated successfully. Redirect to landing page
            $_SESSION['success_message'] = "Lab updated successfully.";
            header("location: lab.php");
            exit();
        } else {
            $_SESSION['error_message'] = "An error occurred. Please try again.";
            header("Location: edit_lab.php?lab_id=" . $lab_id);
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header("Location: edit_lab.php?lab_id=" . $lab_id);
        exit();
    }
} else {
    // If no form submission, redirect to the lab list
    header("location: lab.php");
    exit();
}
?>
