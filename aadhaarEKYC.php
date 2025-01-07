<?php
	include("sendRequest.php");

	$json_data = file_get_contents('php://input');
	$request_data = json_decode($json_data, true);


	
	if($request_data['type'] == "Aadhaar-paperless-EKYC" && $request_data['step'] == "1"){
		$url = "https://www.truthscreen.com/v1/apicall/nid/aadhar_get_otp";
		$body = [
			"aadharNo"=>$request_data['aadhaarNumber'],
			"transId"=>"12345",
			"docType"=>211,		
		];
		$decrypted = sendRequest($url, $body);



		echo json_encode($decrypted);
	}                                              



	if($request_data['type'] == "Aadhaar-paperless-EKYC" && $request_data['step'] == "2"){
		$url = "https://www.truthscreen.com/v1/apicall/nid/aadhar_submit_otp";
		$body = [
			"transId"=>$request_data['tsTransId'],
			"otp"=>$request_data['otp']
		];		
		$decrypted = sendRequest($url, $body);
		echo $decrypted;

		// $decrypted = [
		// 		'msg' =>[
		// 			'Address' => 'Hxxxxx',
		// 			'Careof' => 'S/O Sxxxxx Kxxxx',
		// 			'Country' => 'India',
		// 			'DOB' => 'dd-mm-yyyy',
		// 			'District' => 'Sxxxxx',
		// 			'Document link' => 'https://ab-mum-prod-vault-latest.s3xxxxxxxxxx',
		// 			'Gender' => 'MALE',
		// 			'Image' => 'https://ab-mum-prod-vault-latest.s3xxxxxxx',
		// 			'Landmark' => '',
		// 			'Locality' => 'Hexxxxxx',
		// 			'Name' => 'Axxx Dxxxx',
		// 			'Pincode' => '1xxxx',
		// 			'Post Office' => 'Dxxx',
		// 			'Relatationship type' => '',
		// 			'Relative Name' => '',
		// 			'Share Code' => '1234',
		// 			'State' => 'Pxxxx',
		// 			'Street' => 'Kxxxxx',
		// 			'Sub District' => '',
		// 			'Village/Town/City' => 'Dxxxx',
		// 		],
		// 		'status' => 1,
		// ];

		// $decrypted = [
		// 		"status"=>0,
		// 		"msg"=>"Something went wrong,please try again"
		// ];

		echo json_encode($decrypted);
	}



	if($request_data['type'] == "Aadhaar Verification"){
		$url = "https://www.truthscreen.com/v1/apicall/nid/av/idsearch";
		$body = [
            "transId"=>"12345",
			"docNumber"=>$request_data['docNumber'],
			"docType"=>53,		
		];
		$decrypted = sendRequest($url, $body);



		echo json_encode($decrypted);
	}     


?>