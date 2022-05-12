<?php

include "connect_android.php";

$array_rfid = isset($_POST['product_instance_id']) ? $_POST['product_instance_id'] : '';
$product_line_id = isset($_POST['product_line_id']) ? $_POST['product_line_id'] : '';
$array_product_instance_id = explode("-", $array_rfid);

if (empty($array_rfid) || empty($product_line_id)) {
	echo "Data cannot be empty";
} else {
	for ($x = 0; $x < sizeof($array_product_instance_id); $x++) {
		$query = mysqli_query($con, "INSERT INTO productinstancerfid VALUES('" . $array_product_instance_id[$x] . "','" . $product_line_id . "','0','')");
		$query2 = mysqli_query($con, "UPDATE productline SET stock = stock + 1 WHERE product_line_id ='" . $product_line_id . "'");

		if ($query && $query2) {
			$check = 1;
		} else {
			$check = 0;
		}
	}
	if ($check == 0) {
		echo "Error saving data";
	} else {
		echo "Data saved successfully";
	}
}