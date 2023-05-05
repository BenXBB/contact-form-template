<?php
	
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);

    // ----------------  Include config files e.g dbname, host, port, user, password ---------------- 
	include("config.php");

	header('Content-Type: application/json; charset=UTF-8');

    // ----------------  Setting the conn var with a mysqli object containing the config files ---------------- 
	$conn = new mysqli($cd_host, $cd_user, $cd_password, $cd_dbname, $cd_port);

    // ----------------  Setting connection/database error response first before any process ---------------- 
	if (mysqli_connect_errno()) {
		
		$output['status']['code'] = "300";
		$output['status']['name'] = "failure";
		$output['status']['description'] = "database unavailable";
		$output['data'] = [];

		mysqli_close($conn);

		echo json_encode($output);

		exit;

	}	

	// ----------------  Setting query. Not prepared as no parameters are set ---------------- 
	$query = 'SELECT id, firstName, lastName, email, phone, message FROM contactform ORDER BY id';

    // ------------------------------  Executing query ------------------------------ 
	$result = $conn->query($query);
	
    // ----------------  Setting error response if query fails ---------------- 
	if (!$result) {

		$output['status']['code'] = "400";
		$output['status']['name'] = "executed";
		$output['status']['description'] = "query failed";	
		$output['data'] = [];

		mysqli_close($conn);

		echo json_encode($output); 

		exit;

	}
    
    // ----------------  Success response. Adding each row of the query to an array and echoing to the browser ---------------- 
   	$data = [];

	while ($row = mysqli_fetch_assoc($result)) {

		array_push($data, $row);

	}

	$output['status']['code'] = "200";
	$output['status']['name'] = "ok";
	$output['status']['description'] = "success";
	$output['data'] = $data;
	
	mysqli_close($conn);

	echo json_encode($output); 

?>