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
        $result = mysqli_query($con, "SELECT * FROM `productline` WHERE product_line_id= '$search_id' ;",) or die("query 1
                                    incorrect.....");

        while (list($product_line_id, $name, $price, $stock) =
            mysqli_fetch_array($result)
        ) {
            $id = $product_line_id;
            $product_name = $name;
            $product_price = $price;
            $product_stock = $stock;
        }
    }
    if (isset($_POST['btn_save'])) {
        $product_line_id = $id;
        $name = $_POST['product_name'];
        $previous_price = $_POST['product_price'];
        $price = str_replace(',', '', $previous_price);
        $stock = $_POST['product_stock'];
        mysqli_query(
            $con,
            "UPDATE `productline` SET `name`='$name',`price`='$price',`stock`='$stock' WHERE product_line_id= '$search_id' "
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
                                             <input type="text" id="product_line_id " required name="product_line_id"
                                                 value="<?php echo $search_id; ?>" class="form-control" disabled>
                                         </div>
                                     </div>

                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Name</label><br>
                                             <input type="text" id="product_name" name="product_name"
                                                 value="<?php echo $product_name; ?>" class="form-control" disabled>

                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Price</label>
                                             <input type="text" min="1000" id="product_price" required
                                                 name="product_price" class="form-control"
                                                 value="<?php echo number_format($product_price, 0, '', ','); ?>">
                                         </div>
                                     </div>
                                     <div class="col-md-12">
                                         <div class="form-group">
                                             <label>Stock</label>
                                             <input type="number" id="product_stock" name="product_stock" required
                                                 class="form-control" value="<?php echo $product_stock; ?>">
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
     <script>
     function formatCash(str) {
         return str.split('').reverse().reduce((prev, next, index) => {
             return ((index % 3) ? next : (next + ',')) + prev
         })
     }
     </script>