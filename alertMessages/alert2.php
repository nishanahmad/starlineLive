<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <link rel="stylesheet" href="../css/alert.css" type="text/css" media="screen" charset="utf-8" />
  <title>Error</title>
	<meta name="viewport" content="width=device-width">
	</head>
<body>
<br><br><br>	
	<ul id="various-alert-boxes-freebie">
		<div style="width : 60%">
		<div class="alert-box failure">
					<div class="text">
						<font size='5px'><b>ERROR</b></font>
						<br><br>
						<font size='5px'>Unable to change Date</font>
					</div>
				</div>
		
<br><br><br><br>		
				
				<div class="alert-box success">
					<div class="text">
						<font size='5px'><b>TIPS</b></font>
						<br><br>
						<font size='5px'>
						<li>
						Delete the record (Record Id : <?php echo $_GET['id'];?>) 
						</li>
						<li>
						Create new loading data with the new Date (Keep all the other values same)
						</li>						
						</font>
					</div>
				</div>
			</ul>	
<br><br><br><br>
	<div align="center">
		<font size="5px"><a href='../edit.php?id=<?php echo $_GET["id"];?>'>Click here to go back to previous page
			
		
	
</body>
</html>