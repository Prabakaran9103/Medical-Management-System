<?php
session_start();
include 'database/databaseconnect.php';
$doctor_list = "SELECT * from doctor_details";
$doctors = $conn->query($doctor_list);




if(isset($_GET["schedule"])){
    if(empty($_GET["doctor_name"]) || empty($_GET["date_of_visit"]) || empty($_GET["time_of_visit"])){
        echo "<script>alert('Provide All details')</script>";
    }
    else{
        $doctor_name = preg_replace('/\s*\([^)]*\)$/', '', $_GET['doctor_name']);
        $doctor_id = "SELECT doctor_id from doctor_details where doctor_name = '$doctor_name'";
        $doctor_id = $conn->query($doctor_id)->fetch_array()['doctor_id'];

        $check = "SELECT date_of_visit, time_of_visit from appointment where date_of_visit = '$_GET[date_of_visit]'
                    and time_of_visit='$_GET[time_of_visit]' and doctor_id = '$doctor_id'";
        $check = $conn->query($check);

        if($check->num_rows == 0 ){
            $insert_stm = "INSERT into appointment values ('$_SESSION[patient_id]','$doctor_id','$_GET[date_of_visit]','$_GET[time_of_visit]')";
            if($conn->query($insert_stm)){
                echo "<script>alert('Appointment Fixed')</script>";
            }
            else{
                echo "<script>alert('Error')/script>";
            }
        }
        else{
            $check = "SELECT date_of_visit, time_of_visit from appointment where date_of_visit = '$_GET[date_of_visit]'
                    and time_of_visit='$_GET[time_of_visit]' and  patient_id  != '$_SESSION[patient_id]'";
            if($conn->query($check)->num_rows > 0){
                echo "<script>alert('Some one has already booked in this time')</script>";
            }

        }
    }
}

$appointments = "SELECT ap.patient_id, dd.doctor_name, dd.doctor_type, ap.date_of_visit, ap.time_of_visit
                from appointment as ap
                inner join doctor_details as dd on ap.doctor_id = dd.doctor_id
                where ap.patient_id = '$_SESSION[patient_id]'
                order by ap.date_of_visit desc";
$appointments = $conn->query($appointments);

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/table.css">
    <link rel="stylesheet" href="styles/button.css">
    <link rel="stylesheet" href="styles/patientrecords.css">
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

    <form action="" method="get">
        <div class="records">
            <h1 style="color:darkblue; text-align:center;">Schedule Appointment</h1>
            <table>
                <tr>
                    <td><label>Select Doctor* :</label></td>
                    <td>
                            <?php
                            echo "<select name='doctor_name'>
                            <option>Select doctor</option>";
                            while ($doctor = $doctors->fetch_assoc()) {
                                echo "
                        <option name='$doctor[doctor_name]'>$doctor[doctor_name] ($doctor[doctor_type])</option>
                        ";}
                        echo "</select>"
                        // <input type='hidden' value='$doctor[doctor_id]' name='$doctor[doctor_id]'>
                            ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <lable>Enter date of visit* : </lable>
                    </td>
                    <td><input type="date" name="date_of_visit" min="<?php echo date('Y-m-d'); ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <lable>Enter Time of visit* : </lable>
                    </td>
                    <td><input type="time" name="time_of_visit" min="06:00" max="21:00">
                    </td>
                </tr>
                <tr>
                    <td><input type="submit" name="schedule" value="Schedule">
                    </td>
                </tr>
            </table>
        </div>
    </form>
    <br>
    <div class="table" style="min-height: 300px;">
        <table>
            <thead>
                <tr>
                    <th>Doctor Name</th>
                    <th>Specialist</th>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($appointment = $appointments->fetch_assoc()){
                        echo "
                        <tr>
                            <td>$appointment[doctor_name]</td>
                            <td>$appointment[doctor_type]</td>
                            <td>$appointment[date_of_visit]</td>
                            <td>$appointment[time_of_visit]</td>
                        </tr>
                        ";
                    }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>