<?php
    session_start();
    include 'database/databaseconnect.php';
    $appointments_stm = "SELECT pd.patient_name, dd.doctor_name, ap.date_of_visit, ap.time_of_visit
                    from
                    appointment as ap
                    inner join patient_details as pd on pd.patient_id = ap.patient_id
                    inner join doctor_details as dd on dd.doctor_id = ap.doctor_id;";
    $appointments = $conn->query($appointments_stm);

    if(isset($_GET['fetch'])){
        if(empty($_GET['from_date']) || empty($_GET['to_date'])){
            echo "<script>alert('Select both From and To dates')</script>";
        }
        else{
            if($_GET['from_date'] > $_GET['to_date']){
                echo "<script>alert('Invalid Dates')</script>";
            }
            else{
                $appointments_stm = "SELECT pd.patient_name, dd.doctor_name, ap.date_of_visit, ap.time_of_visit
                from
                appointment as ap
                inner join patient_details as pd on pd.patient_id = ap.patient_id
                inner join doctor_details as dd on dd.doctor_id = ap.doctor_id
                where ap.date_of_visit between '$_GET[from_date]' and '$_GET[to_date]'";
$appointments = $conn->query($appointments_stm);
            }
        }
    }
?>



<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/button.css">
    <link rel="stylesheet" href="styles/table.css">
</head>

<body>
    <script src="nav.js"></script>

    <div class="table" style="min-height: 500px;">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get">
        <div style="padding: 30px;">
            From date* : <input name="from_date" type="date" style="margin-right: 50px;">
            To date* : <input name="to_date" type="date" style="margin-right: 50px;">
            <!-- <input type="submit" name="fetch" value="Fetch"> -->
            <button type="submit" name="fetch">Fetch</button>
        </div>
        </form>
        <br>
        <table>
            <thead>
                <tr>
                    <th>
                        Patient Name
                    </th>
                    <th>
                        Doctor Name
                    </th>
                    <th>
                        Date of Visit
                    </th>
                    <th>
                        Time of Visit
                    </th>
                </tr>
            </thead>

            <tbody>
                <?php
                while ($appointment = $appointments->fetch_assoc()) {
                    echo "
                        <tr>
                            <td>$appointment[patient_name]</td>
                            <td>$appointment[doctor_name]</td>
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