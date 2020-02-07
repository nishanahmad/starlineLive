<?php
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';			
?>
<html>
<head>
<title>Standardization</title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>

<link rel="stylesheet" href="../resources/demos/style.css">
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
<form name="frm" method="post" action="process_new.php">
<div style="width:100%;">
<div align="center" style="padding-bottom:5px;">
<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='50px' height='50px'/> </a> &nbsp;&nbsp;&nbsp;
</div>
<br>
<table border="1" cellpadding="15" cellspacing="0" width="40%" align="center" style="float:center" bgcolor="#E8E8E8">
<tr class="tableheader">
<td colspan="2"><div align ="center"><b><font size="4">Standardization</font><b></td>
</tr>

<td><label>Date</label></td>
<td><input type="text" id="datepicker" name="date" class="txtField" required></td>
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


<td><label>Godown</label></td>
<td><select name="godown" class="txtField" >
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

<tr>
<td colspan="2"><div align="center"><input type="submit" name="submit" value="submit" class="btnSubmit"></div></td>
</tr>
</table>
</div>
</form>
</body>
</html>

<?php
}
else
header("Location:loginPage.php");
?>