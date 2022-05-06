<?php
include "connect_android.php";

$delivery_Order_id = isset($_POST['delivery_Order_id']) ? $_POST['delivery_Order_id'] : '';
$delivery_Order_date = isset($_POST['delivery_Order_date']) ? $_POST['delivery_Order_date'] : '';
$order_status = isset($_POST['order_status']) ? $_POST['order_status'] : '';
$expected_quantity = isset($_POST['expected_quantity']) ? $_POST['expected_quantity'] : '';
$actual_quantity = isset($_POST['actual_quantity']) ? $_POST['actual_quantity'] : '';

class emp
{
}

if (empty($delivery_Order_id)) {
	echo "Lỗi truy xuất dữ liệu khi id rỗng";
} else {
	$query 	= mysqli_query($con, "UPDATE deliveryorder SET delivery_Order_date = '" . $delivery_Order_date . "', order_status = '" . $order_status . "', expected_quantity = '" . $expected_quantity . "', actual_quantity = '" . $actual_quantity . "' where delivery_Order_id = '" . $delivery_Order_id . "' ");
	if ($query) {
		echo "Dữ liệu đã lưu thành công";
	} else {
		echo "Lỗi lưu dữ liệu";
	}
}