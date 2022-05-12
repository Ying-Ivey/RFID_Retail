 <?php
    session_start();
    include("db.php");

    // include "sidenav.php";
    include "topheader.php";
    $queryselect = mysqli_query($con, "SELECT delivery_Order_id FROM `deliveryorder`");
    while (list($delivery_Order_id) = mysqli_fetch_array($queryselect)) {
        $quantity = mysqli_query($con, " SELECT sum(total) as expectedquantity FROM `deliveryorderdetail` WHERE delivery_Order_id= '$delivery_Order_id'");
        list($expectedquantity) = mysqli_fetch_array($quantity);
        $resultForExpectedQuantityDeliveryOrder = mysqli_query($con, "UPDATE deliveryorder SET expected_quantity = '" . $expectedquantity . "' WHERE delivery_Order_id ='$delivery_Order_id' ") or die("<h4>Can't update expected_quantity</h4>");
    }
    $expectedquantity = mysqli_query($con, "SELECT delivery_Order_id, expected_quantity, actual_quantity FROM `deliveryorder`");
    $array_id = array();
    $array_expected_quantity = array();
    $array_actual_quantity = array();
    while (list($delivery_Order_id, $expected_quantity, $actual_quantity) = mysqli_fetch_array($expectedquantity)) {
        array_push($array_id, $delivery_Order_id);
        array_push($array_expected_quantity, $expected_quantity);
        array_push($array_actual_quantity, $actual_quantity);
    }
    $length_array = sizeof($array_id);
    for ($x = 0; $x < $length_array; $x++) {

        if ($array_actual_quantity[$x] == 0) {
            $resultUpdateStatus = mysqli_query($con, "UPDATE deliveryorder SET order_status = 'Unchecked' WHERE delivery_Order_id ='$array_id[$x]' ") or die("<h4>Can't update status</h4>");
        } else {

            $difference = $array_expected_quantity[$x] - $array_actual_quantity[$x];

            if ($difference == 0) {
                $resultUpdateStatus = mysqli_query($con, "UPDATE deliveryorder SET order_status = 'Enough' WHERE delivery_Order_id ='$array_id[$x]' ") or die("<h4>Can't update status</h4>");
            }
            if ($difference > 0) {

                if ($difference == 1) {
                    $resultUpdateStatus = mysqli_query($con, "UPDATE deliveryorder SET order_status = 'Missing 1 product' WHERE delivery_Order_id ='$array_id[$x]' ") or die("<h4>Can't update status</h4>");
                }
                if ($difference > 1) {
                    $resultUpdateStatus = mysqli_query($con, "UPDATE deliveryorder SET order_status = 'Missing $difference products' WHERE delivery_Order_id ='$array_id[$x]' ") or die("<h4>Can't update status</h4>");
                }
            }
            if ($difference < 0) {
                $differenceExpectLessThan = abs($difference);

                if ($differenceExpectLessThan == 1) {
                    $resultUpdateStatus = mysqli_query($con, "UPDATE deliveryorder SET order_status = 'Surplus of 1 product' WHERE delivery_Order_id ='$array_id[$x]' ") or die("<h4>Can't update status</h4>");
                }
                if ($differenceExpectLessThan > 1) {
                    $resultUpdateStatus = mysqli_query($con, "UPDATE deliveryorder SET order_status = 'Surplus of $differenceExpectLessThan products' WHERE delivery_Order_id ='$array_id[$x]' ") or die("<h4>Can't update status</h4>");
                }
            }
        }
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

 </head>

 <body class="dark-edition">
     <div class="content">
         <div class="container-fluid">
             <div class="col-md-14">
                 <div class="card">
                     <div class="card-header card-header-primary">
                         <h4 class="card-title">Delivery Order</h4>
                     </div>
                     <div class="card-body">
                         <div class="table-responsive ps">
                             <table class="table table-hover tablesorter ">
                                 <thead class=" text-primary">
                                     <tr>
                                         <!-- <th>select</th> -->
                                         <th>delivery_Order_id</th>
                                         <th>delivery_Order_date</th>
                                         <th>order_status</th>
                                         <th>expected_quantity</th>
                                         <th>actual_quantity</th>
                                         <th>Details</th>
                                         <th>Edit</th>
                                         <th>Delete</th>
                                         <th>Print</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <tr>
                                         <?php
                                            $result = mysqli_query($con, "select * from deliveryorder") or die("query 1 incorrect.....");

                                            while (list($delivery_Order_id, $delivery_Order_date, $order_status, $expected_quantity, $actual_quantity) = mysqli_fetch_array($result)) { ?>

                                         <?php
                                                $datecreate = date_create($delivery_Order_date);
                                                $dateformat = date_format($datecreate, "d/m/Y h:i:sa");
                                                echo "<td>$delivery_Order_id</td>
                                                <td>$dateformat</td>
                                                <td>$order_status</td>
                                                <td>$expected_quantity</td>
                                                <td>$actual_quantity</td>" ?>
                                         <td><a
                                                 href='deliveryorderlist.php?search=<?php echo $delivery_Order_id; ?>'>📄</a>
                                         </td>
                                         <td><a
                                                 href='editdeliveryorder.php?update_id=<?php echo $delivery_Order_id; ?>'>✎</a>
                                         </td>
                                         <td><a href='deletedeliveryorder.php?delete_id=<?php echo $delivery_Order_id; ?>'
                                                 onclick='return  confirm("Do you want to delete?")'>✖</a></td>
                                         <td><a href='printdeliveryorder.php?id=<?php echo $delivery_Order_id ?>'>🖨</a>
                                         </td>

                                     </tr>
                                     <?php
                                            } ?>

                                 </tbody>
                             </table>

                         </div>
                     </div>

                 </div>

             </div>

         </div>
     </div>

     </div>

     </div>