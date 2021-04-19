<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
require '../library/update_closing_stock.php';
include("../library/flash_messages.php");

session_start();
if(isset($_SESSION["user_name"]))
{
	$result_last_date = mysqli_query($con,"SELECT date FROM last_transaction_date ") or die(mysqli_error($con));
	$row_last_date = mysqli_fetch_array($result_last_date,MYSQLI_ASSOC);
	$last_date = date("Y-m-d", strtotime($row_last_date['date']));
	//echo $last_date;
	
	$entryDate = date("Y-m-d", strtotime($_POST['date']));
	if($entryDate >= $last_date)
	{	
		$client = $_POST['client'];
		$item = $_POST['item'];
		$qty = $_POST['qty'];
		$delivery_number = null;
		$invoice_number = null;

		if(!empty($_POST['godown_slip_number']))
		{
			$godown_slip_number = strtoupper ($_POST['godown_slip_number'].' RTN');			
		}	

		else
			$godown_slip_number = null;
		
		$godown = $_POST['godown'];
		

		if (!empty($godown_slip_number))
		{
			update_closing_stock($entryDate,$item,$godown,-$qty,$con);
		}
							
		$sql="INSERT INTO stock_details (entry_date, client, item, qty, delivery_number, invoice_number, godown_slip_number,godown)
			 VALUES
			 ('$entryDate', '$client', '$item', '$qty', ".var_export($delivery_number, true).", ".var_export($invoice_number, true).", ".var_export($godown_slip_number, true).",'$godown')";

		$result = mysqli_query($con, $sql) or die(mysqli_error($con));	 

		$update_last_date = mysqli_query($con,"UPDATE last_transaction_date SET date='$entryDate' ") or die(mysqli_error($con));
		

		header("Location:new.php");
	}
	else
	{
		echo "ERROR : YOU CANNOT ENTER OLD DATA. PLEASE CHECK THE DATE YOU HAVE ENTERED";
	}	
	
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	
?> 
