<?php
	
	include "connect_android.php";
	
	$id = isset($_POST['delivery_Order_id']) ? $_POST['delivery_Order_id'] : '';
	
	if (empty($delivery_Order_id)) { 
		echo "id không được để trống"; 
		
	} else {
		$query = mysqli_query($con,"DELETE FROM deliveryorder WHERE id = '".$delivery_Order_id."'");
		
		if ($query) {
			echo "Đã xóa dữ liệu thành công";
			
		} else{ 
			echo "Không xóa được dữ liệu";
			
		}	
	}
?>