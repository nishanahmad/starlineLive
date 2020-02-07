<?php
//ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
$message="";
if(count($_POST)>0) {
require 'connect.php';

$result = mysqli_query($con,"SELECT * FROM users WHERE user_name='" . $_POST["user_name"] . "' and password = '". $_POST["password"]."'");
if ( false===$result ) 
{
  printf("error: %s\n", mysqli_error($con));
}



$row  = mysqli_fetch_array($result, MYSQLI_ASSOC);
if(is_array($row)) {
$_SESSION["user_id"] = $row['user_id'];
$_SESSION["user_name"] = $row['user_name'];
} else {
echo "WRONG PASSWORD";
}
}
if(isset($_SESSION["user_id"])) {
header("Location:index.php");
}
?>