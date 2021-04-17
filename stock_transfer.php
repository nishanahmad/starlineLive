<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connect.php';
require 'library/update_closing_stock.php';

session_start();
if(isset($_SESSION["user_name"]))
{
	if(count($_POST)>0) 
	{
		$entryDate = date("Y-m-d", strtotime($_POST['date']));
		$item = $_POST['item'];
		$qty = $_POST['qty'];
		$fromGodown = $_POST['fromGodown'];
		$toGodown = $_POST['toGodown'];
		$remarks = $_POST['remarks'];
		
		update_closing_stock($entryDate,$item,$fromGodown,$qty,$con);
		update_closing_stock($entryDate,$item,$toGodown,-$qty,$con);		
		
		$sql="INSERT INTO transfer_log (date, item, qty, transferred_from, transferred_to, remarks)
			 VALUES
			 ('$entryDate', '$item', '$qty', '$fromGodown', '$toGodown', '$remarks')";

		$result = mysqli_query($con, $sql) or die(mysqli_error($con));	 		
	}	
?>
<html>
<head>
<title>Godown Stock Transfer</title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<script type="text/javascript" language="javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="js/godown_check.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<script>
$(function() {

var pickerOpts = { dateFormat:"d-mm-yy"}; 
	    	
$( "#datepicker" ).datepicker(pickerOpts);

});
</script>
</head>
<body>
<?php
echo "LOGGED USER : ".$_SESSION["user_name"] ;
?>
<form name="frm" method="post" action="">
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="index.php" class="link"><img alt='home' title='home' src='images/home.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
<a href="list.php" class="link"><img alt='list' title='list' src='images/list_icon.jpg' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
</div>
<br>
<table border="1" cellpadding="15" cellspacing="0" width="40%" align="center" style="float:center" bgcolor="#E8E8E8">
<tr class="tableheader">
<td colspan="2"><div align ="center"><b><font size="4">Godown Stock Transfer</font><b></td>
</tr>

<td><label>Date</label></td>
<td><input type="text" id="datepicker" name="date" class="txtField" required></td>
</tr>

</tr>

<td><label>Item</label></td>
<td><select required name="item" class="txtField" >
    <option value = "">---Select---</option>
<?php	
		$query_items = "SELECT DISTINCT `item` FROM `closing_stock` ORDER BY item ";
		$db = mysqli_query($con,$query_items);
		while ( $d=mysqli_fetch_assoc($db)) {
		echo "<option value='".$d['item']."'>".$d['item']."</option>";    }
?>
      </select>
</td>
</tr>


<td><label>Quantity </label></td>
<td><input type="text" name="qty" class="txtField" pattern="[0-9]+" required title="Input a valid number"></td>
</tr>

<td><label>Transferred From</label></td>
<td><select name="fromGodown" class="txtField"  required>
    <option value = "" >---Select---</option>
<?php
		$query_godowns = "SELECT DISTINCT `godown` FROM `closing_stock` ";
		$db = mysqli_query($con,$query_godowns);
		while ( $d=mysqli_fetch_assoc($db)) {
		echo "<option value='".$d['godown']."'>".$d['godown']."</option>";    }
?>
		</select>

</td>
</tr>

</tr>
<td><label>Transferred To</label></td>
<td><select name="toGodown" class="txtField" required>
    <option value = "">---Select---</option>
<?php
		$query_godowns = "SELECT DISTINCT `godown` FROM `closing_stock` ";
		$db = mysqli_query($con,$query_godowns);
		while ( $d=mysqli_fetch_assoc($db)) {
		echo "<option value='".$d['godown']."'>".$d['godown']."</option>";    }
?>
		</select>

</td>

</tr>
</tr>
<td><label>Remarks </label></td>
<td><input type="text" name="remarks"></td>
</tr>

<tr>
<td colspan="2"><div align="center"><input type="submit" name="submit" value="Transfer" class="btnSubmit"></div></td>
</tr>
</table>
</div>
</form>
</body>
</html>
<?php
}
else
{
	echo "ERROR : YOU ARE NOT LOGGED IN";
}	

?>