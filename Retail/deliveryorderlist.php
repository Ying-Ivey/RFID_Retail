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
<style>
input[type=text] {
    padding: 7px 20px;
    margin: 8px 0;
    box-sizing: border-box;
}
</style>

<body class="dark-edition">
    <!-- End Navbar -->
    <div class="content">
        <div class="container-fluid">
            <div class="search-container">
                <form method="get">
                    <input type="number" placeholder="Input id.." id="search" name="search">
                    <button class="btn btn-success" type=" submit">Submit</button>
                </form>
            </div>
            <?php
            if (isset($_GET['search'])) {
                $search_id = $_GET['search'];
            ?>
            <p class="deliveryorderid-p">Delivery Order ID: <?php echo $search_id ?></p>
            <?php
                echo "<a href='adddeliveryorderdetail.php?id=$search_id'>&emsp;&ensp; Add More Delivery Order
                Details</a>"
                ?>
            <?php } ?>

            <div class="col-md-14">
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
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_GET['search'])) {
                                        $search_id = $_GET['search'];

                                        $result = mysqli_query($con, "SELECT * FROM deliveryorderdetail, productline WHERE delivery_order_id= $search_id and deliveryorderdetail.product_line_id = productline.product_line_id;",) or die("query 1
                                    incorrect.....");

                                        while (list($delivery_order_id, $product_line_id, $total, $product_line_id, $name) =
                                            mysqli_fetch_array($result)
                                        ) {
                                            echo "<tr>
                                    
                                        <td>$product_line_id</td>
                                        <td>$name</td>
                                        <td>$total</td>
                                        <td><a href='deletedeliveryorderdetail.php?delete_id=$delivery_order_id&product=$product_line_id'>✖</a></td>
                                        
                                    </tr>";
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>