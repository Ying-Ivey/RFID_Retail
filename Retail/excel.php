<?php
session_start();
include("db.php");
if (isset($_GET['id'])) {
    $search_id = $_GET['id'];
}
header('Content-Type: application/vnd.ms-excel');
header('Content-disposition: attachment; filename=' . rand() . '.xls');
?>

<head>
    <title>Retail</title>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

</head>
<style>
#excel {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#excel td,
#excel th {
    border: 1px solid #ddd;
    padding: 8px;
}

#excel tr:nth-child(even) {
    background-color: #f2f2f2;
}

#excel tr:hover {
    background-color: #ddd;
}

#excel th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #04AA6D;
    color: white;
}
</style>

<h3 class="card-title">Receiver: <?php echo $_SESSION['name']; ?></h3>

<table id="excel">

    <tr>
        <th>Delivery Order ID</th>
        <th>Delivery Order Date</th>
        <th>Status</th>
        <th>Total expected products:</th>
        <th>Total actual products:</th>
    </tr>
    <tr>
        <?php
        $result = mysqli_query($con, "SELECT * FROM deliveryorder WHERE delivery_order_id= $search_id ;") or die("query 1 incorrect.....");
        // $array_total_expected_quantity = array();
        $array_total_actual_quantity = array();
        while (list($delivery_Order_id, $delivery_Order_date, $order_status, $expected_quantity, $actual_quantity) = mysqli_fetch_array($result)) {

        ?>

        <?php
            $datecreate = date_create($delivery_Order_date);
            $dateformat = date_format($datecreate, "d/m/Y h:i:sa");
            echo "<td>$delivery_Order_id</td>
                   <td>$dateformat</td>
                   <td>$order_status</td>
                   <td>$expected_quantity</td>
                   <td>$actual_quantity</td>" ?>

    </tr>
    <?php
        } ?>

    </tbody>
</table>

<h4 class="card-title">Delivery Order Detail:</h4>

<table id="excel">

    <tr>
        <th>Product line_id</th>
        <th>Name</th>
        <th>Expected quantity</th>
        <th>Actual quantity</th>
    </tr>

    <tr>
        <?php
        if (isset($_GET['id'])) {
            $search_id = $_GET['id'];

            $result = mysqli_query($con, "SELECT count(is_checked) as actual_quantity, productinstancerfid.product_line_id, productline.name, total FROM productline, productinstancerfid, deliveryorderdetail WHERE productinstancerfid.is_checked =1 and productinstancerfid.delivery_Order_id = '$search_id' and deliveryorderdetail.delivery_Order_id = productinstancerfid.delivery_Order_id and deliveryorderdetail.product_line_id = productinstancerfid.product_line_id and productline.product_line_id=deliveryorderdetail.product_line_id GROUP BY productinstancerfid.product_line_id;") or die("query 1
                                    incorrect.....");


            while (list($actual_quantity, $product_line_id, $name, $total) =
                mysqli_fetch_array($result)
            ) {

                echo "<tr>
                                    
                                        <td>$product_line_id</td>
                                        <td>$name</td>
                                        <td>$total</td>
                                        <td>$actual_quantity</td>
                                    </tr>";
            }
        }
        ?>

    </tr>
</table>