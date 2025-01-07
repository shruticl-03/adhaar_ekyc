<?php
	include("sendRequest.php");

	$json_data = file_get_contents('php://input');
	$request_data = json_decode($json_data, true);


	
	// if($request_data['type'] == "Aadhaar Verification"){
		$url = "https://www.truthscreen.com/v1/apicall/nid/av/idsearch";
		$body = [
            "transId"=>"1234567",
			"docNumber"=>$request_data['docNumber'],
			"docType"=>53,		
		];
		$decrypted = sendRequest($url, $body);



		echo json_encode($decrypted);
	// }                                              

?>