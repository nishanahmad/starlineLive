<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../connect.php';

session_start();
if(isset($_SESSION["user_name"]))
{
	$godownsArray = array();
	$godowns = mysqli_query($con,"SELECT DISTINCT godown FROM closing_stock ORDER BY godown ") or die(mysqli_error($con));
	while($row = mysqli_fetch_array($godowns,MYSQLI_ASSOC))
	{
		$godownsArray[]= $row['godown'];
	}
	
	if(isset($_GET['godown']))
		$godown = $_GET['godown'];
	else
		$godown = $godownsArray[0];
	if(isset($_GET['date']))	
		$date = date("Y-m-d", strtotime($_GET['date']));
	else
		$date = date("Y-m-d");
	
	$result = mysqli_query($con,"SELECT * FROM stock_details WHERE godown = '$godown' AND entry_date <= '$date' AND (invoice_number IS NULL OR godown_slip_number IS NULL)") or die(mysqli_error($con));
	
	$to_give = 0;
	$to_get = 0;
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
	{
		if ($row["invoice_number"] == null)
			$to_get = $to_get + $row['qty'];
		if ($row["godown_slip_number"] == null)
			$to_give = $to_give + $row['qty'];
	}	
	//echo $to_give;
	
// Calculate physical stock 
	
	$itemNames = mysqli_query($con,"SELECT DISTINCT item FROM closing_stock ORDER BY item ") or die(mysqli_error($con));
	$total_stock = 0;
 	while($row_items = mysqli_fetch_array($itemNames,MYSQLI_ASSOC))
	{
		$stock_date = $date;
		$item = $row_items['item'];
		label: 
		$result_stock = mysqli_query($con,"SELECT * FROM closing_stock WHERE item = '$item' AND date = '$stock_date' AND godown = '$godown' ") or die(mysqli_error($con));
		if(mysqli_num_rows($result_stock) == 0)
		{
			$stock_date = date("Y-m-d", strtotime('-1 day', strtotime($stock_date)));		
			goto label;
		}	
		while($row = mysqli_fetch_array($result_stock,MYSQLI_ASSOC))
		{
			$total_stock = $total_stock + $row['closing_stock'];
			$balance_stock[$row['item']] = $row['closing_stock'];
		} 
	}	
	$total = $total_stock - $to_give + $to_get; 
}
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

	var godown=document.getElementById("godown").value;
	var hrf = window.location.href;
	hrf = hrf.slice(0,hrf.indexOf("?"));
	window.location.href = hrf +"?date="+ date + "&godown=" + godown;
}
</script>
<div align="center">
	<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a>
	<br>
	<h2>Godown Closing Stock</h2>					
	<br><br>
	<input type="text" id="datepicker" class="txtField" name="date" required value="<?php echo date("Y-m-d", strtotime($date));?>" onchange="return rerender();" />
	<select id="godown" name="godown" onchange="return rerender();">
	<?php
					foreach($godownsArray as $key => $value)
					{
?>						<option <?php if($value == $godown) echo 'Selected';?> value="<?php echo $value;?>"><?php echo $value;?></option><?php
					}
?>						
					</select></br><br><br>
<div style="font-weight:bold;font-size:20px">					
<div>
<table class="responstable" style="width:300px !important;">
<tr><th>Item</th><th>Stock</th></tr>
<?php
foreach($balance_stock as $item => $stock)
{
?><tr><td><?php echo $item;?></td><td><?php echo $stock;?></td></tr><?php	
}
?>
</table>
TOTAL : <?php echo $total_stock; ?>
</html>