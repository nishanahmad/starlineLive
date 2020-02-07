<!DOCTYPE html>
<html>
	<title>List</title>
	<head>
	<style>
		tfoot {
			display: table-header-group;
		}
		td.red {
				background-color : #FF6666 !important;
			}
		td.green {
				background-color : #99FF66 !important;
			}
		.gradient{
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
		<script type="text/javascript" language="javascript" src="js/dataTables.jqueryui.js"></script>
		<script type="text/javascript" language="javascript" >
			$(document).ready(function() {
				var dataTable = $('#employee-grid').DataTable( {
					"processing": true,
					"serverSide": true,
					//"responsive": true,
					"bJQueryUI":true,
					"iDisplayLength": 500,
   
					"ajax":{
						url :"library/list_closing_stock.php", // json datasource
						type: "post",  // method  , by default get
						error: function(){  // error handling
							$(".employee-grid-error").html("");
							$("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="4">No data found in the server</th></tr></tbody>');
							$("#employee-grid_processing").css("display","none");
										}
						   }
				} );

	
				$("#employee-grid_filter").css("display","none");  // hiding global search box
				$('.search-input-text').on( 'keyup click', function () {   // for text boxes
					var i =$(this).attr('data-column');  // getting column index
					var v =$(this).val();  // getting search input value
					dataTable.columns(i).search(v).draw();
				} );
				$('.search-input-select').on( 'change', function () {   // for select box
					var i =$(this).attr('data-column');  
					var v =$(this).val();  
					dataTable.columns(i).search(v).draw();
				} );
				
			} );
		</script>

	</head>
	<body>
		<div align="center">
					<a href="index.php" class="link"><img alt='home' title='home' src='images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
					<a href="new.php" class="link"><img alt='Add' title='Add New' src='images/addnew.png' width='60px' height='60px'/></a>
<!--		<h1>All Sales</h1>    -->
		
		</div>
<div align="center" class="gradient">

		<br><br>
			<table id="employee-grid" class="display cell-border  no-wrap">
					<thead>
						<tr>
							<th>Date</th>
							<th>Godown</th>
							<th>Item</th>
							<th>Closing Stock</th>

						</tr>
					</thead>
					<thead>
						<tr>
							<td><input type="text" data-column="0"  class="search-input-text"></td>
							<td><input type="text" data-column="1"  class="search-input-text"></td>
							<td><input type="text" data-column="2"  class="search-input-text"></td>
							<td><input type="text" data-column="3"  class="search-input-text"></td>
						</tr>
					</thead>
			</table>
		</div>
	</body>
</html>
