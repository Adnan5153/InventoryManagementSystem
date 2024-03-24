<?php 
include 'security.php'; 
include 'db.php'; // Ensure this is correctly pointing to your DB connection script

// Fetch labs and their total quantity of items
try {
    $labs = $pdo->query('SELECT l.lab_id, l.lab_name, l.room_number, SUM(i.quantity) AS total_quantity 
                         FROM lab l 
                         LEFT JOIN item i ON l.lab_name = i.lab_name 
                         GROUP BY l.lab_id, l.lab_name, l.room_number')->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Inventory</title>
    <?php include 'header.php'; ?>
    <?php include 'secheader.php'; ?>
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
    </style>
</head>
<body>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Lab_id</th>
                    <th colspan="3">Lab_name</th> <!-- This TH spans 3 columns -->
                    <th>Room_number</th>
                    <th>Total Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($labs as $lab): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($lab['lab_id']); ?></td>
                        <td colspan="3"><?php echo htmlspecialchars($lab['lab_name']); ?></td>
                        <td><?php echo htmlspecialchars($lab['room_number']); ?></td>
                        <td><?php echo htmlspecialchars($lab['total_quantity'] ?? '0'); ?></td> <!-- Display 0 if no items -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
