<?php
function temp_update_closing_stock($post_date,$item,$godown,$qty)
{
	require 'connect.php';
	
	$post_date = date('Y-m-d', strtotime($post_date));
	
	$latest_CS_query = mysqli_query($con,"SELECT date,closing_stock FROM closing_stock WHERE godown='$godown'
										  AND item = '$item' 
										  ORDER BY DATE DESC LIMIT 1  ") or die(mysqli_error($con));	
	$latest_CS_array = mysqli_fetch_array($latest_CS_query,MYSQLI_ASSOC);
	
	$latest_date = date('Y-m-d', strtotime($latest_CS_array['date']));
	$latest_CS = $latest_CS_array['closing_stock'];

	$insert_CS = mysqli_query($con,"INSERT INTO closing_stock(date,item,godown,closing_stock) 
									VALUES('$post_date','$item','$godown','$latest_CS') ") 
									or die(mysqli_error($con));				
			
	$update_CS = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' 
									WHERE date = '$post_date' AND godown = '$godown' AND item = '$item'") 
									or die(mysqli_error($con));			
}	


?>