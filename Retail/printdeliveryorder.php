<?php
session_start();
include("db.php");
include "topheader.php";
if (isset($_GET['id'])) {
    $search_id = $_GET['id'];
    // $queryselect = mysqli_query($con, "SELECT COUNT(is_checked) as actual_total FROM productinstancerfid WHERE is_checked = '1' and delivery_Order_id = '" . $search_id . "'");
    // list($actual_total) = mysqli_fetch_array($queryselect);
}
?>

<head>
    <title>Retail</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="assets/css/Material+Icons.css" />
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <!-- Material Kit CSS -->
    <link href="assets/css/material-dashboard.css?v=2.1.0" rel="stylesheet" />
    <link href="assets/css/black-dashboard.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

</head>

<body class="dark-edition">

    <div class="content">

        <div class="container-fluid">
            <br><br>

            <div class="col-md-14">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Receiver: <?php echo $_SESSION['name']; ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive ps">
                            <table class="table table-hover tablesorter " id="">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>Delivery Order ID</th>
                                        <th>Delivery Order Date</th>
                                        <th>Status</th>
                                        <th>Total expected products:</th>
                                        <th>Total actual products:</th>
                                    </tr>
                                </thead>
                                <tbody>
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

                        </div>

                    </div>
                    <div class="card carddolist ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Delivery Order Detail</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ps">
                                <table class="table table-hover tablesorter " id="">
                                    <thead class=" text-primary">
                                        <tr>
                                            <th>product_line_id</th>
                                            <th>name</th>
                                            <th>total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_GET['id'])) {
                                            $search_id = $_GET['id'];

                                            $result = mysqli_query($con, "SELECT * FROM deliveryorderdetail, productline WHERE delivery_order_id= $search_id and deliveryorderdetail.product_line_id = productline.product_line_id;",) or die("query 1
                                    incorrect.....");

                                            while (list($delivery_order_id, $product_line_id, $total, $product_line_id, $name) =
                                                mysqli_fetch_array($result)
                                            ) {
                                                echo "<tr>
                                    
                                        <td>$product_line_id</td>
                                        <td>$name</td>
                                        <td>$total</td>
                                    </tr>";
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div align="center">
                            <button name="create_excel" id="create_excel" class="btn btn-success">Create Excel
                                File</button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    </div>

    </div>

</body>
<script>
$(document).ready(function() {
    $('#create_excel').click(function() {
        var page = "excel.php?id=<?php echo $search_id; ?> "
        window.location = page;
    });
});
</script>