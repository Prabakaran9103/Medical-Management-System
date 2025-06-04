<?php
    include 'database/databaseconnect.php';

    $medicine_name = $_POST["medicine_name"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $medicine_type = $_POST["medicine_type"];

    $check = $conn->prepare("SELECT medicine_id from medicine_details where medicine_name=?");
    $check->bind_param("s", $medicine_name);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows != 0) {
        echo "Medicine Alread Exist";
    }
    else {
        $add = $conn->prepare("INSERT into medicine_details (medicine_name, price, quantity, medicine_type)
                                values (?,?,?,?)");
        $add->bind_param("siis", $medicine_name, $price, $quantity, $medicine_type);
        if ($add->execute() === TRUE) {
            echo "New Medicine Added";
        } 
        else {
            echo "Error";
        }
    }
?>