<?php
	require '../connect.php';	
	require '../library/update_closing_stock.php';
	date_default_timezone_set("Asia/Kolkata");
		if(count($_POST)>0) 
		{
			$id = $_GET['id'];
			$oldQuery = mysqli_query($con,"SELECT * FROM stock_details WHERE id='" . $_GET["id"] . "'") or die(mysqli_error($con));
			$oldEntry = mysqli_fetch_array($oldQuery,MYSQLI_ASSOC) or die(mysqli_error($con));
			$entryDate = date("Y-m-d", strtotime($_POST['date']));
			$item = $_POST['item'];
			$qty = $_POST['qty'];
			$lorry = $_POST['lorry'];
			$driver = $_POST['driver'];

			if(!empty($_POST['invoice_number']))
			{
				$invoice_number = strtoupper ($_POST['invoice_number']);
				$invoice_date = date("Y-m-d", strtotime($_POST['invoice_date']));			
			}
			else
			{
				$invoice_number = null;
				$invoice_date = null;			
			}
			
			if(!empty($_POST['godown_slip_number']))
			{
				$godown_slip_number = strtoupper ($_POST['godown_slip_number']);
				$godown = $_POST['godown'];
				$oldTime = $oldEntry['loading_time'];
				if($oldTime == null)
					$time = date("H:i:s");
				else 
					$time = $oldTime;
			}
			else
			{
				$godown_slip_number = null;
				$godown = null;		
				$time = null;	
			}
			
			$stock_query = mysqli_query($con,"SELECT * FROM stock_details WHERE id='" . $_GET["id"] . "'") or die(mysqli_error($con));
			$stock = mysqli_fetch_array($stock_query,MYSQLI_ASSOC) or die(mysqli_error($con));

			
// IF GODOWN SLIP IS ENTERED THEN UPDATE STOCK
			$oldGodownQuery = mysqli_query($con,"SELECT godown,godown_slip_number FROM stock_details WHERE id='" . $_GET["id"] . "'") or die(mysqli_error($con));
			foreach($oldGodownQuery as $row)
			{
				$oldGodown = $row['godown'];
				$oldSlip = $row['godown_slip_number'];
			}
						
// UPDATE THE EDITED FIELDS
			$update_query = mysqli_query($con,"UPDATE stock_details SET invoice_number=".var_export($invoice_number, true).",invoice_date=".var_export($invoice_date, true).", 
													  godown_slip_number=".var_export($godown_slip_number, true).",godown='$godown', 
													  lorry = '$lorry', driver= '$driver', loading_time=".var_export($time, true)."
											   WHERE id='" . $_GET["id"] . "'") or die(mysqli_error($con));
									
			if ($oldGodown == null && $oldSlip == null && $godown_slip_number != null && $godown != null)
			{
				update_closing_stock($entryDate,$item,$godown,$qty);
			}									
									
			header("Location:view.php?id=$id");						
		}
?>		