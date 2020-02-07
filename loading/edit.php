<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';	
?>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/button.css">
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/godown_check.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<script>
$(function() {

var pickerOpts = { dateFormat:"d-mm-yy"}; 
$( "#datepicker" ).datepicker(pickerOpts);
});
</script>
<title>EDIT</title>
</head>
<body>
<?php
echo "LOGGED USER : ".$_SESSION["user_name"] ;
		
		$result = mysqli_query($con,"SELECT * FROM stock_details where id='".$_GET['id']."'  ") or die(mysqli_error($con));
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
?>
<form name="frm" method="post" action="update.php?id=<?php echo $_GET['id'];?>" onsubmit="return godown_check()">
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="list.php" class="link"><img alt='list' title='list' src='images/list_icon.jpg' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
</div>
<br>
<table border="1" cellpadding="15" cellspacing="0" width="40%" align="center" style="float:center" bgcolor="#E8E8E8">
<tr class="tableheader">
<td colspan="2"><div align ="center"><b><font size="4">EDIT</font><b></td>
</tr>

<td><label>Date</label></td>
<td><label><?php echo date('d-m-Y', strtotime($row['entry_date']));?></label></td>
<input type="hidden" name="date" value="<?php echo $row['entry_date'];?>" >
</tr>

<td><label>Client</label></td>
<td><label><?php echo $row['client'];?></label></td>
</tr>

<td><label>Item</label></td>
<td><label><?php echo $row['item']; ?></label></td>
<input type="hidden" name="item" value="<?php echo $row['item'];?>" >
</tr>


<td><label>Quantity </label></td>
<td><label><?php echo $row['qty'];?></label></td>
<input type="hidden" name="qty" value="<?php echo $row['qty'];?>" >

</tr>

<td><label>Lorry No.</label></td>
<td><input type="text" <?php if($row['lorry'] != '') echo 'readonly';?> name="lorry" class="txtField" value="<?php echo $row['lorry'];?>"></td>
</tr>

<td><label>Driver</label></td>
<td><input type="text" name="driver" class="txtField" value="<?php echo $row['driver'];?>"></td>
</tr>


<td><label>Invoice No.</label></td>
<td><input type="text" <?php if($row['invoice_number'] != '') echo 'readonly';?> class="txtField" name="invoice_number" value="<?php echo $row['invoice_number'];?>" /></td>
</tr>

<td><label>Invoice Date</label></td>
<?php 
if($row['invoice_number'] != '')
{?>
	<td><input type="text" readonly  value="<?php echo date('d-m-Y', strtotime($row['invoice_date']));?>" class="txtField" name="invoice_date" /></td>	
<?php
}
else
{?>
	<td><input type="text" id="datepicker" class="txtField" name="invoice_date" /></td>		
<?php
}	
?>
</tr>

<td><label>Godown Slip No.</label></td>
<td><input type="text" <?php if($row['godown_slip_number'] != '') echo 'readonly';?> class="txtField" name="godown_slip_number" value="<?php echo $row['godown_slip_number'];?>" /></td>
</tr>

<td><label>Godown</label></td>
<?php 
if($row['godown'] != '') 
{
?>	<td><input type="text" readonly class="txtField" name="godown" value="<?php echo $row['godown'];?>" /></td>
<?php
}
else
{
?>	
<td><select name="godown" class="txtField">
    <option value = "">---Select---</option>
<?php
		$query_godowns = "SELECT DISTINCT `godown` FROM `closing_stock` ";
		$db = mysqli_query($con,$query_godowns);
		while ( $d=mysqli_fetch_assoc($db)) {
		echo "<option value='".$d['godown']."'>".$d['godown']."</option>";    }
?>
		</select>
</td>
<?php
}
?>
</tr>

</tr>
<tr>
<td colspan="2"><div align="center"><input type="submit" name="submit" value="Save" class="btnSubmit"></div></td>
</tr>
</table>
</div>
</form>
</body>
</html>

<?php
}
else
header("Location:../loginPage.php");
?>