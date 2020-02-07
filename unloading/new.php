<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
?>

<html>
<head>
<title>LORRY LOAD</title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/return_check.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<link rel="stylesheet" type="text/css" href="../css/newEdit.css" />
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
<form name="frm" method="post" action="insert.php" onsubmit="return return_check()">
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/homeBrown.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
</div>
<br>
<table border="0" cellpadding="15" cellspacing="0" width="60%" align="center" style="float:center" class="tblSaveForm">
<tr class="tableheader">
<td colspan="2"><div align ="center"><b><font size="4">ADD TO GODOWN</font><b></td>
</tr>

<tr>
<td><label>Date</label></td>
<td><input type="text" id="datepicker" class="txtField" name="date" required  /></td>
</tr>

<tr>
<td><label>Item</label></td>
<td><select required name="item" class="txtField">
    <option value = "">---Select---</option>
    <?php
    $query = mysqli_query($con,"SELECT DISTINCT `item` FROM `closing_stock` ORDER BY item");
     foreach ( $query as $d) 
	 {
		echo "<option value='".$d['item']."'>".$d['item']."</option>";    
	}
    ?>
      </select>
</td>
</tr>

<tr>
<td><label>Quantity</label></td>
<td><input type="text" name="qty" required class="txtField" pattern="[0-9]+" title="Input a valid number"></td>
</tr>

<tr>
<td><label>Delivery Number</label></td>
<td><input type="text" name="delivery_number" class="txtField"></td>
</tr>

<tr>
<td><label>Invoice Number</label></td>
<td><input type="text" name="invoice_number" class="txtField"></td>
</tr>

<tr>
<td><label>Godown Slip Number</label></td>
<td><input type="text" name="godown_slip_number" class="txtField"></td>
</tr>

<tr>
<td><label>Lorry Number</label></td>
<td><input type="text" name="lorry_number" class="txtField"></td>
</tr>

<tr>
<td><label>Godown</label></td>
<td><select required name="godown" class="txtField">
    <option value = "">---Select---</option>
    <?php
    $query = mysqli_query($con,"SELECT DISTINCT `godown` FROM `closing_stock` ");
     foreach ( $query as $d) 
	 {
		echo "<option value='".$d['godown']."'>".$d['godown']."</option>";    
	 }
    ?>
      </select>
</td>
</tr>

</tr>

</tr>

<tr>

<td colspan="2"><div align="center"><input type="submit" name="submit" value="Submit" class="btnSubmit"></div></td>
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