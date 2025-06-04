<?php
    include 'database/databaseconnect.php';

    $patient_id = 1;

    $getRecords = $conn->prepare("SELECT * from health_records where patient_id=?");
    $getRecords->bind_param("i", $patient_id);
    $getRecords->execute();

    $result = $getRecords->get_result();

    if($result)

?>