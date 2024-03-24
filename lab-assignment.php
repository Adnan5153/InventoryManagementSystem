<?php
include 'header.php';
include 'secheader.php';
include 'security.php';
include 'db.php'; // Make sure to include your database connection file

// Fetch lab assignments from the database
try {
    $assignments = $pdo->query('SELECT * FROM assignment')->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Could not connect to the database :" . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .table-container {
            margin-top: 20px;
            width: 100%;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #a6b4f4;
            color: rgb(0, 0, 0);
        }
        tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        tbody tr:hover {
            background-color: #ddd;
        }
        .create-button {
            background-color: #8A95F8;
            color: white;
            padding: 7px 13px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right; /* Align the button to the right */
            margin: 20px 0;
        }
        .edit-button {
            background-color: #1B3DEE;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-button {
            background-color: #FB0606;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .table-title {
            font-size: 24px; /* Increased font size */
            margin-left: 20px;
            margin-top: 15px;
            font-weight: bold;
            display: inline-block; /* Makes the div inline so it can align with the button */
            line-height: 50px; /* Aligns text vertically */
        }
        .divider {
            border-bottom: 2px solid #ddd; /* Divider line */
            clear: both; /* Clear the floating of the create button */
        }
                /* The Modal (background) */
        .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 30%; /* Could be more or less, depending on screen size */
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        animation-name: animatetop;
        animation-duration: 0.4s
        }

        /* Add Animation */
        @keyframes animatetop {
        from {top: -300px; opacity: 0}
        to {top: 0; opacity: 1}
        }

        /* The Close Button */
        .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        }

        .close:hover,
        .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
        }

        /* Modal Header */
        .modal-header {
        padding: 2px 16px;
        background-color: #5cb85c;
        color: white;
        }

        /* Modal Body */
        .modal-body {padding: 2px 16px;}

        /* Input fields styling */
        input[type=text], input[type=number] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        }

        /* Save button styling */
        input[type=submit] {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        }

        input[type=submit]:hover {
        background-color: #45a049;
        }


    </style>
</head>
<body>

<div class="table-title">
    LAB ASSIGNMENT
</div>
<!-- Trigger/Open The Modal -->
<button id="myBtn" class="create-button">Create New</button>

<!-- The Modal -->
<div id="createAssignmentModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <h2>Assign Lab</h2>
    </div>
    <div class="modal-body">
      <form action="add_assistant.php" method="post">
        <label for="assistantName">Assistant Name</label>
        <input type="text" id="assistantName" name="emp_name" required>

        <label for="labName">Lab Name</label>
        <input type="text" id="labName" name="lab_name" required>

        <input type="submit" value="Save">
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal HTML -->
<div id="editAssignmentModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close editClose">&times;</span>
      <h2>Edit Lab Assignment</h2>
    </div>
    <div class="modal-body">
      <form id="editAssignmentForm" action="update_assignment.php" method="post">
        <label for="editLabName">Lab Name:</label>
        <input type="text" id="editLabName" name="lab_name" required>
        <label for="editAssistantName">Assistant Name:</label>
        <input type="text" id="editAssistantName" name="emp_name" required>
        <input type="hidden" id="editAssignedId" name="assigned_id">
        <input type="submit" value="Update">
      </form>
    </div>
  </div>
</div>


<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Assigned_id</th>
                <th>Lab_name</th>
                <th>Emp_name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assignments as $assignment): ?>
            <tr>
                <td><?php echo htmlspecialchars($assignment['assigned_id']); ?></td>
                <td><?php echo htmlspecialchars($assignment['lab_name']); ?></td>
                <td><?php echo htmlspecialchars($assignment['emp_name']); ?></td>
                <td>
                    <button type="button" class="edit-button" 
                            onclick="openEditModal(
                                '<?php echo htmlspecialchars($assignment['assigned_id']); ?>',
                                '<?php echo htmlspecialchars(addslashes($assignment['lab_name'])); ?>',
                                '<?php echo htmlspecialchars($assignment['emp_name']); ?>'
                            )">&#9998; Edit</button>
                    <form action="delete_assignment.php" method="post" style="display: inline;">
                        <input type="hidden" name="assigned_id" value="<?php echo htmlspecialchars($assignment['assigned_id']); ?>">
                        <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this assignment?');">&#10006; Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    // When the user clicks the button, open the modal
    var btn = document.getElementById('myBtn');
    var createModal = document.getElementById('createAssignmentModal');

    btn.onclick = function() {
        createModal.style.display = 'block';
    }

    // When the user clicks on <span> (x), close the modal
    var span = document.getElementsByClassName('close')[0];
    span.onclick = function() {
        createModal.style.display = 'none';
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == createModal) {
            createModal.style.display = 'none';
        }
    }

    function openEditModal(assignedId, labName, empName) {
        document.getElementById('editAssignedId').value = assignedId;
        document.getElementById('editLabName').value = labName;
        document.getElementById('editAssistantName').value = empName;

        var editModal = document.getElementById('editAssignmentModal');
        editModal.style.display = 'block';
    }


    // Close the modal when the user clicks on <span> (x)
    var span = document.getElementsByClassName("editClose")[0];
    span.onclick = function() {
        var modal = document.getElementById('editAssignmentModal');
        modal.style.display = "none";
    }

    // Close the modal when the user clicks anywhere outside of it
    window.onclick = function(event) {
        var editModal = document.getElementById('editAssignmentModal');
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    }
</script>


</body>
</html>
