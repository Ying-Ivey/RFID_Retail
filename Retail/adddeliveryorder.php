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
    session_start();
    include("db.php");
    include "topheader.php";
    $id = $_GET['id'];
    if (isset($_POST['btn_save'])) {
        $delivery_Order_id = $id;
        $delivery_Order_date = $_POST['delivery_Order_date'];
        $order_status = $_POST['order_status'];
        $expected_quantity = $_POST['expected_quantity'];
        $actual_quantity = $_POST['actual_quantity'];

        mysqli_query(
            $con,
            "insert into deliveryorder values ('$delivery_Order_id', '$delivery_Order_date', '$order_status', '$expected_quantity', '$actual_quantity')"
        ) or die("query incorrect");
        header("location: submit_form.php?success=1");

        mysqli_close($con);
    }
    ?>
 <!-- End Navbar -->

 <body class="dark-edition">
     <div class="content">
         <div class="container-fluid">
             <form action="" method="post" type="form" name="form" enctype="multipart/form-data">
                 <div class="row">
                     <div class="col-md-12">
                         <div class="card">
                             <div class="card-header card-header-primary">
                                 <h4 class="card-title">Add Delivery Order</h4>
                             </div>
                             <div class="card-body">
                                 <div class="row">
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>ID</label>
                                             <input type="text" id="delivery_Order_id " required
                                                 name="delivery_Order_id" value="<?php echo $id; ?>"
                                                 class="form-control">
                                         </div>
                                     </div>

                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Date</label><br>
                                             <input type="datetime-local" id="delivery_Order_date"
                                                 name="delivery_Order_date">

                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Status</label>
                                             <!-- <select class="form-control" id="order_status" name="order_status">
                                                 <option value="Delivered" selected>Delivered</option>
                                                 <option value="Enough">Enough</option>
                                                 <option value="Not enough">Not enough</option>
                                             </select> -->
                                             <input type="text" id="order_status " required name="order_status"
                                                 value="Delivered" class="form-control">
                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Expected quantity</label>
                                             <input type="number" id="expected_quantity" name="expected_quantity"
                                                 required class="form-control" value="0">
                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Actual quantity</label>
                                             <input type="number" id="actual_quantity" required name="actual_quantity"
                                                 class="has-white form-control" value="0">
                                         </div>
                                     </div>
                                 </div>
                                 <div class="card-footer">
                                     <button type="submit" id="btn_save" name="btn_save" required
                                         class="btn btn-fill btn-primary">Add</button>
                                 </div>

                             </div>

                         </div>
                     </div>

                 </div>
             </form>

         </div>
     </div>