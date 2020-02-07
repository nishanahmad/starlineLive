<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
require 'connect.php';

		$result = mysqli_query($con,"SELECT * FROM transfer_log") or die(mysqli_error($con));

?>

<html>
<head>
<style>
td.highlight {
        background-color : #FF6666 !important;
 }
h1{

/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#eeeeee+0,cccccc+100;Gren+3D */
background: #eeeeee; /* Old browsers */
background: -moz-linear-gradient(top,  #eeeeee 0%, #cccccc 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#eeeeee), color-stop(100%,#cccccc)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #eeeeee 0%,#cccccc 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #eeeeee 0%,#cccccc 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #eeeeee 0%,#cccccc 100%); /* IE10+ */
background: linear-gradient(to bottom,  #eeeeee 0%,#cccccc 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eeeeee', endColorstr='#cccccc',GradientType=0 ); /* IE6-9 */
}


</style>

<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/dataTables.responsive.css">
<script type="text/javascript" language="javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="js/dataTables.responsive.js"></script>

<script>
$(document).ready(function() {
    // Setup - add a text input to each footer cell
    $('#datatables').dataTable({
"scrollCollapse": true,
"paging":         false,
"responsive": true,
"bJQueryUI":true
});

} );
</script>

<title>Transfer Log</title>
</head>
<body>
<div align="right">
                <a href="logout.php">Logout</a>
</div>
<div align="center">
					<a href="index.php" class="link"><img alt='home' title='home' src='images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;

					<h1 align="center">Transfer Log</h1>

					<table id="datatables" class="stripe hover order-column row-border compact" cellspacing="0" width="60%">
						<thead>
						<tr align="center">
						<th>Date</th>
						<th>Item</th>
						<th>Quantity</th>
						<th>From Godown</th>
						<th>To Godown</th>
						<th>Remarks</th>
						
						</tr>
						</thead>

						<tbody>
						<?php
						while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))  
						{
?>							<tr align="center">
							<td><?php echo date("d-m-Y", strtotime($row['date']));?></td>
							<td><?php echo $row['item']?></td>
							<td><?php echo $row['qty']?></td>
							<td><?php echo $row['transferred_from']?></td>
							<td><?php echo $row['transferred_to']?></td>
							<td><?php echo $row['remarks']?></td>
							</tr>
<?php					}
?>						</tbody>
					</table>
					</div>
				</div>
				



</body>
</html>

<?php
echo "<br><br>";
echo "LOGGED USER : ".$_SESSION["user_name"] ;	
}
else
	header("Location:loginPage.php");
?>