<?php
	header('Content-type: application/json');	
	header('Accept: application/json');
	require_once __DIR__ . '/dataLayer.php';

	$action = $_POST["action"];

	switch($action){
		case "LOGIN" 					:	loginFunction();
											break;
		case "REGISTRATION" 			:	registrationFunction();
											break;
		case "COOKIE"					:	cookieFunction();
											break;
		case "SESSION_START"			:	sessionStartFunction();
											break;
		case "SESSION_END"				:	sessionEndFunction();
											break;
		case "RETRIEVE_DATA"			:	retrieveDataFunction();
											break;
		case "RETRIEVE_COMMENTS"		:	retrieveCommentsFunction();
											break;
		case "INSERT_COMMENT"			:	insertCommentFunction();
											break;
		case "SEARCH_USER"				:	searchUserFunction();
											break;
	}

	function loginFunction(){
		$uName = $_POST["uName"];	
		$uPassword = $_POST["uPassword"];
		$rem = $_POST["remember"];

		$loginResponse = attemptLogin($uName, $uPassword, $rem);

		if($loginResponse["MESSAGE"] == "SUCCESS"){
			$response = array("firstName"=>$loginResponse["firstName"],"lastName"=>$loginResponse["lastName"],"utipo"=>$loginResponse["utipo"]);	
			echo json_encode($response);	
		}
		else{
			genericErrorFunction($loginResponse["MESSAGE"]);
		}
	}

	function genericErrorFunction($errorCode){
		switch($errorCode){
			case "500"	:	header("HTTP/1.1 500 Bad connection, portal down");		
							die("The server is down, we couldn't establish the data base");
							break;
			case "409"	:	header("HTTP/1.1 409 Username Already Exists");		
							die("Existing Username");
		}
	}

	function registrationFunction(){
		$fName 		= $_POST["fName"];	
		$lName 		= $_POST["lName"];	
		$uName 		= $_POST["uName"];	
		$email 		= $_POST["uEmail"];
		$uPassword 	= $_POST["uPassword"];
		$uDoctor 	= $_POST["uDoc"];
		$uGender	= $_POST["uGender"];
		$utipo 	= $_POST["utipo"];
		
		$registrationResponse = attemptRegistration($fName, $lName, $uName, $email, $uPassword, $uDoctor, $uGender, $utipo);

		if($registrationResponse["MESSAGE"] == "SUCCESS"){
			$response = array("message"=>"New User Added Correctly");	
			echo json_encode($response);	
		}
		else{
			genericErrorFunction($loginResponse["MESSAGE"]);
		}
	}

	function cookieFunction(){
		$cookieResponse = attemptCookie();

		if($cookieResponse["MESSAGE"] == "SUCCESS"){
			$response = array("user"=>$cookieResponse["username"]);	
			echo json_encode($response);	
		}
		else{
			genericErrorFunction($cookieResponse["MESSAGE"]);
		}
	}

	function sessionStartFunction(){
		$sessionStartResponse = attemptSessionStart();

		if($sessionStartResponse["MESSAGE"] == "SUCCESS"){
			$response = array("uName"=>$sessionStartResponse["uName"],"passwrd"=>$sessionStartResponse["passwrd"], "email"=>$sessionStartResponse["email"]);	
			echo json_encode($response);	
		}
		else{
			genericErrorFunction($sessionStartResponse["MESSAGE"]);
		}
	}

	function sessionEndFunction(){
		$sessionEndResponse = attemptSessionEnd();

		if($sessionEndResponse["MESSAGE"] == "SUCCESS"){
			$response = array("message"=>"Logout Successful");	
			echo json_encode($response);	
		}
		else{
			genericErrorFunction($sessionEndResponse["MESSAGE"]);
		}
	}

	function retrieveDataFunction(){
		$uName = $_POST["uName"];	
		$uPassword = $_POST["uPassword"];

		$retrieveDataResponse = attemptRetrieveData($uName, $uPassword);

		if($retrieveDataResponse["MESSAGE"] == "SUCCESS"){
			$response = array("firstName"=>$retrieveDataResponse["fName"],"lastName"=>$retrieveDataResponse["lName"],"email"=>$retrieveDataResponse["email"],"gender"=>$retrieveDataResponse["gender"],"doctor"=>$retrieveDataResponse["doctor"],"utipo"=>$retrieveDataResponse["utipo"]);		
			echo json_encode($response);	
		}
		else{
			genericErrorFunction($retrieveDataResponse["MESSAGE"]);
		}
	}

	function retrieveCommentsFunction(){
		$uName = $_POST["uName"];	

		$retrieveCommentsResponse = attemptRetrieveComments($uName);
		
		if($retrieveCommentsResponse[0]["MESSAGE"] == "SUCCESS"){
			$c = 0;
			$count = $retrieveCommentsResponse[0]["counter"];

			for($i = 0; $i < $count; $i++){
				$response[$c++] = array("com"=>$retrieveCommentsResponse[$i]["com"],"counter"=>$count);
			}
			echo json_encode($response);	
		}
		else{
			genericErrorFunction($retrieveCommentsResponse["MESSAGE"]);
		}
	}

	function insertCommentFunction(){
		$userN = $_POST["uName"];	
		$comment = $_POST["com"];

		$insertCommentResponse = attemptInsertComments($uName, $comment);

		if($insertCommentResponse["MESSAGE"] == "SUCCESS"){
			$response = array("comm"=>"Comment Added");		
			echo json_encode($response);
		}
		else{
			genericErrorFunction($insertCommentResponse["MESSAGE"]);
		}
	}

	function searchUserFunction(){
		$userName = $_POST["uName"];
		$search = $_POST["search"];
		$uEmail = $_POST["email"];

		if($userName != $search && $uEmail != $search){
			$retrieveSearchResponse = attemptSearchUsers($userName, $search);
		}
				
		if($retrieveSearchResponse[0]["MESSAGE"] == "SUCCESS"){
			$c = 0;
			$count = $retrieveSearchResponse[0]["counter"];

			for($i = 0; $i < $count; $i++){
				$response[$c++] = array("firstName"=>$retrieveSearchResponse[$i]["fName"],"lastName"=>$retrieveSearchResponse[$i]["lName"],"userName"=>$retrieveSearchResponse[$i]["uName"],"origin"=>$retrieveSearchResponse[$i]["origin"],"counter"=>$count);
			}
			echo json_encode($response);	
		}
		else{
			$response[0] = array("counter"=>0);
			echo json_encode($response);	
		}
	}


?>