<?php
    include 'database/databaseconnect.php';

    $patient_id = $_POST["patient_id"];
    $height = $_POST["height"];
    $weight = $_POST["weight"];
    $bmi = $_POST["bmi"];
    $sugar_level = $_POST["sugar_level"];
    $bp_level = $_POST["bp_level"];
    $reason_of_visit = $_POST["reason_of_visit"];

    $check = $conn->prepare("SELECT patient_id from patient_details where patient_id=?");
    $check->bind_param("i", $patient_id);
    $check->execute();

    $result = $check->get_result();
    if($result->num_rows == 1){

        $insert = $conn->prepare("INSERT into health_records (patient_id, date_of_visit,
                                time_of_visit, height, weight, bmi, sugar_level, bp_level,
                                reason_of_visit) values(?,?,?,?,?,?,?,?,?)");
        $insert->bind_param("sssdddiss",$patient_id, $date_of_visit, $time_of_visit, $height, $weight
                            , $bmi, $sugar_level, $bp_level, $reason_of_visit);
        if($insert->execute() === TRUE){
            echo "record inserted";
        }
        else{
            echo "error";
        }
    }
?>