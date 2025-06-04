<?php
session_start();
include 'database/databaseconnect.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$medicines_stm = "SELECT medicine_name from medicine_details order by medicine_name";
$medicines = $conn->query($medicines_stm);


if (isset($_POST['update'])) {
    if (empty($_POST['medicine_name']) || empty($_POST['quantity'])) {
        echo "<script>alert('Fill required fields')</script>";
    } else {
        $medicine_stm = "SELECT medicine_id, quantity from medicine_details where medicine_name = '$_POST[medicine_name]'";
        $medicine = $conn->query($medicine_stm)->fetch_array();
        $update = "UPDATE medicine_details set quantity=('$_POST[quantity]'+'$medicine[quantity]')
                    where medicine_id = '$medicine[medicine_id]'";
        if ($conn->query($update)) {
            echo "<script>alert('stock updated')</script>";
        } else {
            echo "<script>alert('error in updating database')</script>";

        }
    }
}

if (isset($_POST['add'])) {
    if (
        empty($_POST['medicine_name']) || empty($_POST['quantity'])
        || empty($_POST['price']) || empty($_POST['type'])
    ) {
        echo "<script>alert('Fill required fields')</script>";

    } else {
        $check_stm = "SELECT medicine_id from medicine_details where medicine_name='$_POST[medicine_name]'";
        if ($conn->query($check_stm)->num_rows > 0) {
            echo "<script>alert('Medicine Alread Exist')</script>";

        } else {
            $add_stm = "INSERT into medicine_details (medicine_name, quantity, price, medicine_type) values
            ('$_POST[medicine_name]', '$_POST[quantity]', '$_POST[price]', '$_POST[type]')";

            if ($conn->query($add_stm)) {
                echo "<script>alert('Stock Updated')</script>";
            } else {
                echo "<script>alert('error in updating database')</script>";
            }
        }
    }
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Stocks Update</title>
    <link rel="stylesheet" href="styles/updatestocks.css">
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/button.css">
</head>

<body>
    <script src="nav.js"></script>
    <div class="addsession">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <h1>Add new Medicine</h1>
            <table>
                <tr>
                    <td>Medicine Name* :</td>
                    <td><input type="text" name="medicine_name" placeholder="Enter Medicine Name"></td>
                </tr>
                <tr>
                    <td>Price* :</td>
                    <td><input type="number" min="1" name="price" placeholder="Enter price"></td>
                </tr>
                <tr>
                    <td>Quantity* :</td>
                    <td><input type="number" name="quantity" placeholder="Enter Quantity" min="1"></td>
                </tr>
                <tr>
                    <td>Type* :</td>
                    <td><select name="type">
                            <option disabled selected>Select Type</option>
                            <option>Capsule</option>
                            <option>Cream</option>
                            <option>Drops</option>
                            <option>Gel</option>
                            <option>Granules</option>
                            <option>Inhaler</option>
                            <option>Injection</option>
                            <option>IV (Intravenous infusion)</option>
                            <option>Lozenge</option>
                            <option>Lotion</option>
                            <option>Nebulizer solution</option>
                            <option>Nasal drops</option>
                            <option>Ointment</option>
                            <option>Patch</option>
                            <option>Pessary</option>
                            <option>Powder</option>
                            <option>Soap</option>
                            <option>Spray</option>
                            <option>Suppository</option>
                            <option>Syrup/Tonic</option>
                            <option>Tablet</option>
                        </select></td>
                </tr>
                <tr>
                    <td><input type="submit" name="add" value="add"></td>
                </tr>
            </table>
        </form>
    </div>
    <br>
    <div class="updatesession">
        <h1>Update Medicine stocks</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <table>
                <tr>
                    <td>
                        <lable>Medicine* : </lable>
                    </td>
                    <td><select name="medicine_name">
                            <option disabled selected>Select Medicine Name</option>
                            <?php
                            while ($medicine = $medicines->fetch_assoc()) {
                                echo "<option value=$medicine[medicine_name]>$medicine[medicine_name]</option>";
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <lable>Add Quantity* : </lable>
                    </td>
                    <td><input type="number" min="1" name="quantity" placeholder="Enter Quantity"></td>
                </tr>
                <tr>
                    <td><input type="submit" name="update" value="update"></td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>