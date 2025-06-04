<?php 
date_default_timezone_set("Asia/Kolkata");
echo date("H:i");
echo date("Y/m/d");

$a = "hellow";
$b = $a . "world";

$dob = "<select>";
$i=0;
while($i < 3){

    $dob = $dob . "<option> HI</option>";
    $i = $i + 1;
}

$dob = $dob. "</select>";
echo $dob;
?>