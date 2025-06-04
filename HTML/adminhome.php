<?php

session_start();
include 'database/databaseconnect.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$medicines_stm = "SELECT * from medicine_details order by medicine_name";
$medicines = $conn->query($medicines_stm);

?>



<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/table.css">

</head>

<body style="background-color: white;">
    <script src="nav.js"></script>

    <div class="table" style="max-height: 500px;">
        <table>
            <thead>
                <th>Medicine Name</th>
                <th>Medicine ID</th>
                <th>Medicine Type</th>
                <th>Quantity Available</th>
                <th>Unit Price</th>
            </thead>
            <tbody>
                <?php
                while ($medicine = $medicines->fetch_assoc()) {
                    echo "<tr>
                            <td>$medicine[medicine_name]</td>
                            <td>$medicine[medicine_id]</td>
                            <td>$medicine[medicine_type]</td>
                            <td>$medicine[quantity]</td>
                            <td>Rs.$medicine[price]</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>