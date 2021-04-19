<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
require '../library/update_closing_stock.php';

session_start();
if(isset($_SESSION["user_name"]))
{
	
	$entryDate = date("Y-m-d", strtotime($_POST['date']));

	$item = $_POST['item'];
	$qty = $_POST['qty'];
	
	$godown = $_POST['godown'];
	

	update_closing_stock($entryDate,$item,$godown,$qty,$con);
						
	$sql="INSERT INTO standardization (entry_date, item, qty, godown)
		 VALUES
		 ('$entryDate', '$item', '$qty', '$godown')";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));	 
	
	header("Location:new.php");	
	
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	
?> 
