<?php
include 'database/databaseconnect.php';
session_start();
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = array();
}
date_default_timezone_set('Asia/Kolkata');

$total = 0;
if(!empty($_SESSION['cart'])){
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['amount'];
    }
}
$patients_stm = "SELECT patient_name from patient_details order by patient_name";
$patients = $conn->query($patients_stm);
$contacts_stm = "SELECT contact_no from patient_details order by contact_no";
$contacts = $conn->query($contacts_stm);
$selected_value = "";
if(isset($_GET['patient_name'])){
    $selected_value = $_GET['patient_name'];
    $contacts_stm = "SELECT contact_no from patient_details where patient_name = '$_GET[patient_name]'";
    $contacts = $conn->query($contacts_stm);
}

$medicines_stm = "SELECT * from medicine_details order by medicine_name";
$medicines = $conn->query($medicines_stm);

if(isset($_GET['add'])){
    $availability = "SELECT quantity from medicine_details where medicine_name = '$_GET[medicine_name]'";
    $availability = $conn->query($availability)->fetch_assoc()['quantity'];
    if(empty($_GET['medicine_name']) || empty($_GET['quantity'])){
        echo "<script>alert('Provide Medicine name and quantity')</script>";
    }
    elseif($_GET['quantity'] > $availability){
        echo "<script>alert('Only $availability stocks available')</script>";
    }
    else{
        if(!empty($_SESSION['cart'])){
            $names = array_column($_SESSION["cart"],'name');
            if(!in_array($_GET['medicine_name'],$names)){
                $medicine_details_stm = "SELECT * from medicine_details where medicine_name='$_GET[medicine_name]'";
                $medicine_details = $conn->query($medicine_details_stm)->fetch_assoc();
                $item_array = array('id' => $medicine_details['medicine_id'],
                                    'name' => $medicine_details['medicine_name'], 
                                    'quantity' => $_GET['quantity'],
                                    'price' => $medicine_details['price'],
                                    'amount' => $medicine_details['price'] * $_GET['quantity']
                                    );
                $_SESSION['cart'][] = $item_array;
            }
            else{
                echo "<script>alert('Already Added')</script>";
            }
        }
        else{
            $medicine_details_stm = "SELECT * from medicine_details where medicine_name='$_GET[medicine_name]'";
            $medicine_details = $conn->query($medicine_details_stm)->fetch_assoc();
            $item_array = array('id' => $medicine_details['medicine_id'],
                                'name' => $medicine_details['medicine_name'], 
                                'quantity' => $_GET['quantity'],
                                'price' => $medicine_details['price'],
                                'amount' => $medicine_details['price'] * $_GET['quantity']
                                );
            $_SESSION['cart'][] = $item_array;
        }
        $total = 0;
        if(!empty($_SESSION['cart'])){
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['amount'];
            }
        }

    }
}

if(isset($_GET['action'])){
    if($_GET['action'] == "delete"){
        foreach( $_SESSION['cart'] as $key => $value){
            if($value['id'] == $_GET['id']){
                unset($_SESSION['cart'][$key]);
            }
        }
    }
    $total = 0;
    if(!empty($_SESSION['cart'])){
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['amount'];
        }
    }
}



if(isset($_GET['purchase'])){
    if(empty($_GET['patient_name']) || empty($_GET['contact_no'])){
        echo "<script>alert('Enter Patient details')</script>";
    }
    else{
        if($total > 0){
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $patient_id = "SELECT patient_id from patient_details where patient_name = '$_GET[patient_name]'
                            and contact_no = '$_GET[contact_no]'";
            $patient_id = $conn->query($patient_id)->fetch_assoc()['patient_id'];
            $report = "INSERT into sales_report (patient_id, date_of_purchase,
                        time_of_purchase, total_amount) values ('$patient_id', '$date', '$time', '$total')";
            $conn->query($report);
            $sales_id = "SELECT sales_id from sales_report where date_of_purchase = '$date' and
                        time_of_purchase = '$time'";
            $sales_id = $conn->query($sales_id)->fetch_assoc()['sales_id'];

            foreach( $_SESSION['cart'] as $value){
            $insert_stm = "INSERT into medicine_sales (sales_id, medicine_id, quantity, amount)
                            values ('$sales_id', '$value[id]', '$value[quantity]', '$value[amount]')";
            $conn->query($insert_stm);
            }
            echo "<script>alert('Purchased')</script>";
            $total = 0;
            $_SESSION['cart'] = array();
            $selected_value = "";
        }
    }

}

?>


<html>

<head>
    <title>SalesMedicine</title>
    <link rel="stylesheet" href="styles/salemedicine.css">
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/table.css">
    <link rel="stylesheet" href="styles/button.css">
    <script src="script/salemedicine.js" defer></script>
</head>

<body>
<script src="nav.js"></script>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get">
        <div class="addmedicine">
        <h1>Sales Medicine</h1>
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
                        </td>

                    <td>
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
                    <label>Medicine* : </label>
                </td>
                <td><select name="medicine_name">
                        <option disabled selected>Select Medicine</option>
                        <?php while ($medicine = $medicines->fetch_assoc()) {
                            echo "<option value='$medicine[medicine_name]'>$medicine[medicine_name]</option>";
                        } ?>
                    </select></td>
                <td>
                    <label  id="quantity">Quantity* : </labelS>
                <input name="quantity" type="number" min="1"></td>
                <td><input type="submit" name="add" value="add"></td>
            </tr>
            <tr>
            <td><input type="submit" value="Purchase" name="purchase"></td>
            <td>Total: Rs.<?php echo $total?></td>
            </tr>
        </table>
        </div>

    </form>
    
    <div class="cart">
    <div class="table" style="max-height: 300px; min-height: 300px;" >
        <table>
            <thead>
                <tr>
                    <th>Medicine ID</th>
                    <th>Medicine Name</th>
                    <th>Quantity</th>
                    <th>Unit price</th>
                    <th>amount</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($_SESSION['cart'] as $key => $value){
                        echo "
                        <tr>
                        <td>$value[id]</td>
                        <td>$value[name]</td>
                        <td>$value[quantity]</td>
                        <td>Rs.$value[price]</td>
                        <td>Rs.$value[amount]</td>
                        <td><a href='$_SERVER[PHP_SELF]?action=delete&id=$value[id]' style='color: darkblue;'>Remove</a></td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    </div>
</body>

</html>

<script>

</script>