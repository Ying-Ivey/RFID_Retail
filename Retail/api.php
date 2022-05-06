<?php
$host = "localhost";	
$user = "root"; 		//enter your own user name
$pass = "asd123!@#";						//enter your own password
$database = "retail_database_seminar";  	//enter your own database name

$conn = mysqli_connect($host, $user, $pass,$database) or die("Could not connect to host.");


$sth = mysqli_query($conn,"SELECT * FROM deliveryorder");
$rows = array();
while($r = mysqli_fetch_assoc($sth)) {
   $rows[] = $r;
}
print json_encode($rows);
?>