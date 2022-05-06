<?php
	include "connect_android.php";

	$delivery_Order_id = isset($_POST['delivery_Order_id']) ? $_POST['delivery_Order_id'] : '';
	class emp{}
	
	if (empty($delivery_Order_id)) { 
		echo "Lỗi truy xuất dữ liệu khi id rỗng"; 
		
	} else {
		$query 	= mysqli_query($con,"SELECT * FROM deliveryorder WHERE delivery_Order_id ='".$delivery_Order_id."'");
		$row 	= mysqli_fetch_array($query);
		
		if (!empty($row)) {
			$response = new emp();
			$response->delivery_Order_id = $row["delivery_Order_id"];
			$response->delivery_Order_date = $row["delivery_Order_date"];
			$response->order_status = $row["order_status"];
			$response->expected_quantity = $row["expected_quantity"];
			$response->actual_quantity = $row["actual_quantity"];
			
			echo(json_encode($response));
		} else{ 
			
			echo("Lỗi khi truy xuất dữ liệu"); 
		}	
	}
?>