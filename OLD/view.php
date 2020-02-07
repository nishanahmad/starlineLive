<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require 'connect.php';	

?>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/button.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<script type="text/javascript" language="javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="js/godown_check.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">

<title>VIEW</title>
</head>
<body>
<?php
echo "LOGGED USER : ".$_SESSION["user_name"] ;
		
		$result = mysqli_query($con,"SELECT * FROM stock_details where id='".$_GET['id']."'  ") or die(mysqli_error($con));
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
?>
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="list.php" class="link"><img alt='list' title='list' src='images/list_icon.jpg' width='50px' height='50px'/> </a><br><br><br>
<br>
<table border="1" cellpadding="10" cellspacing="0" width="35%" align="center" style="float:center" bgcolor="#E8E8E8">
<tr>
<td width="50%"><label>Date</label></td>
<td><label><?php echo date('d-m-Y', strtotime($row['entry_date']));?></label></td>
</tr>

<td><label>Client</label></td>
<td><label><?php echo $row['client'];?></label></td>
</tr>

<td><label>Item</label></td>
<td><label><?php echo $row['item'];?></label></td>
</tr>

<td><label>Quantity </label></td>
<td><label><?php echo $row['qty'];?><label></td>
</tr>

<td><label>Lorry No.</label></td>
<td><label><?php echo $row['lorry'];?></label></td>
</tr>

<td><label>Loading Time</label></td>
<td><label><?php if($row['loading_time'] != '00:00:00') echo date('h:i A', strtotime($row["loading_time"]));?></label></td>
</tr>

<td><label>Invoice No.</label></td>
<td><label><?php echo $row['invoice_number'];?> </label></td>
</tr>

<td><label>Invoice Date</label></td>
<td><label><?php if($row['invoice_number'] != null) echo date('d-m-Y', strtotime($row['invoice_date']));?> </label></td>
</tr>

<td><label>Godown Slip No.</label></td>
<td><label><?php echo $row['godown_slip_number'];?> </label></td>
</tr>

<td><label>Godown</label></td>
<td><label><?php echo $row['godown'];?></label></td>
</tr>


</tr>
</table>
<br><br>
<button type="button" style="width : 100px;"  onclick="location.href='edit.php?id=<?php echo $row['id'] ?>'" >Edit</button>&nbsp&nbsp&nbsp
<a href="delete.php?id=<?php echo $row["id"]; ?>"  class="btn btn-red" onclick="return confirm('Are you sure you want to permanently delete this entry ?')">DELETE</a><br><br>
<br><br>
</div>
</div>
</body>
</html>

<?php
}
else
header("Location:loginPage.php");
?>