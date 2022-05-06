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
<style type="text/css">
table {
    width: 100%;
    border-collapse: collapse;
}

table tr td,
table tr th {
    border: 1px solid black;
    padding: 25px;
}
</style>
<?php
session_start();
include("db.php");
include "topheader.php";
$id = $_GET['id'];

?>
<!-- End Navbar -->

<body class="dark-edition">
    <div class="content">
        <div class="container-fluid">
            <?php
            if (isset($_POST["addDeliveryOrderDetail"])) {
                $deliveryorderid = $_GET['id'];
                for ($a = 0; $a < count($_POST["productid"]); $a++) {
                    $sql = "INSERT INTO deliveryorderdetail VALUES ('$deliveryorderid', '" . $_POST["productid"][$a] . "', '" . $_POST["total"][$a] . "');";
                    $result = mysqli_query($con, $sql) or die("<h4>One or more products that cannot be added</h4>");
                }
                if ($result) {
                    echo "<h4>This delivery order detail has been added.</h4>";
                }
            }

            ?>
            <form action="" method="post" type="form" name="form" enctype="multipart/form-data">
                <div class="card ">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Delivery Order ID: <?php echo $id; ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive ps">
                            <table class="tablewhiteborder">
                                <tr>
                                    <th>#</th>
                                    <th>product_line_id</th>
                                    <th>total</th>
                                    <th>Delete</th>
                                </tr>
                                <tbody id="tbody"></tbody>
                            </table>

                            <button class="btn btn-primary" type="button" onclick="addItem();">Add Item</button>
                            <input type="submit" class="btn btn-primary" name="addDeliveryOrderDetail"
                                value="Add Details">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    var items = 0;

    function addItem() {
        items++;

        var html = "<tr>";
        html += "<td>" + items + "</td>";
        html +=
            "<select name='productid[]'><option value='SP001' selected='selected'>Laptop Asus Gaming Rog Strix G15</option><option value='SP002'>Smart Tivi 4K The Serif Samsung</option><option value='SP003'>Tai nghe Bluetooth Apple AirPods 3</option><option value='SP004'>Đồng hồ thông minh Xiaomi Watch</option></select>"
        // html += "<td><input type='text' name='productid[]'></td>";
        html += "<td><input type='text' name='total[]'></td>";
        html +=
            "<td><button class='btn' type = 'button' onclick = 'deleteRow(this);' > Delete </button></td > ";
        html += "</tr>";

        var row = document.getElementById("tbody").insertRow();
        row.innerHTML = html;
    }


    function deleteRow(button) {
        button.parentElement.parentElement.remove();
        // first parentElement will be td and second will be tr.
    }
    </script>