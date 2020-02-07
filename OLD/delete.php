<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'connect.php';
require 'library/update_closing_stock.php';

session_start();
if(isset($_SESSION["user_name"]))
{
	$id = $_GET['id'];
	
	$query = mysqli_query($con,"SELECT entry_date,item,qty,godown,godown_slip_number FROM stock_details WHERE id='$id'") 
						  or die(mysqli_error($con));
	$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
	
	
	$result_last_date = mysqli_query($con,"SELECT date FROM last_transaction_date ") or die(mysqli_error($con));
	$row_last_date = mysqli_fetch_array($result_last_date,MYSQLI_ASSOC);
	$last_date = date("Y-m-d", strtotime($row_last_date['date']));
	
	$date = trim($row['entry_date']);
	if($date >= $last_date)
	{
		$item = trim($row['item']);
		$godown = trim($row['godown']);
		$qty = trim($row['qty']);
		$godown_slip_number = trim($row['godown_slip_number']);

		if (!empty($godown_slip_number))
		{
			update_closing_stock($date,$item,$godown,-$qty);
		}
			
		$delete = mysqli_query($con,"DELETE FROM stock_details WHERE id='$id' ") 
							or die(mysqli_error($con));	
	
		header("Location:list.php");
								
	}
	else
	{
		echo "ERROR : YOU CANNOT DELETE OLD DATA. PLEASE CHECK THE DATE OR CONTACT SYSTEM ADMINISTRATOR";
	}	
	
	
}
else
header("Location:loginPage.php");
?>