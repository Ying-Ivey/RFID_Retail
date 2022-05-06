 <?php
    session_start();
    include("db.php");
    ?>

 <head>
     <title>Delete Delivery Order</title>
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

     <div class="resultOfDeleteDeliveryOrder">
         <a href="index.php" class="btn btn-primary">Return</a><br>
         <?php
            if (isset($_GET['delete_id'])) {
                $search_id = $_GET['delete_id'];
                $resultDetail = mysqli_query($con, "DELETE FROM `deliveryorderdetail` WHERE delivery_Order_id = $search_id;") or die("<h4>Can't delete it</h4>");
                $result = mysqli_query($con, "DELETE FROM deliveryorder WHERE delivery_Order_id = $search_id;") or die("<h4>Can't delete it</h4>");

                if ($result) {
                    echo "<h4>Successfully deleted</h4>";
                }
            }
            ?>

     </div>