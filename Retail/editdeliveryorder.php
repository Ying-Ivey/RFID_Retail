 <?php
    session_start();
    include("db.php");
    include "topheader.php";

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
 <?php
    if (isset($_GET['update_id'])) {
        $search_id = $_GET['update_id'];
        $result = mysqli_query($con, "SELECT * FROM deliveryorder WHERE delivery_order_id= $search_id ;",) or die("query 1
                                    incorrect.....");

        while (list($delivery_Order_id, $delivery_Order_date, $order_status, $expected_quantity, $actual_quantity) =
            mysqli_fetch_array($result)
        ) {
            $id = $delivery_Order_id;
            $date = $delivery_Order_date;
            $status = $order_status;
            $expected = $expected_quantity;
            $actual = $actual_quantity;
        }
    }
    if (isset($_POST['btn_save'])) {
        $delivery_Order_id = $id;
        $order_date = $date;
        $order_status = $_POST['order_status'];
        $expected_quantity = $_POST['expected_quantity'];
        $actual_quantity = $_POST['actual_quantity'];
        $timestamp = strtotime($order_date);
        $delivery_Order_date = date("Y-m-d H:i:s", $timestamp);
        mysqli_query(
            $con,
            "UPDATE deliveryorder SET `delivery_Order_date`='$delivery_Order_date',`order_status`= '$order_status',`expected_quantity`='$expected_quantity',`actual_quantity`='$actual_quantity' WHERE `delivery_Order_id`='$delivery_Order_id'"
        ) or die("query incorrect");
        header("location: submit_form.php?success=1");

        mysqli_close($con);
    }
    ?>

 <body class="dark-edition">
     <div class="content">
         <div class="container-fluid">
             <form action="" method="post" type="form" name="form" enctype="multipart/form-data">
                 <div class="row">
                     <div class="col-md-12">
                         <div class="card">
                             <div class="card-header card-header-primary">
                                 <h5 class="title">Edit Delivery Order</h5>
                             </div>
                             <div class="card-body">
                                 <div class="row">
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>ID</label>
                                             <input type="text" id="delivery_Order_id " required
                                                 name="delivery_Order_id" value="<?php echo $id; ?>"
                                                 class="form-control" disabled>
                                         </div>
                                     </div>

                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Date</label><br>
                                             <input type="text" id="delivery_Order_date" name="delivery_Order_date"
                                                 value="<?php echo $date; ?>" disabled>

                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Status</label>
                                             <input type="text" id="order_status" required name="order_status"
                                                 class="form-control" value="<?php echo $status; ?>">
                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Expected quantity</label>
                                             <input type="number" id="expected_quantity" name="expected_quantity"
                                                 required class="form-control" value="<?php echo $expected; ?>">
                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Actual quantity</label>
                                             <input type="number" id="actual_quantity" required name="actual_quantity"
                                                 class="form-control" value="<?php echo $actual; ?>">
                                         </div>
                                     </div>
                                 </div>
                                 <div class="card-footer">
                                     <button type="submit" id="btn_save" name="btn_save" required
                                         class="btn btn-fill btn-primary">Edit</button>
                                 </div>

                             </div>

                         </div>
                     </div>

                 </div>
             </form>

         </div>
     </div>