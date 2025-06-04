<?php
include 'database/databaseconnect.php';
session_start();
if (!isset($_SESSION['patient_id'])) {
    header("Location: index.php");
    exit;
}
$patient_id = $_SESSION['patient_id'];
$profile_stm = "SELECT patient_id, patient_name, contact_no, email_id from patient_details
                where patient_id = '$patient_id'";
$profile = $conn->query($profile_stm);
$profile = $profile->fetch_assoc();

$records_stm = "SELECT * from health_records where patient_id = $patient_id";
$records = $conn->query($records_stm); 
?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/patienthome.css">
    <link rel="stylesheet" href="styles/table.css">
</head>

<body>
    <nav>
        <h1>Medical Management System</h1>
        <div class="nav">
        <a href="patienthome.php">Home</a>
        <a href="scheduleappointment.php">Sechedule Appointment</a>
        <a href="logout.php">Log out</a>
        </div>
    </nav>
    <h1>Profile</h1>
    <div class="profile">
        <?php echo "<p>Patient ID : $profile[patient_id]</p>";?>
        <?php echo "<p>Patient Name : $profile[patient_name]<p/>"; ?>
        <?php echo "<p>Contact No. :$profile[contact_no]</p>"?>
        <?php echo "<p>E-mail ID : $profile[email_id]</p>"?>
    </div>

    <h1>Health Records</h1>
    <div class="table" style="min-height: 300px; max-height: 300px;">
        <table>
            <thead>
                <tr>
                    <th>Date of Visit</th>
                    <th>Time of Visit</th>
                    <th>Height (in cm)</th>
                    <th>Weight (in kg)</th>
                    <th>BMI</th>
                    <th>Sugar Level (g/mol)</th>
                    <th>Blood Pressure Level (mmHg)</th>
                    <th>Reason of Visit</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    while($record = $records->fetch_assoc()){
                        echo "<tr> 
                            <td> $record[date_of_visit] </td>
                            <td> $record[time_of_visit]</td>
                            <td> $record[height]</td>
                            <td> $record[weight]</td>
                            <td> $record[bmi]</td>
                            <td> $record[sugar_level]</td>
                            <td> $record[bp_level]</td>
                            <td> $record[reason_of_visit]</td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>