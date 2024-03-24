<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start(); // Start the session at the very beginning

require 'db.php'; // Include the database connection

$error_message = ''; // Initialize a variable to hold error messages

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // Ensure we're trimming the password as well

    // Prepare a select statement to fetch the user details
    $sql = "SELECT id, email, password FROM users WHERE email = :email";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                if ($row = $stmt->fetch()) {
                    // Since you're not using hashed passwords, compare directly
                    if ($password === $row['password']) { // Direct comparison
                        // Session variables to indicate user is logged in
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['email'] = $row['email'];

                        // Redirect to home page on successful login
                        header("location: home.php");
                        exit;
                    } else {
                        // Password does not match
                        $error_message = "The password you entered was not valid.";
                    }
                }
            } else {
                // No user found with that email address
                $error_message = "No account found with that email.";
            }
        } else {
            // Database query failed
            $error_message = "Oops! Something went wrong. Please try again later.";
        }
        // Clean up the statement
        unset($stmt);
    }
    // Close the database connection
    unset($pdo);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Inventory Management System</title>
  <style>
     body {
      background-color: rgba(203, 204, 205, 255);
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      /* align-items: center; */
      justify-content: flex-start; /* Changed to flex-start to avoid centering the header */
      height: 100vh;
    }

    .header {
    background-color: #2480fc;
    color: white;
    padding: 10px 0;
    text-align: left;
    padding-left: 80px;
    font-size: 25px;
    position: relative; /* For absolute positioning of the dropdown */
    margin-bottom: 10%;
  }

    .centered-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100%; /* Take up remaining height */
      width: 100%; /* Align width */
    }

    .page-title {
      font-size: 32px;
      margin: 20px 0;
      font-weight: bold;
      margin-left: 29%;
      color: #ffffff;
      text-shadow: 2px 2px 4px rgb(0, 0, 0);
    }
    
    .login-container {
      background-color: #fff;
      padding: 20px;
      margin-left: 36%;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 400px;
      margin-bottom: 20px; /* Add some space below the login container */
    }
  
  .login-container h2 {
    text-align: center; /* Center the title */
    margin-bottom: 20px; /* Space below the title */
  }
  
  .form-group {
    margin-bottom: 15px; /* Space below each form group */
  }
  
  .form-group label {
    display: block; /* Ensures the label is on its own line */
    margin-bottom: 5px; /* Space between label and input */
  }
  
  .form-group input[type="email"],
  .form-group input[type="password"] {
    width: 95%; /* Full width of the form group */
    padding: 10px; /* Padding inside the input */
    border: 1px solid #ccc; /* Border around the input */
    border-radius: 5px; /* Slightly rounded corners for the input */
  }
  
  .login-button {
    background-color: rgba(13, 110, 253, 255); /* Background color for the button */
    color: white; /* Text color for the button */
    padding: 10px 15px;
    border: none; /* No border for the button */
    border-radius: 5px; /* Slightly rounded corners for the button */
    width: 100%; /* Full width of the form group */
    cursor: pointer; /* Cursor effect when hovering over the button */
  }
  
  .login-button:hover {
    background-color: rgba(11, 98, 227, 255); /* Slightly darker color on hover */
  }
</style>
</head>
<body>

<div class="header">
  Inventory Management System - CIU
</div>

<div class="page-title">
  Lab Inventory Management System - CIU
</div>

<div class="login-container">
  <h2>Login</h2>
  <form method="post" action="">
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Email address" required>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Password" required>
    </div>
    <button type="submit" class="login-button">Login</button>
  </form>
</div>


</body>
</html>
