<?php
    session_start();
    include 'database/databaseconnect.php';


    if(!isset($_SESSION['admin_id'])){
        header("Location: index.php");
        exit;
    }

    if(isset($_POST['register'])){
        if(!(empty($_POST['patient_name'])|| empty($_POST['dob'])|| empty($_POST['gender'])|| 
            empty($_POST['contact_no'])|| empty($_POST['email_id'])|| empty($_POST['address'])||
            empty($_POST['password']))){
           
            $insert_stm = "INSERT into patient_details (patient_name, dob, gender,
            contact_no, email_id, password, address) values ('$_POST[patient_name]',
            '$_POST[dob]', '$_POST[gender]', '$_POST[contact_no]', '$_POST[email_id]',
            '$_POST[password]', '$_POST[address]')";
            try{
                if($conn->query($insert_stm) === true){
                    echo "<script>alert('Registeration completed Successfully')</script>";
                }
            }
            catch(mysqli_sql_exception $e){
              echo "<script>alert('Patient already has an account')</script>";

            }
        }
        else{
            echo "<script>alert('Fill all required fields')</script>";
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registeration</title>
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/registrationpage.css">
    <link rel="stylesheet" href="styles/button.css">
    <script src="script/registration.js" defer></script>
</head>


<body>
<script src="nav.js"></script>
    <div class="records">
        <h1 style="text-align: center;">New Patient Registeration</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return formValidate()">
        <table>
            <tr>
                <td><label>Patient Name *</label></td>
                <td>:  <input type="text" id="name" name="patient_name"
                    placeholder="Enter Patient Name" oninput="nameValidate()"></td>
                <td><div class="error" id="nameerror">
                    Name must be within 3-20 characters.</div></td>
            </tr>
            <tr>
                <td><label>Date Of Birth *</label></td>
                <td>:  <input type="date" id="dob" name="dob"></td>
            </tr>
            <tr>
                <td><label>Gender *</label></td>
                <td>:  <select id="gender" name="gender">
                        <option value="" disabled selected>Select your gender</option>
                        <option value="male" name="gender">Male</option>
                        <option value="female" name="gender">Female</option>
                        <option value="other" name="gender">Other</option>
                    </select></td>
            </tr>
            <tr>
                <td><label>Contact No. *</label></td>
                <td>:  <input type="text" id="contact_no" name="contact_no"
                    placeholder="Enter Contact No." oninput="contactValidate()"></td>
                <td><div class="error" id="contacterror">Must be 10 digits.</div></td>
            </tr>
            <tr>
                <td><label>Mail ID *</label></td>
                <td>:  <input type="text" id="email" name="email_id"
                    placeholder="Enter E-Mail ID" oninput="emailValidate()"></td>
                <td><div class="error" id="emailerror">Email invalid.</div></td>
            </tr>
            <tr>
                <td><label>Password *</label></td>
                <td>:  <input type="password" id="psw" name="password"
                    placeholder="Enter Password" oninput="pswValidate()"></td>
                <td><div class="error" id="pswerror">
                    Password must be 8-12 characters includes a special, a upper, a lower, a digit.</div></td>
            </tr>
            <tr>
                <td><label>Confirm Password *</label></td>
                <td>:  <input type="password" id="rpsw" 
                    placeholder="Re-Enter Password" oninput="rpswValidate()">
                </td>
                <td><div class="error" id="rpswerror">Must be same as password.</div></td>
            </tr>
            <tr>
                <td><label>Address *</label></td>
                <td><textarea rows="5" cols="30" id="address" name="address" style="min-width: 300px;"
                    placeholder="Enter Address" oninput="addressValidate()"></textarea></td>
                <td><div class="error" id="addresserror">Address must be within 10-150 charactes.</div></td>
            </tr>
            <tr>
                <td><input type="submit" value="Register" name="register"></td>
                <td><input type="reset" value="Clear All"></td>
            </tr>
        </table>
    </form>
    </div>
</body>
</html>