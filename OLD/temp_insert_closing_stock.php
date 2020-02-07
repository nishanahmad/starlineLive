<?php
require 'connect.php';
require 'library/array_push_assoc.php';
require 'library/temp_update_closing_stock.php';

	$loading_arr = array();
	$unloading_arr = array();
	
	$loading_query = mysqli_query($con,"SELECT entry_date,godown,item,qty FROM stock_details 
										WHERE (godown <> '' AND godown not like '%wagon%' AND entry_date < '2015-09-05')  
										ORDER BY `stock_details`.`entry_date` DESC ") 
										or die(mysqli_error($con));	
	while($row = mysqli_fetch_array($loading_query,MYSQLI_ASSOC))
	{
		$date = trim($row['entry_date']);
		$godown = trim($row['godown']);
		$item = trim($row['item']);
		$qty = trim($row['qty'])*1;
		$GI = $godown.'--'.$item;
		
		if(!isset($loading_arr[$date]))
		{
			$loading_arr[$date] = array();
			$loading_arr[$date] = push($loading_arr[$date],$GI,$qty); 
		}
		else
		{
			if(isset($loading_arr[$date][$GI]))
				$loading_arr[$date][$GI] = $loading_arr[$date][$GI] + $qty;
			else	
				$loading_arr[$date] = push($loading_arr[$date],$GI,$qty);
		}	
		
	}
	echo 'THE LOADING ARRAY IS .............................';	
	var_dump($loading_arr);
	
	$unloading_query = mysqli_query($con,"SELECT entry_date,godown,item,qty FROM godown_entry 
										  ORDER BY `godown_entry`.`entry_date` DESC ") 
										or die(mysqli_error($con));
	
	while($row = mysqli_fetch_array($unloading_query,MYSQLI_ASSOC))
	{
		$date = trim($row['entry_date']);
		$godown = trim($row['godown']);
		$item = trim($row['item']);
		$qty = trim($row['qty'])*1;
		$GI = $godown.'--'.$item;
		
		if(!isset($unloading_arr[$date]))
		{
			$unloading_arr[$date] = array();
			$unloading_arr[$date] = push($unloading_arr[$date],$GI,$qty); 
		}
		else
		{
			if(isset($unloading_arr[$date][$GI]))
				$unloading_arr[$date][$GI] = $unloading_arr[$date][$GI] + $qty;
			else	
				$unloading_arr[$date] = push($unloading_arr[$date],$GI,$qty);
		}	
		
	}
	echo 'THE UNLOADING ARRAY IS .............................';	
	var_dump($unloading_arr);									

	foreach($loading_arr as $date => $value_array)
	{
		foreach($value_array as $GI => $qty)
		{
			$arr = explode('--',$GI);
			$godown = trim($arr[0]);
			$item = trim($arr[1]);
			temp_update_closing_stock($date,$item,$godown,-$qty);
		}
	}
	
	foreach($unloading_arr as $date => $value_array)
	{
		foreach($value_array as $GI => $qty)
		{
			$arr = explode('--',$GI);
			$godown = trim($arr[0]);
			$item = trim($arr[1]);
			temp_update_closing_stock($date,$item,$godown,$qty);
		}
	}
	
?> 