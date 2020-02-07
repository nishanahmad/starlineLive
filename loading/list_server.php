	
<?php
session_start();
if(isset($_SESSION["user_name"]))
{	

require '../connect.php';

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1=> 'godown_slip_number',	
	2 =>'entry_date', 	
	3=> 'loading_time',	
	4=> 'lorry',	
	5 => 'client',
	6=> 'item',
	7=> 'qty',
	8=> 'invoice_number',	
	9=> 'invoice_date',
	10=> 'godown',
	11=> 'driver'	
);
	
// getting total number records without any search

	$sql = "SELECT id, entry_date, client, item, qty, lorry, loading_time, godown_slip_number, invoice_number, invoice_date, godown, driver";
	$sql.=" FROM stock_details";
	$query=mysqli_query($con, $sql) or die(mysqli_error($con));
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


	$sql = "SELECT id, entry_date, client, item, qty, lorry, loading_time, godown_slip_number, invoice_number,invoice_date, godown, driver";
	$sql.=" FROM stock_details WHERE 1=1";



// getting records as per search parameters
if( !empty($requestData['columns'][0]['search']['value']) )
{ //cstl
	$sql.=" AND id LIKE '".$requestData['columns'][0]['search']['value']."%' ";
}

if( !empty($requestData['columns'][1]['search']['value']) )
{ //cstl
	$sql.=" AND godown_slip_number LIKE '".$requestData['columns'][1]['search']['value']."%' ";
}

if( !empty($requestData['columns'][2]['search']['value']) )
{  //entry_date  

	$pattern_day1 = '/^[0-9]{2}$/';
	$pattern_day2 = '/^[0-9]{2}-$/';
	$pattern_day3 = '/^[0-9]{2}-[0-9]{1}$/';
	
	$pattern_day_month1 = '/^[0-9]{2}-[0-9]{2}$/';
	$pattern_day_month2 = '/^[0-9]{2}-[0-9]{2}-$/';
	$pattern_day_month3 = '/^[0-9]{2}-[0-9]{2}-[0-9]{1}$/';
	$pattern_day_month4 = '/^[0-9]{2}-[0-9]{2}-[0-9]{2}$/';
	$pattern_day_month5 = '/^[0-9]{2}-[0-9]{2}-[0-9]{3}$/';
	
	$pattern_month = '/^[a-z A-Z]/';

	$full_pattern = '/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/';
	if(preg_match($pattern_day1, $requestData['columns'][2]['search']['value']) || preg_match($pattern_day2, $requestData['columns'][2]['search']['value']) || preg_match($pattern_day3, $requestData['columns'][2]['search']['value']))
	{
		$day_array[0] = $requestData['columns'][2]['search']['value'][0];
		$day_array[1] = $requestData['columns'][2]['search']['value'][1];
		$day = implode ('', $day_array);
		$sql.=" AND entry_date LIKE '%".$day."' ";	
	}

	if(preg_match($pattern_day_month1, $requestData['columns'][2]['search']['value']) || preg_match($pattern_day_month2, $requestData['columns'][2]['search']['value']) || preg_match($pattern_day_month3, $requestData['columns'][2]['search']['value']) || preg_match($pattern_day_month4, $requestData['columns'][2]['search']['value']) || preg_match($pattern_day_month5, $requestData['columns'][2]['search']['value']))
	{
		$month_day_array[0] = $requestData['columns'][2]['search']['value'][3];
		$month_day_array[1] = $requestData['columns'][2]['search']['value'][4];
		$month_day_array[2] = $requestData['columns'][2]['search']['value'][2];
		$month_day_array[3] = $requestData['columns'][2]['search']['value'][0];
		$month_day_array[4] = $requestData['columns'][2]['search']['value'][1];
		
		$month_day = implode ('', $month_day_array);
		$sql.=" AND entry_date LIKE '%".$month_day."' ";	

	}
	
	if( preg_match($pattern_month, $requestData['columns'][2]['search']['value']) )
	{
		$date = date_parse($requestData['columns'][2]['search']['value']);
		$month = $date['month'];
		$sql.=" AND month(entry_date) = '".$month."' ";	
	}	

	if(	preg_match($full_pattern, $requestData['columns'][2]['search']['value'])	)
	{
		$full_date = date('Y-m-d', strtotime($requestData['columns'][2]['search']['value']));
		$sql.=" AND entry_date LIKE '".$full_date."' ";	
	}
	
	else if(strcmp($requestData['columns'][1]['search']['value'] , 'to') != 0)	
	{
		$dates = explode( 'to', $requestData['columns'][2]['search']['value'] );
		$from = date('Y-m-d', strtotime($dates[0]));
		$to = date('Y-m-d', strtotime($dates[1]));
		$sql.=" AND entry_date BETWEEN '".$from."' AND  '".$to."' ";	
	}	
	
}

if( !empty($requestData['columns'][5]['search']['value']) )
{  //ar
	$sql.=" AND client LIKE '".$requestData['columns'][5]['search']['value']."%' ";
}

if( !empty($requestData['columns'][6]['search']['value']) )
{
	$sql.=" AND item LIKE '".$requestData['columns'][6]['search']['value']."%' ";
}

if( !empty($requestData['columns'][8]['search']['value']) )
{ 
	$sql.=" AND invoice_number LIKE '".$requestData['columns'][8]['search']['value']."%' ";
}

if( !empty($requestData['columns'][9]['search']['value']) )
{
	$sql.=" AND godown LIKE '".$requestData['columns'][9]['search']['value']."%' ";
}

if( !empty($requestData['columns'][10]['search']['value']) )
{
	$sql.=" AND driver LIKE '".$requestData['columns'][10]['search']['value']."%' ";
}


$query=mysqli_query($con, $sql) or die(mysqli_error($con));
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	
if($requestData != null)	
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length

$query=mysqli_query($con, $sql) or die(mysqli_error($con));

	


$data = array();
$to_deliver = 0;
$to_receive = 0;
$billed = 0;
$slipped = 0;
$total = 0;
$itemarray = array();
while( $row=mysqli_fetch_array($query) ) 
{  // preparing an array
	$nestedData=array(); 

	$nestedData[] = '<a href="view.php?id='.$row["id"].'">'.$row["id"].'</a>';
	$nestedData[] = $row["godown_slip_number"];
	$nestedData[] = date('d-m-Y',strtotime($row['entry_date']));

	if($row["loading_time"] != '00:00:00' && $row["loading_time"] != null)
		$nestedData[] = date('h:i A', strtotime($row["loading_time"]));
	else
		$nestedData[] = '';	

	$nestedData[] = $row["lorry"];	
	$nestedData[] = $row["client"];
	$nestedData[] = $row["item"];
	
	if ($row["invoice_number"] != null && $row["godown_slip_number"] == null)			
		$nestedData[] = '-'.$row["qty"];	

	else if ($row["godown_slip_number"] != null && $row["invoice_number"] == null)			
	{
		if (strlen(strstr($row['godown_slip_number'],'RTN'))>0)			
			$nestedData[] = '-'.$row["qty"];	
		else
			$nestedData[] = '+'.$row["qty"];				
	}		
	else
		$nestedData[] = $row["qty"];
		
	$nestedData[] = $row["invoice_number"];
	if($row['invoice_date'] != null)
		$nestedData[] = date('d-m-Y',strtotime($row['invoice_date']));
	else
		$nestedData[] = $row['invoice_date'];
	
	$nestedData[] = $row["godown"];
	$nestedData[] = $row["driver"];

	$data[] = $nestedData;
	
	if(!array_key_exists($row["item"],$itemarray))
		$itemarray[$row["item"]] = array('To deliver' => 0, 'To receive' => 0, 'Taken' => 0, 'Billed' => 0 );
		
	if ($row["invoice_number"] != null && $row["godown_slip_number"] == null)			
	{		
		$to_deliver = $to_deliver + $row['qty'];
		$billed = $billed + $row['qty'];
		$itemarray[$row["item"]]['To deliver'] = $itemarray[$row["item"]]['To deliver'] - $row['qty'];
		$itemarray[$row["item"]]['Billed'] = $itemarray[$row["item"]]['Billed'] - $row['qty'];		
	}	

	if ($row["godown_slip_number"] != null && $row["invoice_number"] == null)			
	{
		if (strlen(strstr($row['godown_slip_number'],'RTN'))>0)			
		{
			$to_deliver = $to_deliver + $row['qty'];
			$billed = $billed + $row['qty'];
			$itemarray[$row["item"]]['To deliver'] = $itemarray[$row["item"]]['To deliver'] - $row['qty'];
			$itemarray[$row["item"]]['Billed'] = $itemarray[$row["item"]]['Billed'] - $row['qty'];					
		}			
		else
		{
			$to_receive = $to_receive + $row['qty'];
			$slipped = $slipped + $row['qty'];
			$itemarray[$row["item"]]['To receive'] = $itemarray[$row["item"]]['To receive'] + $row['qty'];		
			$itemarray[$row["item"]]['Taken'] = $itemarray[$row["item"]]['Taken'] + $row['qty'];				
		}	
	}	
	
	if ($row['invoice_number'] != null &&  $row['godown_slip_number'] != null && !strlen(strstr($row['godown_slip_number'],'RTN'))>0)		
	{
		$billed = $billed + $row['qty'];
		$itemarray[$row["item"]]['Billed'] = $itemarray[$row["item"]]['Billed'] - $row['qty'];		
		if(!strlen(strstr($row['godown_slip_number'],'RTN'))>0)
		{
			$slipped = $slipped + $row['qty'];
			$itemarray[$row["item"]]['Taken'] = $itemarray[$row["item"]]['Taken'] + $row['qty'];				
		}	
	}
}
	//var_dump($itemarray);
	$total = $to_receive - $to_deliver;

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data,   // total data array
			"to_deliver"	  => $to_deliver,
			"to_receive"	  => $to_receive,
			"billed"		  => $billed,
			"slipped"		  => $slipped,
			"itemarray"		  => json_encode($itemarray),	
			"total"			  => $total
			);

echo json_encode($json_data);  // send data as json format

}
else
	header("Location:loginPage.php");
?>