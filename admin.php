<!DOCTYPE html>
<?php
session_start();
if($_SESSION["user_name"] == 'admin')
{
require 'connect.php';

if($_POST)
{
	$date = $_POST["date"];
	$update = mysqli_query($con,"UPDATE last_transaction_date SET date = '$date'") or die(mysqli_error($con));	
}
?>

<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<div align="center">
	<br/><br/><br/><br/>
	<form method="post" action="">
		<input type="date" name="date"/>
		<button type="submit">Submit</button>
	</form>
</div>	
</html>
<?php
}
else
	header("Location:loginPage.php");
?>