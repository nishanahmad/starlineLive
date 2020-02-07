<?php
	require 'connect.php';	
	require 'library/update_closing_stock.php';
		if(count($_POST)>0) 
		{
			$id = $_GET['id'];
			$entryDate = date("Y-m-d", strtotime($_POST['date']));
			$item = $_POST['item'];
			$qty = $_POST['qty'];
			$lorry = $_POST['lorry'];
			$driver = $_POST['driver'];
			$loading_time = $_POST['loading_time'];

			if(!empty($_POST['invoice_number']))
				$invoice_number = strtoupper ($_POST['invoice_number']);
			else
				$invoice_number = null;

			$invoice_date = date("Y-m-d", strtotime($_POST['invoice_date']));			
			
			if(!empty($_POST['godown_slip_number']))
				$godown_slip_number = strtoupper ($_POST['godown_slip_number']);
			else
				$godown_slip_number = null;
		
			$godown = $_POST['godown'];
			
			$stock_query = mysqli_query($con,"SELECT * FROM stock_details WHERE id='" . $_GET["id"] . "'") or die(mysqli_error($con));
			$stock = mysqli_fetch_array($stock_query,MYSQLI_ASSOC) or die(mysqli_error($con));

			
// IF GODOWN SLIP IS ENTERED THEN UPDATE STOCK
			if ($stock['godown_slip_number'] == ''  &&  $godown_slip_number != '')
			{
				update_closing_stock($entryDate,$item,$godown,$qty);
			}
						

// UPDATE THE EDITED FIELDS
			$update_query = mysqli_query($con,"UPDATE stock_details SET invoice_number=".var_export($invoice_number, true).",invoice_date='$invoice_date', 
													  godown_slip_number=".var_export($godown_slip_number, true).",godown='$godown', 
													  lorry = '$lorry', driver= '$driver' ,loading_time = '$loading_time'
												WHERE id='" . $_GET["id"] . "'") or die(mysqli_error($con));
									
			header("Location:view.php?id=$id");						
		}
?>		