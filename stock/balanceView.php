<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';
require '../library/sumClosingStock.php';

session_start();
if(isset($_SESSION["user_name"]))
{
	$itemsArray = array();
	$items = mysqli_query($con,"SELECT DISTINCT item FROM closing_stock ORDER BY item ") or die(mysqli_error($con));
	while($row = mysqli_fetch_array($items,MYSQLI_ASSOC))
	{
		$itemsArray[]= $row['item'];
	}
	
	if(isset($_GET['item']))
		$item = $_GET['item'];
	else
		$item = $itemsArray[0];
	
	if(isset($_GET['date']))	
		$date = date("Y-m-d", strtotime($_GET['date']));
	else
		$date = date("Y-m-d");
	 
	
	$to_give = 0;
	$invoicedQuery = mysqli_query($con,"SELECT * FROM stock_details WHERE item = '$item' AND invoice_date <= '$date' AND invoice_number IS NOT NULL") or die(mysqli_error($con));
	while($row = mysqli_fetch_array($invoicedQuery,MYSQLI_ASSOC))
		$to_give = $to_give + $row['qty'];
	
	$to_get = 0;
	$billedQuery = mysqli_query($con,"SELECT * FROM stock_details WHERE item = '$item' AND entry_date <= '$date' AND godown_slip_number IS NOT NULL") or die(mysqli_error($con));
	while($row = mysqli_fetch_array($billedQuery,MYSQLI_ASSOC))
		$to_get = $to_get + $row['qty'];
	
	
	$godownNames = mysqli_query($con,"SELECT DISTINCT godown FROM closing_stock ORDER BY godown ") or die(mysqli_error($con));
	$physical_stock = 0;
	while($row_godowns = mysqli_fetch_array($godownNames,MYSQLI_ASSOC))
	{
		$godown = $row_godowns['godown'];

		$result_stock = mysqli_query($con,"SELECT * FROM closing_stock WHERE item = '$item' AND date <= '$date' AND godown = '$godown' ORDER BY date DESC LIMIT 1") or die(mysqli_error($con));
		while($row = mysqli_fetch_array($result_stock,MYSQLI_ASSOC))
		{
			$physical_stock = $physical_stock + $row['closing_stock'];
			$balance_stock[$row['godown']] = $row['closing_stock'];
		}
	}

	$sap_stock = $physical_stock - $to_give + $to_get;
?>
	<html>
	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">

	<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
	<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
			.responstable {
			  margin: 1em 0;
			  width: 50%;
			  overflow: hidden;
			  background: #FFF;
			  color: #024457;
			  border-radius: 5px;
			  border: 1px solid #167F92;
			}
			.responstable tr {
			  border: 1px solid #D9E4E6;
			}
			.responstable tr:nth-child(odd) {
			  background-color: #EAF3F3;
			}
			.responstable th {
			  display: none;
			  border: 1px solid #FFF;
			  background-color: #167F92;
			  color: #FFF;
			  padding: 1em;
			  
			 }
			.responstable td {
			  display: block;
			  word-wrap: break-word;
			  max-width: 3em;
			}
			.responstable td:first-child {
			  display: table-cell;
			  border-right: 1px solid #D9E4E6;
			}
			@media (min-width: 480px) {
			  .responstable td {
				border: 1px solid #D9E4E6;
			  }
			}
			.responstable th, .responstable td {
			  text-align: left;
			  margin: .5em 1em;
			}
			@media (min-width: 480px) {
			  .responstable th, .responstable td {
				display: table-cell;
				padding: .2em;
			  }
			}
			
		</style>
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

		var item=document.getElementById("item").value;
		var hrf = window.location.href;
		hrf = hrf.slice(0,hrf.indexOf("?"));
		window.location.href = hrf +"?date="+ date + "&item=" + item;
	}
	</script>
	<div align="center">
						<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a><br>
	<h1>CLOSING STOCK<h1>
						<input type="text" id="datepicker" class="txtField" name="date" required value="<?php echo date("d-m-Y", strtotime($date));?>" onchange="return rerender();" />
						<select id="item" name="item" onchange="return rerender();">
						<?php
						foreach($itemsArray as $key => $value)
						{
	?>						<option <?php if($value == $item) echo 'Selected';?> value="<?php echo $value;?>"><?php echo $value;?></option><?php
						}
	?>						
						</select></br></br>
	<div style="font-weight:bold;font-size:20px">					
	PHYSICAL STOCK : <?php echo $physical_stock; ?></br><br>
	SAP STOCK : <?php echo $sap_stock;?></br>
	<div>
	<table class="responstable" style="width:300px !important;">
	<tr><th>Godown</th><th>Stock</th></tr>
	<?php
	foreach($balance_stock as $godown => $stock)
	{
	?><tr><td><?php echo $godown;?></td><td><?php echo $stock;?></td></tr><?php	
	}
	?>
	</table>

	<?php 
	/*			 							PARTY WISE BALANCE									*/

	$clientQtyMap = array();
	$total = 0;
	$to_get = mysqli_query($con,"SELECT SUM(qty),client FROM stock_details 
								 WHERE item = '$item' AND entry_date <= '$date' AND
								 godown_slip_number IS NOT NULL 
								 AND godown_slip_number NOT LIKE '%RTN%'
								 AND invoice_number IS NULL
								 GROUP BY client ") or die(mysqli_error($con));
	while($row = mysqli_fetch_array($to_get,MYSQLI_ASSOC))
	{
		if(isset($clientQtyMap[$row['client']]))
			$clientQtyMap[$row['client']] = $clientQtyMap[$row['client']] + $row['SUM(qty)'];
		else
			$clientQtyMap[$row['client']] = $row['SUM(qty)'];	
		
		$total = $total + $row['SUM(qty)'];	
	}

	$to_give = mysqli_query($con,"SELECT SUM(qty),client FROM stock_details 
								  WHERE item = '$item' AND invoice_date <= '$date' AND
								  godown_slip_number IS NULL 
								  AND invoice_number IS NOT NULL 
								  GROUP BY client ") or die(mysqli_error($con));
	while($row = mysqli_fetch_array($to_give,MYSQLI_ASSOC))
	{
		if(isset($clientQtyMap[$row['client']]))
			$clientQtyMap[$row['client']] = $clientQtyMap[$row['client']] - $row['SUM(qty)'];
		else
			$clientQtyMap[$row['client']] = -$row['SUM(qty)'];	
		
		$total = $total - $row['SUM(qty)'];	
	}

	$journalSlips = mysqli_query($con,"SELECT SUM(qty),client FROM journals 
									   WHERE item = '$item' AND date <= '$date' AND type = 'slip'
									   GROUP BY client ") or die(mysqli_error($con));
	while($row = mysqli_fetch_array($journalSlips,MYSQLI_ASSOC))
	{
		if(isset($clientQtyMap[$row['client']]))
			$clientQtyMap[$row['client']] = $clientQtyMap[$row['client']] + $row['SUM(qty)'];
		else
			$clientQtyMap[$row['client']] = $row['SUM(qty)'];	
		
		$total = $total + $row['SUM(qty)'];	
	}	
	
	$journalInvoices = mysqli_query($con,"SELECT SUM(qty),client FROM journals 
									   WHERE item = '$item' AND date <= '$date' AND type = 'invoice'
									   GROUP BY client ") or die(mysqli_error($con));
	while($row = mysqli_fetch_array($journalInvoices,MYSQLI_ASSOC))
	{
		if(isset($clientQtyMap[$row['client']]))
			$clientQtyMap[$row['client']] = $clientQtyMap[$row['client']] - $row['SUM(qty)'];
		else
			$clientQtyMap[$row['client']] = -$row['SUM(qty)'];	
		
		$total = $total - $row['SUM(qty)'];	
	}		
	?>
	<br>
	<table class="responstable" style="width:50%;">
	<tr><th>Physical Stock</th><th style="width:20%;text-align:center"><?php echo $physical_stock; ?></th></tr>																	<?php
	foreach($clientQtyMap as $client => $qty)
	{
		if($qty !=0)
		{																																										?>		
			<tr>
				<td width="60%;"><?php echo $client;?></td>
				<td style="text-align:center"><?php if($qty < 0 ) echo $qty.' to give'; if($qty > 0 ) echo $qty.' to bill';?></td>
			</tr>																																											<?php			
		}		
	}																																											?>
	<tr><th>SAP Stock</th><th style="width:20%;text-align:center"><?php echo $sap_stock; ?></th></tr>
	</table>
	</html>																																										<?php
}																																											?>