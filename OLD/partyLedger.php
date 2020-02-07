<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connect.php';

session_start();
if(isset($_SESSION["user_name"]))
{
	$clientArray = array();
	$clients = mysqli_query($con,"SELECT client_name FROM client_details ") or die(mysqli_error($con));
	while($row = mysqli_fetch_array($clients,MYSQLI_ASSOC))
	{
		$clientArray[]= $row['client_name'];
	}
	
	$itemsArray = array();
	$items = mysqli_query($con,"SELECT DISTINCT item FROM closing_stock ORDER BY item ") or die(mysqli_error($con));
	while($row = mysqli_fetch_array($items,MYSQLI_ASSOC))
	{
		$itemsArray[]= $row['item'];
	}
	
	if(isset($_GET['client']))
		$client = $_GET['client'];
	else
		$client = $clientArray[0];
	
	
	if(isset($_GET['fromDate']))	
		$fromDate = date("Y-m-d", strtotime($_GET['fromDate']));
	else
	{
		$month_ini = new DateTime("first day of last month");
		$fromDate = $month_ini -> format('Y-m-d');
	}	

	
	if(isset($_GET['toDate']))		
		$toDate = date("Y-m-d", strtotime($_GET['toDate']));		
	else
		$toDate = date("Y-m-d");

	$searchArray = array();
	$searchArray['client'] = $client;
	$searchArray['fromDate'] = $fromDate;
	$searchArray['toDate'] = $toDate;
	
	$fp = fopen('library/searchArray.json', 'w');
	fwrite($fp, json_encode($searchArray));
	fclose($fp);		
}
?>
<html>
<link rel="stylesheet" type="text/css" href="css/glow_box.css">
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/dataTables.responsive.css">
<script type="text/javascript" language="javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="js/dataTables.jqueryui.js"></script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/1.10.15/sorting/natural.js"></script>
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

		tfoot input {
			width: 100%;
			padding: 3px;
			box-sizing: border-box;
		}		
	</style>
<script>
$(window).on('load', function() {
$("#loader").hide();

var pickerOpts = { dateFormat:"yy-mm-dd"}; 
	    	
$( "#datepicker" ).datepicker(pickerOpts);
$( "#datepicker2" ).datepicker(pickerOpts);

});
</script>
<script type="text/javascript">
function rerender()
{
	var fromDate = document.getElementById("datepicker").value;
	var toDate = document.getElementById("datepicker2").value;

	var client= encodeURIComponent(document.getElementById("client").value);
	
	$("#main").hide();
	$("#loader").show();	
	
	var hrf = window.location.href;
	hrf = hrf.slice(0,hrf.indexOf("?"));
	window.location.href = hrf +"?fromDate="+ fromDate + "&toDate=" + toDate + "&client=" + client;
}
</script>
<script type="text/javascript" language="javascript" >
			$(document).ready(function() {
				var dataTable = $('#employee-grid').DataTable( {
					"processing": true,
					"serverSide": true,
					"bJQueryUI":true,
					"iDisplayLength": 2000,
					"aaSorting" : [[0, 'desc']],
					"ajax":{
						url :"library/partyLedger.php", // json datasource
						type: "post",  // method  , by default get
						error: function(){  
							$(".employee-grid-error").html("");
							$("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#employee-grid_processing").css("display","none");
										}
						   }
				} );
				
				$("#employee-grid_filter").css("display","none");  // hiding global search box
				$('.search-input-text').on( 'keyup click', function () {   // for text boxes
					var i =$(this).attr('data-column');  // getting column index
					var v =$(this).val();  // getting search input value
					dataTable.columns(i).search(v).draw();
				} );
				$('.search-input-select').on( 'change', function () {   // for select box
					var i =$(this).attr('data-column');  
					var v =$(this).val();  
					dataTable.columns(i).search(v).draw();
				} );
				
			} );
</script>
<div id="loader" class="loader" align="center" style="background : #161616 url('../images/pattern_40.gif') top left repeat;height:100%">
<br><br><br><br><br><br><br><br><br><br><br><br>
<div class="circle"></div>
<div class="circle1"></div>
<br>
<font style="color:white;font-weight:bold">Loading......</font>
</div>
<div align="center">
					<a href="index.php" class="link"><img alt='home' title='home' src='images/home.png' width='60px' height='60px'/> </a><br><br><br>
					<select id="client" data-column="5" name="client" onchange="return rerender();">
					<?php
					foreach($clientArray as $key => $value)
					{
?>						<option <?php if($value == $client) echo 'Selected';?> value="<?php echo $value;?>"><?php echo $value;?></option><?php
					}
?>						
					</select>
					</br></br></br>
					<table>
					<tr>
					<td>From :</td><td><input type="text" data-column="2" id="datepicker" class="txtField" name="fromDate" class="search-input-text textarea" value="<?php echo $fromDate ?>" onchange="return rerender();" /></td></tr>
					<td>To:</td><td><input type="text" id="datepicker2" class="txtField" name="toDate" value="<?php echo $toDate ?>" onchange="return rerender();" /></td>					
					</tr>
					</table>
					</br>
<?php 
/*			 							PARTY WISE BALANCE									*/

$mainArray = array();   //  client => opening,to get, to give

// Calculate first day of previous month

$to_get = mysqli_query($con,"SELECT SUM(qty),item FROM stock_details 
							 WHERE entry_date >= '$fromDate' AND entry_date <= '$toDate' 
							 AND invoice_number IS NULL 
							 AND godown_slip_number IS NOT NULL 
							 AND godown_slip_number NOT LIKE '%RTN%' 
							 AND client = '$client' GROUP BY item ") or die(mysqli_error($con));
while($row = mysqli_fetch_array($to_get,MYSQLI_ASSOC))
{
	//var_dump($row);
	if(isset($mainArray[$row['item']]))
	{
		$mainArray[$row['item']]['to get'] = $mainArray[$row['item']]['to get'] + $row['SUM(qty)'];
	}
	else
	{
		$mainArray[$row['item']]['opening'] = 0;
		$mainArray[$row['item']]['to get'] = $row['SUM(qty)'];
		$mainArray[$row['item']]['to give'] = 0;
		$mainArray[$row['item']]['taken'] = 0;
		$mainArray[$row['item']]['billed'] = 0;
	}		
}

$to_give = mysqli_query($con,"SELECT SUM(qty),item FROM stock_details 
							  WHERE entry_date >= '$fromDate' AND entry_date <= '$toDate' 
							  AND godown_slip_number IS NULL 
							  AND invoice_number IS NOT NULL 
							  AND client = '$client' GROUP BY item ") or die(mysqli_error($con));
while($row = mysqli_fetch_array($to_give,MYSQLI_ASSOC))
{
	//var_dump($row);
	if(isset($mainArray[$row['item']]))
	{
		$mainArray[$row['item']]['to give'] = $mainArray[$row['item']]['to give'] + $row['SUM(qty)'];
	}
	else
	{
		$mainArray[$row['item']]['opening'] = 0;
		$mainArray[$row['item']]['to get'] =  0;
		$mainArray[$row['item']]['to give'] = $row['SUM(qty)'];
		$mainArray[$row['item']]['taken'] = 0;
		$mainArray[$row['item']]['billed'] = 0;		
	}		
}

$taken = mysqli_query($con,"SELECT SUM(qty),item FROM stock_details 
							WHERE entry_date >= '$fromDate' AND entry_date <= '$toDate' 
							AND godown_slip_number IS NOT NULL
							AND godown_slip_number NOT LIKE '%RTN%' 							
							AND client = '$client' GROUP BY item ") or die(mysqli_error($con));
while($row = mysqli_fetch_array($taken,MYSQLI_ASSOC))
{
	//var_dump($row);
	if(isset($mainArray[$row['item']]))
	{
		$mainArray[$row['item']]['taken'] = $mainArray[$row['item']]['taken'] + $row['SUM(qty)'];
	}
	else
	{
		$mainArray[$row['item']]['opening'] = 0;
		$mainArray[$row['item']]['to get'] =  0;
		$mainArray[$row['item']]['to give'] = 0;
		$mainArray[$row['item']]['taken'] = $row['SUM(qty)'];
		$mainArray[$row['item']]['billed'] = 0;		
	}		
}

$billed = mysqli_query($con,"SELECT SUM(qty),item FROM stock_details 
							 WHERE entry_date >= '$fromDate' AND entry_date <= '$toDate' 
							 AND invoice_number IS NOT NULL 
							 AND client = '$client' GROUP BY item ") or die(mysqli_error($con));
while($row = mysqli_fetch_array($billed,MYSQLI_ASSOC))
{
	//var_dump($row);
	if(isset($mainArray[$row['item']]))
	{
		$mainArray[$row['item']]['billed'] = $mainArray[$row['item']]['billed'] + $row['SUM(qty)'];
	}
	else
	{
		$mainArray[$row['item']]['opening'] = 0;
		$mainArray[$row['item']]['to get'] =  0;
		$mainArray[$row['item']]['to give'] = 0;
		$mainArray[$row['item']]['taken'] = 0;
		$mainArray[$row['item']]['billed'] = $row['SUM(qty)'];		
	}		
}

$opening1 = mysqli_query($con,"SELECT SUM(qty),item FROM stock_details WHERE entry_date < '$fromDate'	
							   AND	godown_slip_number IS NULL 
							   AND invoice_number IS NOT NULL
							   AND client = '$client' GROUP BY item ") or die(mysqli_error($con));
while($row = mysqli_fetch_array($opening1,MYSQLI_ASSOC))
{
	//var_dump($row);
	if(isset($mainArray[$row['item']]))
	{
		$mainArray[$row['item']]['opening'] = $mainArray[$row['item']]['opening'] - $row['SUM(qty)'];
	}
	else
	{
		$mainArray[$row['item']]['opening'] =  -$row['SUM(qty)'];
		$mainArray[$row['item']]['to get'] =  0;
		$mainArray[$row['item']]['to give'] = 0;
		$mainArray[$row['item']]['taken'] = 0;
		$mainArray[$row['item']]['billed'] = 0;				
	}		
}

$opening2 = mysqli_query($con,"SELECT SUM(qty),item FROM stock_details 
							   WHERE entry_date < '$fromDate'	
							   AND invoice_number IS NULL 
							   AND godown_slip_number IS NOT NULL 
							   AND godown_slip_number NOT LIKE '%RTN%' 
							   AND client = '$client' GROUP BY item ") or die(mysqli_error($con));
while($row = mysqli_fetch_array($opening2,MYSQLI_ASSOC))
{
	//var_dump($row);
	if(isset($mainArray[$row['item']]))
	{
		$mainArray[$row['item']]['opening'] = $mainArray[$row['item']]['opening'] + $row['SUM(qty)'];
	}
	else
	{
		$mainArray[$row['item']]['opening'] =  + $row['SUM(qty)'];
		$mainArray[$row['item']]['to get'] =  0;
		$mainArray[$row['item']]['to give'] = 0;
		$mainArray[$row['item']]['taken'] = 0;
		$mainArray[$row['item']]['billed'] = 0;				
	}		
}
?>
<br>
<table class="responstable">
<tr><th>Item</th><th>Opening <?php echo '( '. date("d-m-Y", strtotime($fromDate)) . ' )' ;?></th><th>To Deliver</th><th>To Receive</th><th>Taken</th><th>Billed</th><th>Total</th></tr>
<?php
$total_opening = 0;
$total_to_give = 0;
$total_to_get = 0;
$total_taken = 0;
$total_billed = 0;
$total_balance = 0;

foreach($mainArray as $item => $subArray)
{
		$total_opening = $total_opening + $subArray['opening'];
		$total_to_give = $total_to_give - $subArray['to give'];
		$total_to_get = $total_to_get + $subArray['to get'];
		$total_taken = $total_taken + $subArray['taken'];
		$total_billed = $total_billed - $subArray['billed'];
		$total_balance = $total_balance + $subArray['opening'] + $subArray['to get'] - $subArray['to give'];
?><tr>
		<td width="25%;"><?php echo $item;?></td>
		<td><?php echo $subArray['opening'];?></td>
		<td><?php echo -$subArray['to give'];?></td>
		<td><?php echo $subArray['to get'];?></td>
		<td><?php echo $subArray['taken'];?></td>
		<td><?php echo -$subArray['billed'];?></td>
		<td><?php echo $subArray['opening'] + $subArray['to get'] - $subArray['to give'];?></td>		
  </tr>
<?php	
}
?>
<tr style="font-weight:bold;font-size:20px">
<td>Total</td>
<td><?php echo $total_opening;?></td>
<td><?php echo $total_to_give;?></td>
<td><?php echo $total_to_get;?></td>
<td><?php echo $total_taken;?></td>
<td><?php echo $total_billed;?></td>
<td><?php echo $total_balance;?></td>
</tr>
</table>
<h2><?php echo $client;?></h2>
<!--------------------				FULL LIST OF SALES			-----------------------------------------------> 
			<table id="employee-grid" class="display cell-border  no-wrap">
					<thead>
						<tr>
							<th>Id</th>
							<th style="width:70px;">Slip Number</th>							
							<th>Date</th>
							<th style="width:60px;">Time</th>
							<th>Lorry</th>
							<th>Client</th>
							<th>Item</th>
							<th>Qty</th>
							<th style="width:70px;">Delivery Number</th>							
							<th style="width:70px;">Invoice Number</th>							
							<th style="width:100px;">Godown</th>
						</tr>
					</thead>
			</table>
</html>