<?php
$result = mysqli_query($con, "SELECT MAX(delivery_Order_id) as total FROM deliveryorder");
$data = mysqli_fetch_assoc($result);

$result2 = mysqli_query($con, "SELECT delivery_Order_id FROM deliveryorder WHERE delivery_Order_id not in (select delivery_Order_id from deliveryorderdetail)");
$data2 = mysqli_fetch_assoc($result2);
if (isset($_SESSION['username'])) {

?>
<div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
            <div class="navbar-wrapper">
                <a class="staffname" href="logout.php"> <?php echo $_SESSION['name']; ?></a>
                <a class="leftheader" href="index.php">
                    <p>Delivery Order</p>
                </a>
                <a class="leftheadersecond" href="deliveryorderlist.php">
                    <p>Delivery Order Details</p>
                </a>

                <a class="leftheadersecond" href="productlist.php">
                    <p>Product</p>
                </a>

                <a class="leftheadersecond" href="adddeliveryorder.php?id=<?php echo $data['total'] + 1; ?>">
                    <p>Add Delivery Order</p>
                </a>

                <a class="leftheadersecond"
                    href="adddeliveryorderdetail.php?id=<?php echo $data2['delivery_Order_id']; ?>">
                    <p>Add Delivery Order Details</p>
                </a>

            </div>

        </div>
    </nav>
</div>
<?php

} else {

    header("Location: login.php");

    exit();
}

?>