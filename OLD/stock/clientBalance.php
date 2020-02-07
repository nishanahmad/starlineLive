<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';

session_start();
if(isset($_SESSION["user_name"]))
{
?>
<html>
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">

<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>

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
<div align="center">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a><br>
<h1>PARTY BALANCE<h1>
<?php 
$clientQtyMap = array();
$total = 0;
$to_get = mysqli_query($con,"SELECT SUM(qty),client FROM stock_details 
							 WHERE  
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
							  WHERE
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
?>
<table class="responstable" style="width:40%;">
<tr><th>Client</th><th>Balance</th></tr>
<?php
foreach($clientQtyMap as $client => $qty)
{
	if($qty !=0)
	{
?>		<tr>
			<td width="60%;"><?php echo $client;?></td>
			<td><?php echo $qty;?></td>
		</tr>
<?php			
	}		
}
?>
<tr style="font-weight:bold;font-size:20px">
<td>Total</td>
<td><?php echo $total;?></td>
</tr>
</table>
</html>
<?php
}
?>