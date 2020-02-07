<?php
session_start();
if(isset($_SESSION["user_name"]))
{
echo "LOGGED USER : ".$_SESSION["user_name"] ;	

require 'connect.php';

	if($_GET['godown'] == 'all')
	{
		$result = mysqli_query($con,"SELECT * FROM stock_details where delivery_number='NIL' or godown_slip_number='NIL' order by client asc") or die(mysqli_error($con));
		
	}
	else	
	{
		$result = mysqli_query($con,"SELECT * FROM stock_details where godown='".$_GET['godown']."' and (delivery_number='NIL' or godown_slip_number='NIL') order by client asc") or die(mysqli_error($con));
	}	
	
		$to_bill = 0;
		$to_deliver = 0;
		$mainarray = array();
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			//var_dump($row);
			if($row['godown_slip_number'] != 'NIL' && $row['delivery_number'] == 'NIL')
			{
				if(isset($mainarray[$row['client']][$row['item']]['To bill']))
				{
					
					$mainarray[$row['client']][$row['item']]['To bill'] = $mainarray[$row['client']][$row['item']]['To bill'] + $row['qty'];
					
				}
				else
				{
					$mainarray[$row['client']][$row['item']]['To bill'] =  $row['qty']*1;
					
				}		
			}
			
			if($row['godown_slip_number'] == 'NIL' && $row['delivery_number'] != 'NIL')
			{
				if(isset($mainarray[$row['client']][$row['item']]['To deliver']))
				{
					
					$mainarray[$row['client']][$row['item']]['To deliver'] = $mainarray[$row['client']]['To deliver'] + $row['qty'];
					
				}
				else
				{
					$mainarray[$row['client']][$row['item']]['To deliver'] =  $row['qty'];
					
				}		
			}
		}
		
		foreach($mainarray as $client => $subarray1)
		{
			$total_to_bill = 0;
			$total_to_deliver = 0;
			foreach($subarray1 as $item => $subarray2)
			{
				foreach($subarray2 as $type => $qty)
				{
					if($type == 'To bill')
						$total_to_bill = $total_to_bill + $qty;
					else
						$total_to_deliver = $total_to_deliver + $qty;
				}
			}
			$mainarray[$client]['Total(To bill)'] = $total_to_bill;
			$mainarray[$client]['Total(To deliver)'] = $total_to_deliver;
		}
		var_dump($mainarray);
?>
<html>
<head>
<style>
tfoot {
    display: table-header-group;
}
</style>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/responsive/1.0.6/css/dataTables.responsive.css"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/responsive/1.0.6/js/dataTables.responsive.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.7/api/sum().js"></script>
<script>

$(document).ready(function() {
// Setup - add a text input to each footer cell
$('#datatables').dataTable({
"scrollCollapse": true,
"paging": false,
"responsive": true,
"bJQueryUI":true,
"fnDrawCallback": function( oSettings ) {
      
    }
 });
 
var table = $('#datatables').DataTable(); 
 
	$('#datatables tfoot th').each( function () {
        var title = $('#datatables thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );


	
	var sum = table.column( 4, {filter:'applied'} ).data().reduce( function ( a, b ) {
	return a*1 + b*1;} );
	    $('.results').html(sum);

		

    // DataTable 
     // Apply the search
    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            that
                .search( this.value )
                .draw();

        } );
    } );
} );
</script>
<title>Client List</title>
</head>
<body>
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="index.php" class="link"><img alt='home' title='home' src='images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
</div>
<br>

<div align="center"><select name="godown" id="godown" onchange="document.location.href = 'client_list.php?godown=' + this.value" class="txtField"></div>
<option value = "">--SELECT--</option>
<option value = "all">ALL</option>
<option value = "godown1">Godown 1</option>
<option value = "godown2">Godown 2</option>
<option value = "godown3">Godown 3</option>
<option value = "godown4">Godown 4</option>
</select>
<?php
	if($_GET['godown'] == 'all')
	{	
		$godown = 'ALL GODOWNS';
	}	
	if($_GET['godown'] == 'godown1')
	{
		$godown = 'GODOWN 1';
	}	
	if($_GET['godown'] == 'godown2')
	{	
		$godown = 'GODOWN 2';
	}	
	if($_GET['godown'] == 'godown3')
	{	
		$godown = 'GODOWN 3';
	}	
	if($_GET['godown'] == 'godown4')
	{
		$godown = 'GODOWN 4';
	}	

?>
<h2><?php echo $godown ?></h2>
<table id="datatables" class="stripe hover order-column row-border compact" cellspacing="0" width="100%">
 <thead>
<tr align="center">
<th>Client</th>
<th>Item</th>
<th>Quantity</th>
<th>Status</th>
</tr>
</thead>
<tfoot>
<tr align="center">
<th>Client</th>
<th>Item</th>
<th>Quantity</th>
<th>Status</th>
</tfoot>
<tbody>
<?php
foreach($mainarray as $client => $subarray1)  
{
?>
<tr align="center">
<td><?php echo $client ?></td>
<td><?php 
		foreach($subarray1 as $item => $subarray2)
		{
			if($item != 'Total(To bill)' && $item != 'Total(To deliver)') 
			{
				//var_dump($subarray2);
				foreach($subarray2 as $status => $qty)
				{
?><td><?php
					echo $qty;
?><td><?php
					echo $status;
				}
			}	
			else
			{
?><td><?php
				echo $subarray1[$item];
?><td><?php	
				echo $item;
			}	
		}	
?>		
</tr>
<?php
}
?>
</tbody>
</table>
</html>

<?php
}
else
	header("Location:loginPage.php");
?>