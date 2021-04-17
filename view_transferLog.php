<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION["user_name"]))
{
require 'connect.php';

		$result = mysqli_query($con,"SELECT * FROM transfer_log ORDER BY date DESC") or die(mysqli_error($con));

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

.tablesorter .tablesorter-filter {
	width: 99%;
	border: 1px solid #C3E6CB;
	border-radius: 5px;  
}
@media screen and (min-width: 768px) {
	.bottom-nav{	
		top: 86px;
	}				
}

.tablesorter .filtered {
	display: none;
}

.tablesorter .tablesorter-errorRow td {
	text-align: center;
	background-color: #e6bf99;
}

</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous"/>
<link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet"/>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.min.js" integrity="sha512-mWSVYmb/NacNAK7kGkdlVNE4OZbJsSUw8LiJSgGOxkb4chglRnVfqrukfVd9Q2EOWxFp4NfbqE3nDQMxszCCvw==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.9.1/jquery.tablesorter.widgets.min.js" integrity="sha512-6I1SQyeeo+eLGJ9aSsU43lGT+w5HYY375ev/uIghqqVgmSPSDzl9cqiQC4HD6g8Ltqz/ms1kcf0takjBfOlnig==" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
	$(".maintable").tablesorter({
		dateFormat : "ddmmyyyy",
		theme : 'bootstrap',
		widgets: ['filter'],
		filter_columnAnyMatch: true
	});
} );
</script>

<title>Transfer Log</title>
</head>
<body>
<div align="center">
	<a href="index.php" class="link"><img alt='home' title='home' src='images/home.png' width='60px' height='60px'/> </a><br/><br/>
	<h1 align="center">Transfer Log</h1><br/><br/>
	<table class="maintable table table-hover table-bordered" style="width:95%;margin-left:2%;">
		<thead>
			<tr class="table-success">
			<th>Date</th>
			<th>Item</th>
			<th>Quantity</th>
			<th>From Godown</th>
			<th>To Godown</th>
			<th>Remarks</th>
			</tr>	
		</thead>
		<tbody>	<?php
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))  
			{																																				?>							
				<tr align="center">
					<td><?php echo date("d-m-Y", strtotime($row['date']));?></td>
					<td><?php echo $row['item']?></td>
					<td><?php echo $row['qty']?></td>
					<td><?php echo $row['transferred_from']?></td>
					<td><?php echo $row['transferred_to']?></td>
					<td><?php echo $row['remarks']?></td>
				</tr><?php					
			}																																				?>
		</tbody>	
	</table>					
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