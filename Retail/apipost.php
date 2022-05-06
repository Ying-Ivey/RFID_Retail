<?php
$host = "localhost";	
$user = "root"; 		//enter your own user name
$pass = "asd123!@#";						//enter your own password
$database = "retail_database_seminar";  	//enter your own database name

$conn = mysqli_connect($host, $user, $pass,$database) or die("Could not connect to host.");

// Escape user inputs for security
$delivery_Order_id = mysqli_real_escape_string($conn, $_REQUEST['delivery_Order_id']);
$delivery_Order_date = mysqli_real_escape_string($conn, $_REQUEST['delivery_Order_date']);
$order_status = mysqli_real_escape_string($conn, $_REQUEST['order_status']);
$expected_quantity = mysqli_real_escape_string($conn, $_REQUEST['expected_quantity']);
$actual_quantity = mysqli_real_escape_string($conn, $_REQUEST['actual_quantity']);

// attempt insert query execution
$sql = "INSERT INTO student (name, phone) VALUES ('$delivery_Order_id','$delivery_Order_date', '$order_status','$expected_quantity', '$actual_quantity')";
if(mysqli_query($conn, $sql)){
    echo "1"; //1 for success
} else{
    echo "0"; //0 for fail
}
// close connection
mysqli_close($conn);
?>
