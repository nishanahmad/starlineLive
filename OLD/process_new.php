<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connect.php';
require 'library/update_closing_stock.php';

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
		$lorry = $_POST['lorry'];		
		$time = $_POST['loading_time'];		

		if(!empty($_POST['invoice_number']))
			$invoice_number = strtoupper ($_POST['invoice_number']);
		else
			$invoice_number = null;

		$invoice_date = date("Y-m-d", strtotime($_POST['invoice_date']));
		
		if(!empty($_POST['godown_slip_number']))
			$godown_slip_number = strtoupper ($_POST['godown_slip_number']);
		else
			$godown_slip_number = null;
		
		$driver = $_POST['driver'];
		
		$godown = $_POST['godown'];
		
	//	$entered_by = $_SESSION["user_name"];


		if (!empty($godown_slip_number))
		{
			update_closing_stock($entryDate,$item,$godown,$qty);
		}
							
		$sql="INSERT INTO stock_details (entry_date, client, item, qty, lorry, driver, loading_time, invoice_number, invoice_date , godown_slip_number,godown)
			 VALUES
			 ('$entryDate', '$client', '$item', '$qty', '$lorry', '$driver','$time', ".var_export($invoice_number, true).", '$invoice_date' ,".var_export($godown_slip_number, true).",'$godown')";

		$result = mysqli_query($con, $sql) or die(mysqli_error($con));	 

		$update_last_date = mysqli_query($con,"UPDATE last_transaction_date SET date='$entryDate' ");
		
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
