<?php
    // Error reporting
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);

    // Setting a var for timing the execution ready to send in the response
	$executionStartTime = microtime(true);

    // Example of the endpoint with the request being "UK" and the username or key being "BenXBB"
	$url = 'http://api.geonames.org/countryInfoJSON?&country=' . $_REQUEST['country'] . '&username=BenXBB';

    // Setting up the CURL environment ready for sending. curl_init() initialises the instance
	$ch = curl_init();

    // Verifies the peer's SSL certificate
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // true to return the transfer as a string of the return value of curl_exec() instead of outputting it directly.
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // The URL to fetch. This can also be set when initializing a session with curl_init().
	curl_setopt($ch, CURLOPT_URL,$url);

    // Executing CURL with the provided URL
	$result=curl_exec($ch);

    // Closing CURL once executed
	curl_close($ch);

    // Decoding the reponse to JSON for frontend
	$decode = json_decode($result,true);	

    // Setting the output with the data $decode
	$output['status']['code'] = "200";
	$output['status']['name'] = "ok";
	$output['status']['description'] = "success";
	$output['status']['returnedIn'] = intval((microtime(true) - $executionStartTime) * 1000) . " ms";
	$output['data'] = $decode;
	
	header('Content-Type: application/json; charset=UTF-8');

    // Echoing to the browser
	echo json_encode($output); 

?>
