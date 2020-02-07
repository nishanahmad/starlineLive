<?php

require '../connect.php';

$entries = array(); 

$sql = "SELECT id, client FROM stock_details LIMIT 10;";
$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($id, $client);

while($stmt->fetch())
{
	$temp = [
		'id'=>$id,
		'client'=>$client
	];
	
	array_push($entries, $temp);
}

echo json_encode($entries);