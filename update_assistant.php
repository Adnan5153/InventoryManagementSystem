<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['emp_id']) && isset($_POST['emp_name'])) {
    $emp_id = $_POST['emp_id'];
    $emp_name = $_POST['emp_name'];

    if (!empty($emp_name) && !empty($emp_id)) {
        $sql = "UPDATE employee SET emp_name = :emp_name WHERE emp_id = :emp_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':emp_name', $emp_name, PDO::PARAM_STR);
        $stmt->bindParam(':emp_id', $emp_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Assistant updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update assistant.";
        }
    } else {
        $_SESSION['error_message'] = "All fields are required.";
    }
    header("Location: lab-assistant.php");
    exit();
}
