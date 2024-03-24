<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['emp_id'])) {
    $emp_id = $_POST['emp_id'];

    $sql = "DELETE FROM employee WHERE emp_id = :emp_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':emp_id', $emp_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Assistant deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete assistant.";
    }
    header("Location: lab-assistant.php");
    exit();
}
