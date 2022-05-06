<?php
include "connect_android.php";
$delivery_Order_id = isset($_POST['delivery_Order_id']) ? $_POST['delivery_Order_id'] : '';
$product_instance_id = isset($_POST['product_instance_id']) ? $_POST['product_instance_id'] : '';

if (empty($delivery_Order_id)) {
	echo "Output data error when id is empty";
} else {
	$query 	= mysqli_query($con, "UPDATE productinstancerfid SET is_checked = '1', delivery_Order_id = '" . $delivery_Order_id . "' WHERE product_instance_id = '" . $product_instance_id . "'");

	$query2 = mysqli_query($con, "UPDATE deliveryorder SET actual_quantity = (SELECT COUNT(is_checked) FROM productinstancerfid WHERE is_checked = '1' and delivery_Order_id = '" . $delivery_Order_id . "') where delivery_Order_id = '" . $delivery_Order_id . "'");
	if ($query && $query2) {
		echo "Data saved successfully";
	} else {
		echo "Data save error";
	}
}


mysqli_close($con);