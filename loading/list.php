<!DOCTYPE html>
<html>
<meta charset="UTF-8"> 
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
		
		.responstable {
		  margin: 1em 0;
		  width: 50%;
		  overflow: hidden;
		  background: #FFF;
		  color: #024457;
		  border-radius: 5px;
		  border: 1px solid #167F92;
		}
		.responstable tr {
		  border: 1px solid #D9E4E6;
		}
		.responstable tr:nth-child(odd) {
		  background-color: #EAF3F3;
		}
		.responstable th {
		  display: none;
		  border: 1px solid #FFF;
		  background-color: #167F92;
		  color: #FFF;
		  padding: 1em;
		  
		 }
		.responstable td {
		  display: block;
		  word-wrap: break-word;
		  max-width: 3em;
		}
		.responstable td:first-child {
		  display: table-cell;
		  border-right: 1px solid #D9E4E6;
		}
		@media (min-width: 480px) {
		  .responstable td {
			border: 1px solid #D9E4E6;
		  }
		}
		.responstable th, .responstable td {
		  text-align: left;
		  margin: .5em 1em;
		}
		@media (min-width: 480px) {
		  .responstable th, .responstable td {
			display: table-cell;
			padding: .2em;
		  }
		}
		
	</style>
		<link rel="stylesheet" type="text/css" href="../css/glow_box.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="../css/dataTables.responsive.css">
		<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/dataTables.responsive.js"></script>
		<script type="text/javascript" language="javascript" src="../js/dataTables.jqueryui.js"></script>
		<script type="text/javascript" language="javascript" >
			$(document).ready(function() {
				var dataTable = $('#employee-grid').DataTable( {
					"processing": true,
					"serverSide": true,
					//"responsive": true,
					"bJQueryUI":true,
					"iDisplayLength": 2000,
					"aaSorting" : [[0, 'desc']],
					
		/*			"createdRow": function ( row, data, index ) {
								if ( data[6].contains('NIL') || data[6].contains('RTN') ) {
									$('td', row).eq(4).addClass('red');
								}
								if ( data[5].contains('NIL')) {
									$('td', row).eq(4).addClass('green');
								}			
								},   */
								 
					"ajax":{
						url :"list_server.php", // json datasource
						type: "post",  // method  , by default get
						error: function(){  // error handling
							$(".employee-grid-error").html("");
							$("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#employee-grid_processing").css("display","none");
										}
						   }
				} );
				
   dataTable.on( 'xhr', function () {
    var json = dataTable.ajax.json();
	$('.to_deliver').html(json.to_deliver);
	$('.to_receive').html(json.to_receive);
	$('.billed').html(json.billed);
	$('.slipped').html(json.slipped);
	var itemString = '<table class="responstable"><tr><th width="30%">Item</th><th width="15%">To Deliver</th><th width="15%">To Receive</th><th width="15%">Taken</th><th width="15%">Billed</th></tr><td>' + json.itemarray.slice(1,-2);
	itemString = itemString.replace(/"/g,'');
    itemString = itemString.replace(/},/g,'</td></tr><tr><td>');
    itemString = itemString.replace(/:{/g,',');
	itemString = itemString.replace(/,/g,'</td><td>');
	itemString = itemString.replace(/To deliver:/g,'').replace(/To receive:/g,'').replace(/Taken:/g,'').replace(/Billed:/g,'');
	itemString = itemString + '<tr><th>TOTAL</th><th>-' + json.to_deliver + '</th><th>' + json.to_receive +'</th><th>'+ json.slipped + '</th><th>-'+ json.billed +'</th></tr>'
	$('.itemarray').html(itemString);
	$('.total').html(json.total);
	
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
					<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
					<a href="new.php" class="link"><img alt='Add' title='Add New' src='../images/addnew.png' width='60px' height='60px'/></a>
<!--		<h1>All Sales</h1>    -->
		
		</div>
<div align="center" class="gradient">
<font size="5">
<br>
To Receive: +<span class='to_receive'></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
To Deliver : -<span class='to_deliver'></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
<br>
Taken : +<span class='slipped'></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
Billed : -<span class='billed'></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
<br><br>
<b>TOTAL : <span class='total'></span></b>
<br>
</font>
<font size="4">
<span class='itemarray'/>
</font>
<br>
</div>
		<br><br>
<div align="center">
			<input type="text" data-column="0"  style="width:30px;" class="search-input-text textarea" placeholder="Id">
			<input type="text" data-column="1"  style="width:150px;" class="search-input-text textarea" placeholder="Slip No.">			
			<input type="text" data-column="2"  style="width:150px;" class="search-input-text textarea" placeholder="Date">
			<input type="text" data-column="5"  style="width:120px;" class="search-input-text textarea" placeholder="Client">
			<input type="text" data-column="6"  class="search-input-text textarea" placeholder="Item">
			<input type="text" data-column="8"  class="search-input-text textarea" placeholder="Invoice No.">	
			<input type="text" data-column="9"  class="search-input-text textarea" placeholder="Godown">		
			<input type="text" data-column="10"  class="search-input-text textarea" placeholder="Driver">		
			
		<br><br>	
			<table id="employee-grid" class="display cell-border  no-wrap">
					<thead>
						<tr>
							<th>Id</th>
							<th style="width:70px;">Slip Number</th>							
							<th style="width:75px;">Date</th>
							<th style="width:60px;">Time</th>
							<th>Lorry</th>
							<th>Client</th>
							<th>Item</th>
							<th>Qty</th>
							<th style="width:70px;">Invoice Number</th>							
							<th style="width:75px;">Invoice date</th>
							<th style="width:100px;">Godown</th>
							<th style="width:100px;">Driver</th>
							
						</tr>
					</thead>



			</table>
		</div>
	</body>
</html>