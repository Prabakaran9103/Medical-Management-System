<?php
session_start();
include 'database/databaseconnect.php';
$patienterror = "";
$adminerror = "";

if (isset($_POST['patientlogin'])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    if (empty($username) || empty($password)) {
        $patienterror = "Please enter both username and password.";
    } else {
        $sql = "SELECT patient_id from patient_details where patient_name='$username' and password='$password'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $_SESSION['patient_id'] = $result->fetch_assoc()['patient_id'];
            header("Location:patienthome.php");
        } else {
            $patienterror = "*Invalid User Name or Password";
        }
    }
}

if (isset($_POST['adminlogin'])) {
    $adminID = $_POST["adminID"];
    $password = $_POST["password"];
    if (empty($adminID) || empty($password)) {
        $adminerror = "Please enter both Admin ID and Password.";
    } else {
        $sql = "SELECT admin_id from admin where admin_id='$adminID' and password='$password'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $_SESSION['admin_id'] = $result->fetch_assoc()['admin_id'];
            header("Location:adminhome.php");
        } else {
            $adminerror = "*Invalid AdminID or Password";
        }
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/button.css">
</head>
<body>
    <nav>
        <h1>Medical Management System</h1>
    </nav>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="patientsession">
                <h2>Patient Login</h1>
                    <label>User Name</label>
                    <input type="text" name="username" id="username" placeholder="Enter User Name">
                    <label>Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter Password">
                    <input type="submit" name="patientlogin" value="login">
                    <?php echo "<label class='error'>$patienterror</label>" ?>
            </div>
        </form>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="adminsession">
                <h2>Admin Login</h1>
                    <label>Admin ID</label>
                    <input type="text" name="adminID" id="adminID" placeholder="Enter AdminID">
                    <label>Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter Password">
                    <input type="submit" name="adminlogin" value="login">
                    <?php echo "<label class='error'>$adminerror</label>" ?>
            </div>
        </form>
    </div>
</body>
</html>