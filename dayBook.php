<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connect.php';

session_start();
if(isset($_SESSION["user_name"]))
{	
	if(isset($_GET['date']))	
		$date = date("Y-m-d", strtotime($_GET['date']));
	else
		$date = date("Y-m-d");
	
	$invoiced = mysqli_query($con,"SELECT * FROM stock_details WHERE invoice_date = '$date' ") or die(mysqli_error($con));
	$sale = mysqli_query($con,"SELECT * FROM stock_details WHERE entry_date = '$date' AND godown_slip_number IS NOT NULL ") or die(mysqli_error($con));
	$logs = mysqli_query($con,"SELECT * FROM transfer_log WHERE date = '$date'") or die(mysqli_error($con));	
	$unloading = mysqli_query($con,"SELECT * FROM godown_entry WHERE entry_date = '$date'") or die(mysqli_error($con));	
?>
<html>
<style>
.table{
	border:1px solid black;	
	
	border-collapse: collapse;
}
.table tr,th{
	border:1px solid black;	
	padding: 10px;	
}
.table td{
	border:1px solid black;
	padding: 5px;	
}
</style>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">

<script type="text/javascript" language="javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery-ui.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>
	$(function() {

	var pickerOpts = { dateFormat:"yy-mm-dd"}; 
				
	$( "#datepicker" ).datepicker(pickerOpts);

	});
	</script>
	<script type="text/javascript">
	function rerender()
	{
		var date = document.getElementById("datepicker").value;

		var hrf = window.location.href;
		hrf = hrf.slice(0,hrf.indexOf("?"));
		window.location.href = hrf +"?date="+ date;
	}
</script>
<div align="center">
					<a href="index.php" class="link"><img alt='home' title='home' src='images/home.png' width='60px' height='60px'/> </a><br>
<h1>Day Book<h1>

<input type="text" id="datepicker" class="txtField" name="date" required value="<?php echo date("d-m-Y", strtotime($date));?>" onchange="return rerender();" />
<br/><br/>

<table class="table" style="float:left;margin-left:15%;">
<caption>INVOICE ENTRIES</caption>
<tr style="background-color: #ffad99"><th>Invoice No.</th><th>Party</th><th>Item</th><th>Qty</th></tr>
<?php
foreach($invoiced as $row)
{																																										?>
	<tr>
		<td><?php echo $row['invoice_number'];?></td>
		<td><?php echo $row['client'];?></td>
		<td><?php echo $row['item'];?></td>
		<td><?php echo $row['qty'];?></td>
	</tr>																																									<?php	
}
																																										?>
</table>
<table class="table" style="float:right;margin-right:15%;">
<caption>LOADING</caption>
<tr style="background-color: #c1f0c1"><th>Slip No.</th><th>Party</th><th>Item</th><th>Qty</th><th>Godown</th></tr>														<?php
foreach($sale as $row)
{																																										?>
	<tr>
		<td><?php echo $row['godown_slip_number'];?></td>
		<td><?php echo $row['client'];?></td>
		<td><?php echo $row['item'];?></td>
		<td><?php echo $row['qty'];?></td>
		<td><?php echo $row['godown'];?></td>
	</tr>																																									<?php	
}																																										?>
</table>
<br/><br/>
<table class="table" width="35%" style="margin-right:10%;">
<caption>UNLOADING</caption>
<tr style="background-color: #c1f0c1"><th>Item</th><th>Qty</th><th>From</th><th>To</th></tr>															<?php
foreach($logs as $row)
{																																										?>
	<tr>
		<td><?php echo $row['item'];?></td>
		<td><?php echo $row['qty'];?></td>
		<td><?php echo $row['transferred_from'];?></td>
		<td><?php echo $row['transferred_to'];?></td>
	</tr>																																									<?php	
}
foreach($unloading as $row)
{																																										?>
	<tr>
		<td><?php echo $row['item'];?></td>
		<td><?php echo $row['qty'];?></td>
		<td>LORRY/WAGON</td>
		<td><?php echo $row['godown'];?></td>
	</tr>																																									<?php	
}																																																																																				
?>
</table>
</div>
</html>																																										<?php
}																																											?>