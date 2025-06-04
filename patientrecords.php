<?php
session_start();
include 'database/databaseconnect.php';
date_default_timezone_set("Asia/Kolkata");

// if (!isset($_SESSION['admin_id'])) {
//     header("Location: index.php");
//     exit();
// }
$_SESSION['admin_id'] = "admin";
$selected_value = "";
$patients_stm = "SELECT patient_name from patient_details order by patient_name";
$patients = $conn->query($patients_stm);
$contacts_stm = "SELECT contact_no from patient_details";
$contacts = $conn->query($contacts_stm);
if(isset($_GET['patient_name'])){
    $selected_value = $_GET['patient_name'];
    $contacts_stm = "SELECT contact_no from patient_details where patient_name = '$_GET[patient_name]'";
    $contacts = $conn->query($contacts_stm);
}



if (isset($_GET['enter'])) {
    if (
        empty($_GET['patient_name']) || empty($_GET['height'])  || empty($_GET['weight'])
        || empty($_GET['bmi']) || empty($_GET['reason_of_visit']) || empty($_GET['contact_no'])
    ) {
        $message = "Fill all required details";
        echo "<script>alert('Fill all required details')</script>";
    } else {
        $sugar_level = 0;
        $bp_level = 0;
        if(!empty($_GET['sugar_level'])){
            $sugar_level = intval($_GET['sugar_level']);
        }
        if(!empty($_GET['bp_level'])){
            $bp_level = intval($_GET['bp_level']);
        }
        $getId_stm = "SELECT patient_id from patient_details
                    where patient_name = '$_GET[patient_name]' and contact_no = '$_GET[contact_no]'";
        $patient_id = $conn->query($getId_stm);
        if ($patient_id->num_rows > 0) {
            $patient_id = $patient_id->fetch_array()['patient_id'];
            $update = "INSERT into health_records (patient_id, height, weight, bmi, sugar_level, bp_level,
            reason_of_visit, date_of_visit, time_of_visit) values ('$patient_id', '$_GET[height]',
            '$_GET[weight]', '$_GET[bmi]', '$sugar_level', '$bp_level', '$_GET[reason_of_visit]',
            CURDATE(), CURTIME())";
            if ($conn->query($update)) {
                echo "<script>alert('Record updated')</script>";
            } else {
                echo "<script>alert('error while updating database')</script>";
            }
        }
    }
}


?>

<html>

<head>
    <title>Patient Records</title>
    <link rel="stylesheet" href="styles/patientrecords.css">
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/button.css">
    <script src="script/patientrecords.js" defer></script>

</head>

<body>
    <script src="nav.js"></script>
    <form action="" method="get">
        <div class="records">
            <h1 style="color: darkblue; text-align:center;">Patient Records</h1>
            <table>
            <tr>
            <td>
                        <lable>Patinet Name* </lable>
                    </td>
                    <td>: <select onchange="this.form.submit()" name="patient_name">
                            <option selected disabled>Select Patient Name</option>
                            <?php while ($patient = $patients->fetch_assoc()) {
                                $isSelected = ($patient['patient_name'] == $selected_value) ? "selected" : "";
                                echo "<option value='$patient[patient_name]' $isSelected>$patient[patient_name]</option>";
                            }
                            ?>
                        </select>
                        <select id="contact_no" name="contact_no">
                            <option disabled selected>Select Contact No.</option>
                            <?php while ($contact = $contacts->fetch_assoc()) {
                                echo "<option value='$contact[contact_no]'>$contact[contact_no]</option>";
                            } ?>
                        </select>
                    </td>
            </tr>
                <tr>
                    <td>
                        <lable>Height (in cm) *</lable>
                    </td>
                    <td>: <input type="text" id="height" name="height" placeholder="Enter Height"
                            oninput="heightValidate()">
                        <label class="error" id="heighterror">*Invalid input</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <lable>Weight (in kg) *</lable>
                    </td>
                    <td>: <input type="text" id="weight" name="weight" placeholder="Enter  Weight"
                            oninput="weightValidate()">
                        <label class="error" id="weighterror">*Invalid input</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>BMI *</label>
                    </td>
                    <td>: <input type="text" id="bmi" name="bmi"> <input type="button" onclick="bmiValidate()" value="calculate"></td>
                </tr>
                <tr>
                    <td>
                        <lable>Sugar Level (in mol/g)</lable>
                    </td>
                    <td>: <input type="number" id="sugar_level" name="sugar_level" placeholder="Enter Sugar Level"
                            oninput="sugarLevelValidate()">
                        <label class="error" id="sugarlevelerror">*Invalid input</label>
                    </td>
                </tr>
                <tr>
                    <td><label>Blood Pressure (in mmHg)</label></td>
                    <td>: <input type="text" id="bp_level" name="bp_level" placeholder="Enter Blood Pressure"></td>
                </tr>
                <tr>
                    <td>
                        <lable>Reason for Visit *</lable>
                    </td>
                    <td><textarea id="reason_of_visit" name="reason_of_visit"
                            placeholder="Enter reason for visit"></textarea></td>
                </tr>
                <tr>
                    <td><input type="submit" name="enter" value="Enter"></td>
                    <td><input type="reset" name="clear" value="Clear All"></td>
                </tr>
            </table>
        </div>
    </form>
</body>

</html>