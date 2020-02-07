<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require '../connect.php';

session_start();
if(isset($_SESSION["user_name"]))
{
	$id = $_GET['id'];
	
	$delete = mysqli_query($con,"DELETE FROM journals WHERE id='$id' ") or die(mysqli_error($con));	

	header("Location:list.php");
								
}
else
header("Location:../index.php");
?>