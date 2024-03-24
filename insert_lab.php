<?php
// Include your database configuration file
require 'db.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assign variables from form data
    $labName = $_POST['lab_name'];
    $roomNumber = $_POST['room_number'];

    // Prepare an insert statement
    $sql = "INSERT INTO lab (lab_name, room_number) VALUES (:lab_name, :room_number)";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":lab_name", $labName, PDO::PARAM_STR);
        $stmt->bindParam(":room_number", $roomNumber, PDO::PARAM_STR);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Records created successfully. Redirect to landing page
            header("location: lab.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }

    // Close statement
    unset($stmt);
}

// Close connection
unset($pdo);
?>
