<?php 
    include 'databaseconnect.php';

    $update = "update medicine_details set medicine_type='Inhaler' where medicine_id = '4'";
    $conn->query($update);

?>