<?php
	
	include "connect_android.php";

	$delivery_Order_id = isset($_POST['delivery_Order_id']) ? $_POST['delivery_Order_id'] : '';
	$delivery_Order_date = isset($_POST['delivery_Order_date']) ? $_POST['delivery_Order_date'] : '';
	$order_status= isset($_POST['order_status']) ? $_POST['order_status'] : '';
	$expected_quantity= isset($_POST['expected_quantity']) ? $_POST['expected_quantity'] : '';
	$actual_quantity= isset($_POST['actual_quantity']) ? $_POST['actual_quantity'] : '';
	
	
	if (empty($delivery_Order_date) || empty($order_status)|| empty($expected_quantity)|| empty($actual_quantity)) { 
		echo "Dữ liệu không được để trống"; 
		
	} else {
		$query = mysqli_query($con,"INSERT INTO deliveryorder VALUES('".$delivery_Order_id."','".$delivery_Order_date."','".$order_status."','".$expected_quantity."','".$actual_quantity."')");
		
		if ($query) {
			echo "Dữ liệu đã lưu thành công";
			
		} else{ 
			echo "Lỗi lưu dữ liệu";
			
		}
	}
		
?>