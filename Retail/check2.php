<?php
include "connect_android.php";
$delivery_Order_id = isset($_POST['delivery_Order_id']) ? $_POST['delivery_Order_id'] : '';
$product_instance_id = isset($_POST['product_instance_id']) ? $_POST['product_instance_id'] : '';

$query3 = mysqli_query($con, "SELECT 1 FROM deliveryorder WHERE actual_quantity = expected_quantity and  delivery_Order_id = '" . $delivery_Order_id . "'");
if (mysqli_num_rows($query3) == 1) {
	echo "Đơn hàng này đủ số lượng sản phẩm";
} else {
	$query4 = mysqli_query($con, "SELECT deliveryorderdetail.product_instance_id, productline.name 
						FROM deliveryorderdetail, productline, productinstancerfid 
						where deliveryorderdetail.delivery_Order_id = '" . $delivery_Order_id . "'
						 and is_checked = '0' and deliveryorderdetail.product_instance_id = productinstancerfid.product_instance_id and productline.product_line_id = productinstancerfid.product_line_id");
	echo "Còn thiếu Sản phẩm ";
	while ($row = mysqli_fetch_assoc($query4)) {
		echo $row['product_instance_id'];
		echo $row['name'];
	}
}
mysqli_close($con);