<?php
function sumClosingStock($date)
{
	require 'connect.php';
	$date = date("Y-m-d", strtotime($date));
	
	$itemsArray = array();
	$items = mysqli_query($con,"SELECT DISTINCT item FROM closing_stock ORDER BY item ") or die(mysqli_error($con));
	while($row = mysqli_fetch_array($items,MYSQLI_ASSOC))
	{
		$itemsArray[]= $row['item'];
	}	
	
	$godownNames = mysqli_query($con,"SELECT DISTINCT godown FROM closing_stock ORDER BY godown ") or die(mysqli_error($con));
	$balance_stock = array();
	//$total_stock = 0;
	while($row_godowns = mysqli_fetch_array($godownNames,MYSQLI_ASSOC))	
	{
		$godown = $row_godowns['godown'];
		$balance_stock[$godown] = 0;
		foreach($itemsArray as $index => $item)
		{
			$stock_date = $date;			
			label:
				$result_stock = mysqli_query($con,"SELECT * FROM closing_stock WHERE item = '$item' AND date = '$stock_date' AND godown = '$godown' ") or die(mysqli_error($con));
				
				if(mysqli_num_rows($result_stock) == 0)
				{
					$stock_date = date("Y-m-d", strtotime('-1 day', strtotime($stock_date)));		
					goto label;
				}	
				while($row = mysqli_fetch_array($result_stock,MYSQLI_ASSOC))
				{
					//$total_stock = $total_stock + $row['closing_stock'];
					$balance_stock[$godown] = $balance_stock[$godown] + $row['closing_stock'];
				}	
		}		
	}	
	return $balance_stock;
}
//sumClosingStock("2017-06-30");
?>