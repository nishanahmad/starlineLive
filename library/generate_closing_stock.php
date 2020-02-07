<?php
	require '../connect.php';
	require 'array_push_assoc.php';
	
	$mainArray = array();
	$CS_query = mysqli_query($con,"SELECT * FROM closing_stock ORDER BY date asc") or die(mysqli_error($con));
	
	while($CS_row = mysqli_fetch_array($CS_query,MYSQLI_ASSOC))
	{
		$key = trim($CS_row['date']).'--'.trim($CS_row['godown']).'--'.trim($CS_row['item']);
		$mainArray = push($mainArray,$key,$CS_row['closing_stock']);
		
	}	

	$newest_CS_query = mysqli_query($con,"SELECT date FROM closing_stock ORDER BY date desc LIMIT 1") or die(mysqli_error($con));	
	$oldest_CS_query = mysqli_query($con,"SELECT date FROM closing_stock ORDER BY date asc LIMIT 1") or die(mysqli_error($con));
	
	$newest_CS_array = mysqli_fetch_array($newest_CS_query,MYSQLI_ASSOC);
	$oldest_CS_array = mysqli_fetch_array($oldest_CS_query,MYSQLI_ASSOC);
	
	$newest_date = date('Y-m-d', strtotime($newest_CS_array['date']));
	$oldest_date = date('Y-m-d', strtotime($oldest_CS_array['date']));
	
	$last_closing_stock = array();  // used to store the latest closing stock in the loop below 
	while($oldest_date <= $newest_date)
	{
		foreach($mainArray as $key => $closing_stock)
		{
			$arr = explode('--',$key);
			$date = trim($arr[0]);
			$godown = trim($arr[1]);
			$item = trim($arr[2]);
			
			$CS_query = mysqli_query($con,"SELECT * FROM closing_stock WHERE date='$oldest_date'
										   AND godown='$godown' AND item='$item' ") or die(mysqli_error($con));		
			if(mysqli_num_rows($CS_query) > 0)
			{
				$CS = mysqli_fetch_array($CS_query,MYSQLI_ASSOC);
				$key = trim($CS['godown']).'--'.trim($CS['item']);
				$last_closing_stock = push($last_closing_stock,$key,$CS['closing_stock']);
			}
			else
			{
				$key = $godown.'--'.$item;
				$closing_stock = $last_closing_stock[$key];
				
				$insert = mysqli_query($con,"INSERT INTO closing_stock(date,godown,item,closing_stock)
											 VALUES('$oldest_date','$godown','$item','$closing_stock')") or die(mysqli_error($con));		
			}	
			
		}
		$oldest_date = date('Y-m-d', strtotime($oldest_date . ' +1 day'));

	}
	//var_dump($last_closing_stock);	
?>	