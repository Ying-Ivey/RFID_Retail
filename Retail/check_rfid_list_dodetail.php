<?php

	$mysqli = new mysqli("localhost:3308", "root", "asd123!@#", "retail_database_seminar");

	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: " . $mysqli->connect_error;
		exit();
	}
	$delivery_Order_id = isset($_POST['delivery_Order_id']) ? $_POST['delivery_Order_id'] : '';
	$product_instance_id = isset($_POST['product_instance_id']) ? $_POST['product_instance_id'] : '';

	$sql = "UPDATE deliveryorderdetail SET is_checked = 1
		WHERE product_instance_id in (
			SELECT product_instance_id
				FROM productinstancerfid
				WHERE productinstancerfid.product_instance_id = '".$product_instance_id."'
		);";
	$sql .= "UPDATE deliveryorder SET actual_quantity = 
		(SELECT COUNT(is_checked) FROM deliveryorderdetail where deliveryorder.delivery_Order_id = deliveryorderdetail.delivery_Order_id group by is_checked) where delivery_Order_id = '".$delivery_Order_id."' ";
	// Execute multi query
	if ($mysqli -> multi_query($sql)) {
	do {
		// Store first result set
		if ($result = $mysqli -> store_result()) {
		while ($row = $result -> fetch_row()) {
			printf("row[0] %s\n", $row[0]);
		}
		$result -> free_result();
		}
		
		if ($mysqli -> more_results()) {
		printf("next result ------\n");
		}
		//Prepare next result set
	} while ($mysqli -> next_result());
	}
	
	$mysqli -> close();
?>