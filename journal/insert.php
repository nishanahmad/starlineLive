<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';

session_start();
if(isset($_SESSION["user_name"]))
{
	var_dump($_POST);
	$date = date("Y-m-d", strtotime($_POST['date']));
	$client = trim($_POST['client']);
	$item = trim($_POST['item']);
	$qty = (int)trim($_POST['qty']);
	$type = trim($_POST['type']);
	$remarks = trim($_POST['remarks']);
	
	$insert = "INSERT INTO journals (date, client, item, qty, type, remarks)
				VALUES
				('$date', '$client', '$item', $qty, '$type', '$remarks')";

	$result = mysqli_query($con, $insert) or die(mysqli_error($con));		 
	
	header("Location:list.php");
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	
?> 