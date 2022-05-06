<?php

include 'DatabaseConfig.php' ;
 
 $con = mysqli_connect($HostName,$HostUser,$HostPass,$DatabaseName);
 
 $delivery_Order_id = $_POST['delivery_Order_id'];
 $delivery_Order_date = $_POST['delivery_Order_date'];
 $order_status = $_POST['order_status'];
 $expected_quantity = $_POST['expected_quantity'];
 $actual_quantity = $_POST['actual_quantity'];

 $Sql_Query = "insert into deliveryorder values ('$delivery_Order_id','$delivery_Order_date', '$order_status','$expected_quantity', '$actual_quantity')";
 
 if(mysqli_query($con,$Sql_Query)){
 
 echo 'Data Submit Successfully';
 
 }
 else{
 
 echo 'Try Again';
 
 }
 mysqli_close($con);
?>