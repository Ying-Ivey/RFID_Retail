 <?php
    session_start();
    include("db.php");

    // include "sidenav.php";
    include "topheader.php";

    ?>
 <!-- 
 <head>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
 </head> -->

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
             <div class="col-md-14">
                 <div class="card ">
                     <div class="card-header card-header-primary">
                         <h4 class="card-title">Product</h4>
                     </div>
                     <div class="card-body">
                         <div class="table-responsive ps">
                             <table class="table table-hover tablesorter ">
                                 <thead class=" text-primary">
                                     <tr>
                                         <!-- <th>select</th> -->
                                         <th>product_line_id</th>
                                         <th>name</th>
                                         <th>price</th>
                                         <th>stock</th>
                                         <th>Edit</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <tr>

                                         <?php
                                            $result = mysqli_query($con, "SELECT * FROM productline") or die("query 1 incorrect.....");

                                            while (list($product_line_id, $name, $price, $stock) = mysqli_fetch_array($result)) { ?>

                                         <?php
                                                $product_price = number_format($price, 0, '', ',');
                                                echo "<td>$product_line_id</td>
                                     <td>$name</td>
                                     <td>$product_price</td>
                                         <td>$stock</td>" ?>
                                         <td><a href='editproduct.php?update_id=<?php echo $product_line_id; ?>'>✎</a>
                                         </td>
                                     </tr>
                                     <?php
                                            }

                                    ?>


                                 </tbody>
                             </table>

                         </div>

                     </div>

                 </div>

             </div>


         </div>