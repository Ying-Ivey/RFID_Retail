<?php

$servername = "localhost:3308";
$username = "root";
$password = "asd123!@#";
$db = "retail_database_seminar";

// Create connection
$con = mysqli_connect($servername, $username, $password, $db);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}