<?php 
    include 'databaseconnect.php';
    $name = $_POST["name"];
    $password = $_POST["password"];

    $sql = "INSERT into test (name, password)values($name, $password)";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }

    echo"<br>done"
?>