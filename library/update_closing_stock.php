<?php
function update_closing_stock($date,$item,$godown,$qty,$con)
{	
	if(empty($date) || empty($item) || empty($godown) || empty($qty) )
	{
		die("Error...... Cannot update closing stock. Please contact administrator");
	}	
	
	$date = date('Y-m-d', strtotime($date));
	$timestamp = date('Y-m-d H:i:s');
	
	$date_query = mysqli_query($con,"SELECT date,closing_stock FROM closing_stock WHERE godown='$godown' AND item = '$item' AND date = '$date'") or die(mysqli_error($con.' at line 13'));	
	if(mysqli_num_rows($date_query) > 0)
	{
		$update = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' WHERE date >= '$date' AND godown = '$godown' AND item = '$item'") or die(mysqli_error($con.' at line 16'));	
		$insertLog = mysqli_query($con,"INSERT INTO stock_log(dateTime,item,godown,qty) VALUES('$timestamp','$item','$godown','$qty') ") or die(mysqli_error($con.' at line 18'));
	}
	else
	{
		$previous_date_query = mysqli_query($con,"SELECT closing_stock FROM closing_stock WHERE godown='$godown' AND item = '$item' AND date < '$date' ORDER BY date DESC LIMIT 1") or die(mysqli_error($con.' at line 20'));	
		if(mysqli_num_rows($previous_date_query) > 0)
			$previous_qty = (int)mysqli_fetch_row($previous_date_query)[0];
		else
			$previous_qty = 0;
		
		$insert = mysqli_query($con,"INSERT INTO closing_stock(date,item,godown,closing_stock) VALUES('$date','$item','$godown','$previous_qty') ") or die(mysqli_error($con.' at line 28'));
		$update = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' WHERE date >= '$date' AND godown = '$godown' AND item = '$item'") or die(mysqli_error($con. ' at line 27'));			
		
		$insertLog = mysqli_query($con,"INSERT INTO stock_log(dateTime,item,godown,qty) VALUES('$timestamp','$item','$godown','$qty') ") or die(mysqli_error($con.' at line 31'));
	}
}
?>