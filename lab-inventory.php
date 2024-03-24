<?php
include 'header.php';
include 'secheader.php';
include 'security.php';
include 'db.php'; // Make sure to include your database connection file

// Fetch labs for dropdown
$labsStmt = $pdo->query('SELECT lab_name FROM lab');
$labs = $labsStmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize an array to hold the items
$items = [];

// Check if a lab name is selected and it is not the "Please Select" option
if (isset($_GET['lab_name']) && !empty($_GET['lab_name'])) {
    $selectedLabName = $_GET['lab_name'];
    $itemsStmt = $pdo->prepare('SELECT * FROM item WHERE lab_name = :lab_name');
    $itemsStmt->bindParam(':lab_name', $selectedLabName, PDO::PARAM_STR);
    $itemsStmt->execute();
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
} // If "Please Select" is chosen or no lab is selected, $items remains an empty array and no items are fetched

?>
<title>Lab Table</title>
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
<div class="table-title">
    INVENTORY
</div>

<!-- Trigger/Open The Modal -->
<button id="myBtn" class="create-button">Create New</button>

<!-- The Modal -->
<div id="createItemModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <h2>Add Item</h2>
    </div>
    <div class="modal-body">
      <form action="insert_item.php" method="post">
        <label for="labDropdown">Lab Name:</label>
        <select id="labDropdown" name="lab_name" required>
          <?php foreach ($labs as $lab): ?>
            <option value="<?= htmlspecialchars($lab['lab_name']); ?>"><?= htmlspecialchars($lab['lab_name']); ?></option>
          <?php endforeach; ?>
        </select>

        <label for="itemName">Item Name:</label>
        <input type="text" id="itemName" name="item_name" required>

        <label for="itemQuantity">Quantity:</label>
        <input type="number" id="itemQuantity" name="quantity" required>

        <input type="submit" value="Save">
      </form>
    </div>
  </div>
</div>


<!-- Edit Modal HTML -->
<div id="editItemModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close editClose">&times;</span>
      <h2>Edit Item List</h2>
    </div>
    <div class="modal-body">
      <form id="editItemForm" action="update_item.php" method="post">
        <label for="editItemName">Item:</label>
        <input type="text" id="editItemName" name="item_name" required>
        <label for="editQuantity">Quantity:</label>
        <input type="text" id="editQuantity" name="quantity" required>
        <input type="hidden" id="editItemId" name="item_id">
        <input type="submit" value="Update">
      </form>
    </div>
  </div>
</div>
<form action="lab-inventory.php" method="GET">
    <label for="labDropdown">Select Lab:</label>
    <select id="labDropdown" name="lab_name" onchange="this.form.submit()" required>
        <option value="">Please Select</option>
        <?php foreach ($labs as $lab): ?>
            <option value="<?= htmlspecialchars($lab['lab_name']); ?>" <?= (isset($_GET['lab_name']) && $_GET['lab_name'] === $lab['lab_name']) ? 'selected' : ''; ?>>
                <?= htmlspecialchars($lab['lab_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>



<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Item_id</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
<tr>
    <td><?php echo htmlspecialchars($item['item_id']); ?></td>
    <td><?php echo htmlspecialchars($item['item_name']); ?></td>
    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
    <td>
        <button type="button" class="edit-button" onclick="openEditModal('<?= htmlspecialchars($item['item_id']); ?>', '<?= htmlspecialchars(addslashes($item['item_name'])); ?>')">&#9998; Edit</button>
                    <form action="delete_item.php" method="post" style="display: inline;">
                        <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['item_id']); ?>">
                        <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this Item?');">&#10006; Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
   // When the user clicks the button, open the modal
    var btn = document.getElementById('myBtn'); // The ID of your 'Create New' button
    var createModal = document.getElementById('createItemModal'); // Corrected ID to match the modal's ID

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


    function openEditModal(itemId, itemName) {
    document.getElementById('editItemId').value = itemId;
    document.getElementById('editItemName').value = itemName;

    var editModal = document.getElementById('editItemModal');
    editModal.style.display = 'block';
    }


    // Close the modal when the user clicks on <span> (x)
    var span = document.getElementsByClassName("editClose")[0];
    span.onclick = function() {
        var modal = document.getElementById('editItemModal');
        modal.style.display = "none";
    }

    // Close the modal when the user clicks anywhere outside of it
    window.onclick = function(event) {
        var modal = document.getElementById('editItemModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

</script>