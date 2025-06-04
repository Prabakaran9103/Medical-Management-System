<?php
    session_start();
    include 'database/databaseconnect.php';

    $total = 0;
    if (!isset($_SESSION['admin_id'])) {
        header("Location: index.php");
        exit();
    }
    $sales_details = "SELECT * from sales_report order by sales_id desc";
    $sales_details = $conn->query($sales_details);

    if(isset($_GET['fetch'])){
        if(empty($_GET['from_date']) || empty($_GET['to_date'])){
            echo "<script>alert('Select both From and To dates')</script>";
        }
        elseif($_GET['from_date'] > $_GET['to_date']){
            echo "<script>alert('Invalid Dates')</script>";
        }
        else{
            $_SESSION['from_date'] = $_GET['from_date'];
            $_SESSION['to_date'] = $_GET['to_date'];
            $sales_details = "SELECT * from sales_report where date_of_purchase between
                            '$_GET[from_date]' and '$_GET[to_date]' order by sales_id desc";
            $sales_details = $conn->query($sales_details);
        }
    }


    if(isset($_GET['action'])){
        if($_GET['action'] == "view"){
            if(isset($_SESSION['from_date'])){
                $sales_details = "SELECT * from sales_report where date_of_purchase between
            '$_SESSION[from_date]' and '$_SESSION[to_date]' order by sales_id desc";
            }
            else{
                $sales_details = "SELECT * from sales_report order by sales_id desc";
            }
            $sales_details = $conn->query($sales_details);
            $sales = "SELECT ms.medicine_id, md.medicine_name, ms.quantity, md.price, ms.amount, ms.sales_id
                     from medicine_sales as ms
                     inner join medicine_details as md on ms.medicine_id = md.medicine_id
                     where ms.sales_id = '$_GET[sales_id]' order by medicine_id";
            $sales = $conn->query($sales);

        }

    }
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles/table.css">
        <link rel="stylesheet" href="styles/nav.css">
        <link rel="stylesheet" href="styles/button.css">


    </head>
    <body>
    <script src="nav.js"></script>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get">
        <div style="padding: 30px;">
            From date* : <input name="from_date" type="date" style="margin-right: 50px;">
            To date* : <input name="to_date" type="date" style="margin-right: 50px;">
            <!-- <input type="submit" name="fetch" value="Fetch"> -->
            <button type="submit" name="fetch">Fetch</button>
        </div>
        </form>
        

        <div class="table" style="max-height: 350px;">
            <table>
                <thead>
                    <tr>
                        <th id="medicine">Sales Id</th>
                        <th id="patient">Patient Id</th>
                        <th id="amount">Total Amount</th>
                        <th id="date">Date of Purchase</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while($sales_detail = $sales_details->fetch_assoc()){
                            echo "
                            <tr>
                                <td>$sales_detail[sales_id]</td>
                                <td>$sales_detail[patient_id]</td>
                                <td>Rs.$sales_detail[total_amount]</td>
                                <td>$sales_detail[date_of_purchase]</td>
                                <td><a href='$_SERVER[PHP_SELF]?action=view&sales_id=$sales_detail[sales_id]' style='color: darkblue;'>View</a></td>

                            </tr>
                            ";
                            $total = $total + $sales_detail['total_amount'];
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <h3>Total : Rs.<?php echo $total ?></h3>

        <?php
            if(isset($_GET['action'])){
                if($_GET['action'] == "view"){
        echo "<div class='table' style='max-height : 280px;'>
        <table>
                <thead>
                    <tr>
                        <th>Medicine ID</th>
                        <th>Medicine Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>";
                        while($sale = $sales->fetch_assoc()){
                            echo "
                            <tr>
                                <td>$sale[medicine_id]</td>
                                <td>$sale[medicine_name]</td>
                                <td>$sale[quantity]</td>
                                <td>Rs.$sale[price]</td>
                                <td>Rs.$sale[amount]</td>
                            </tr>
                            ";
                        }
                    echo "
                </tbody>
            </table>
        </div>"; }}
        ?>
        
    </body>
</html>