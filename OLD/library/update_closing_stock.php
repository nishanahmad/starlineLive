<?php
function update_closing_stock($post_date,$item,$godown,$qty)
{
	require 'connect.php';
	
	if(empty($post_date) || empty($item) || empty($godown) || empty($qty) )
	{
		die("Error...... Cannot update closing stock. Please contact administrator");
	}	
	
	$post_date = date('Y-m-d', strtotime($post_date));
	
	$latest_CS_query = mysqli_query($con,"SELECT date,closing_stock FROM closing_stock WHERE godown='$godown'
											AND item = '$item' 
											ORDER BY DATE DESC LIMIT 1  ") or die(mysqli_error($con));	
	$latest_CS_array = mysqli_fetch_array($latest_CS_query,MYSQLI_ASSOC);
	
	$latest_date = date('Y-m-d', strtotime($latest_CS_array['date']));
	$latest_CS = $latest_CS_array['closing_stock'];
	
	$previous_CS_query = mysqli_query($con,"SELECT * FROM closing_stock 
													WHERE item='$item' AND godown='$godown' AND date < '$post_date'	
													ORDER BY date DESC LIMIT 1") 
													or die(mysqli_error($con));
	if(mysqli_num_rows($previous_CS_query) == 0)
	{
		echo $post_date;echo '<br>';echo $godown;echo '<br>';echo $item;echo '<br>';echo $qty;echo '<br>';
//		exit('Entries before 4th April 2015 cannot be entered');
	}
	else
	{
		$previous_CS_array = mysqli_fetch_array($previous_CS_query,MYSQLI_ASSOC);
		$previous_date = date('Y-m-d', strtotime($previous_CS_array['date']));
		$previous_CS = $previous_CS_array['closing_stock'];		
	}	
	



	if($post_date == $latest_date)
	{
		$update_CS = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' 
										WHERE date = '$post_date' AND godown = '$godown' AND item = '$item'") 
										or die(mysqli_error($con));	
	}	
	
	else if($post_date > $latest_date)
	{
		$insert_CS = mysqli_query($con,"INSERT INTO closing_stock(date,item,godown,closing_stock) 
										VALUES('$post_date','$item','$godown','$latest_CS') ") 
										or die(mysqli_error($con));	
		$update_CS = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' 
										WHERE date = '$post_date' AND godown = '$godown' AND item = '$item'") 
										or die(mysqli_error($con));				
	}	


// Process the entries of previous dates 
	
	else if($post_date < $latest_date)
	{
// Check if the old date is already present in DB
		$match_query = mysqli_query($con,"SELECT date FROM closing_stock  
										  WHERE date = '$post_date' AND godown = '$godown' AND item = '$item'") 
										  or die(mysqli_error($con));
		
// If match found update all the values from that date to the latest date in DB
		if(mysqli_num_rows($match_query) > 0) 
		{
			$loop_date = $post_date;
			
		//	Updating the post date value		
			$update_CS = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' 
										WHERE date = '$post_date' AND godown = '$godown' AND item = '$item'") 
										or die(mysqli_error($con));			

		//	Updating all values between post date and latest date (latest date included) pesent in DB in a while loop									
			while($loop_date <= $latest_date)
			{
				$loop_date = date('Y-m-d', strtotime($loop_date . ' +1 day'));
				
				$update_CS = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' 
										WHERE date = '$loop_date' AND godown = '$godown' AND item = '$item'") 
										or die(mysqli_error($con));			
			}	
		}

// If no match found query for previuos date entry and insert post date with the same closing stock. 
// Then repeat the above while loop		
		else if(mysqli_num_rows($match_query) == 0)
		{
			$insert_CS = mysqli_query($con,"INSERT INTO closing_stock(date,item,godown,closing_stock) 
											VALUES('$post_date','$item','$godown','$previous_CS') ") 
											or die(mysqli_error($con));				
			
			$loop_date = $post_date;
			
		//	Updating the post date value		
			$update_CS = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' 
										WHERE date = '$post_date' AND godown = '$godown' AND item = '$item'") 
										or die(mysqli_error($con));			

		//	Updating all values between post date and latest date (latest date included) pesent in DB									
			while($loop_date <= $latest_date)
			{
				$loop_date = date('Y-m-d', strtotime($loop_date . ' +1 day'));
				
				$update_CS = mysqli_query($con,"UPDATE closing_stock SET closing_stock = closing_stock - '$qty' 
										WHERE date = '$loop_date' AND godown = '$godown' AND item = '$item'") 
										or die(mysqli_error($con));
			}							
		}	

	}	

}
//update_closing_stock('2015-04-09','43 HDPE','Manna',-5);
?>