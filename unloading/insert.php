<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
require '../library/update_closing_stock.php';

session_start();
if(isset($_SESSION["user_name"]))
{

	$entryDate = date("Y-m-d", strtotime($_POST['date']));
	$item = trim($_POST['item']);
	$qty = trim($_POST['qty']);
	$delivery_number = trim($_POST['delivery_number']);
	$invoice_number = trim($_POST['invoice_number']);
	$godown_slip_number = trim($_POST['godown_slip_number']);
	$lorry_number = trim($_POST['lorry_number']);
	$godown = trim($_POST['godown']);
	$entered_by = $_SESSION["user_name"];
	
	$insert = "INSERT INTO godown_entry (entry_date, item, qty, delivery_number, invoice_number, godown_slip_number, lorry_number, godown)
				VALUES
				('$entryDate',  '$item', '$qty', '$delivery_number', '$invoice_number', '$godown_slip_number', '$lorry_number', '$godown')";

	$result = mysqli_query($con, $insert) or die(mysqli_error($con));		 
	
	update_closing_stock($entryDate,$item,$godown,-$qty);
	
	header("Location:new.php");
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	
?> 