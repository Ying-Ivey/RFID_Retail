<?php

include "connect_android.php";

$product_instance_id = isset($_POST['product_instance_id']) ? $_POST['product_instance_id'] : '';
$product_line_id = isset($_POST['product_line_id']) ? $_POST['product_line_id'] : '';


if (empty($product_instance_id) || empty($product_line_id)) {
	echo "Data cannot be empty";
} else {
	$query = mysqli_query($con, "INSERT INTO productinstancerfid VALUES('" .$product_instance_id. "','" .$product_line_id. "','0','')");
	$query2 = mysqli_query($con, "UPDATE productline SET stock = stock + 1 WHERE product_line_id ='" .$product_line_id. "'");

	if ($query && $query2) {
		echo "Data saved successfully";
	} else {
		echo "Error saving data";
	}
}