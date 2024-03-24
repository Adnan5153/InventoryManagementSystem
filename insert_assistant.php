<?php
// Include your database configuration file
require 'db.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assign variables from form data
    $assistantName = $_POST['emp_name'];

    // Prepare an insert statement
    $sql = "INSERT INTO employee (emp_name) VALUES (:emp_name)";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":emp_name", $assistantName, PDO::PARAM_STR);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Records created successfully. Redirect to landing page
            header("location: lab-assistant.php");
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
