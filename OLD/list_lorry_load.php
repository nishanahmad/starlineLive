<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
require 'connect.php';	

$result = mysqli_query($con,"SELECT * FROM godown_entry order by id desc");
		 
if ( false===$result ) 
{
  printf("error: %s\n", mysqli_error($con));
}

?>

<html>
<head>
<title>List Lorry Load</title>
<style>
td.highlight {
        font-weight: bold;
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
		<link rel="stylesheet" type="text/css" href="css/button.css">
		<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="css/dataTables.responsive.css">
		<script type="text/javascript" language="javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="js/dataTables.responsive.js"></script>
		<script type="text/javascript" language="javascript" src="js/dataTables.jqueryui.js"></script>
<script>
$(document).ready(function(){
$('#datatables').dataTable({
"scrollCollapse": true,
"paging":         false,
"responsive": true,
"bJQueryUI":true,
"aaSorting" : [[0, 'desc']]
});

})

</script>
</head>
<body>
<div align="right">
                <a href="logout.php">Logout</a>
</div>
				<div align="center">
				<a href="index.php" class="link"><img alt='home' title='home' src='images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
				<h1> List Lorry Load </h1>
				<br><br><br>
				<div style="width:90%;">
				<table id="datatables" class="display">
				<thead>
				<tr>
				<th>Id</th>
				<th>Date</th>
				<th>Item</th>
				<th>Quantity</th>
				<th>Delivery No.</th>
				<th>Invoice No.</th>
				<th>Godown Slip No.</th>
				<th>Lorry No.</th>
				<th>Godown</th>
				<th></th>
				</tr>
				</thead>
				 <tbody>
				<?php
				while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
				{
				?>
				<tr align="center">
				<td><?php echo $row['id']?></td>
				<td><?php echo date('d-m-Y',strtotime($row['entry_date']))?></td>
				<td><?php echo $row['item']?></td>
				<td><?php echo $row['qty']?></td>
				<td><?php echo $row['delivery_number']?></td>
				<td><?php echo $row['invoice_number']?></td>
				<td><?php echo $row['godown_slip_number']?></td>
				<td><?php echo $row['lorry_number']?></td>
				<td><?php echo $row['godown']?></td>
				<td><a href="delete_lorry_load.php?id=<?php echo $row['id'];?>" style="width:50px;" class="btn btn-red" onclick="return confirm('Are you sure you want to permanently delete this entry ?')">Delete</a></td>				
				</tr>
				<?php
				}
				?>
				</tbody>
				</table>
				</body>
				</html>
				<?php
echo "LOGGED USER : ".$_SESSION["user_name"] ;	
							
				}

				else
					header("Location:loginPage.php");

				?>