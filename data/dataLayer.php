<?php
	function databaseConnection(){
		$servername = "localhost";		
		$username = "root";
		$password = "root";						
		$dbname = "testing";		

		$conn = new mysqli($servername, $username, $password, $dbname); 
		
		if($conn->connect_error){		
			return null;
		}
		else{
			return $conn;
		}
	}

	function attemptLogin($userName, $userPassword, $remember){
		$connection = databaseConnection();

		if($connection != null){
			$sql = "SELECT * FROM Users WHERE username = '$userName' AND passwrd = '$userPassword'";
			$result = $connection->query($sql);

			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					if($remember == "1"){
						setcookie("username", $userName, time()+(60*60*24*30), "/"); 
					}
					session_start();
					if(!isset($_SESSION["firstName"])){
						$_SESSION["firstName"] = $row["fName"];
					}
					if(!isset($_SESSION["lastName"])){
						$_SESSION["lastName"] = $row["lName"];
					}
					if(!isset($_SESSION["username"])){
						$_SESSION["username"] = $row["username"];
					}
					if(!isset($_SESSION["password"])){
						$_SESSION["password"] = $row["passwrd"];
					}
					if(!isset($_SESSION["email"])){
						$_SESSION["email"] = $row["email"];
					}
					if(!isset($_SESSION["utipo"])){
						$_SESSION["utipo"] = $row["utipo"];
					}
					$response = array("firstName"=>$row["fName"],"lastName"=>$row["lName"],"utipo"=>$row["utipo"], "MESSAGE"=>"SUCCESS");		
				}
				$connection->close();	
				return $response;	
			}
			else{
				$connection->close();
				return array("MESSAGE"=>"406");
			}
		}
		else{
			return array("MESSAGE" => "500");
		}
	}

	function attemptRegistration($firstName, $lastName, $userName, $userEmail, $userPassword, $uDoctor, $userGender, $utipo){
		$connection = databaseConnection();

		if($connection != null){
			$sql = "SELECT * FROM Users WHERE username = '$userName'";
						
			$result = $connection->query($sql);

			if($result->num_rows > 0){
				$connection->close();
				$response = array("MESSAGE"=>"409");
				return $response;	
			}
			else{
				
				$sql = "INSERT INTO users(fName, lName, username, email, passwrd, doctor, gender, utipo)
					VALUES('$firstName','$lastName','$userName','$userEmail','$userPassword', '$uDoctor', '$userGender', '$utipo')";
				
				session_start();
				if(!isset($_SESSION["firstName"])){
					$_SESSION["firstName"] = $firstName;
				}
				if(!isset($_SESSION["lastName"])){
					$_SESSION["lastName"] = $lastName;
				}
				if(!isset($_SESSION["username"])){
					$_SESSION["username"] = $userName;
				}
				if(!isset($_SESSION["password"])){
					$_SESSION["password"] = $userPassword;
				}

				if($connection->query($sql) === TRUE){
					$response = array("MESSAGE"=>"SUCCESS");
					$connection->close();	
					return $response;
				} 
				else{
					$connection->close();
					$response = array("MESSAGE"=>"409");
					return $response;	
				}
			}
		}
		else{
			return array("MESSAGE" => "500");
		}
	}

	function attemptCookie(){
		if(isset($_COOKIE['username'])){
			$response = array('username'=>$_COOKIE['username'],'MESSAGE'=>'SUCCESS');
			return $response;
		}
		else{
			return array("MESSAGE" => "404");
		}
	}
	
	function attemptSessionStart(){
		session_start();
		if(isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['email'])){
			$response = array('uName'=>$_SESSION['username'], 'passwrd'=>$_SESSION['password'], 'email' => $_SESSION['email'],'MESSAGE'=>'SUCCESS');
			return $response;
		}
		else{
			return array("MESSAGE" => "406");
		}
	}

	function attemptSessionEnd(){
		session_start();
		if(isset($_SESSION['firstName']) && isset($_SESSION['lastName']) && isset($_SESSION['password']) && isset($_SESSION['username'])){
			unset($_SESSION['firstName']);
			unset($_SESSION['lastName']);
			unset($_SESSION['username']);
			unset($_SESSION['password']);
			session_destroy();
			$response = array('MESSAGE'=>'SUCCESS');
			return $response;
		}
		else{
			return array("MESSAGE" => "406");
		}
	}

	function attemptRetrieveData($userName, $userPassword){
		$connection = databaseConnection();

		if($connection != null){
			$sql = "SELECT * FROM Users WHERE username = '$userName' AND passwrd = '$userPassword'";
			$result = $connection->query($sql);

			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$response = array("fName"=>$row["fName"],"lName"=>$row["lName"],"email"=>$row["email"],"gender"=>$row["gender"],"doctor"=>$row["doctor"],"utipo"=>$row["utipo"],"MESSAGE"=>"SUCCESS");		
				}				
				$connection->close();
				return $response;	
			}
			else{
				$connection->close();	
				return array("MESSAGE"=>"406");
			}
		}
		else{
			return array("MESSAGE" => "500");
		}
	}

	function attemptRetrieveComments($userName){
		$connection = databaseConnection();

		if($connection != null){
			$sql = "SELECT * FROM Comments WHERE username = '$userName'";
			$result = $connection->query($sql);

			if($result->num_rows > 0){
				$c = 0;
				while($row = $result->fetch_assoc()){
					$response[$c++] = array("com"=>$row["comments"],"counter"=>$result->num_rows, "MESSAGE"=>"SUCCESS");			
				}				
				$connection->close();
				return $response;	
			}
			else{
				$connection->close();	
				return array("MESSAGE"=>"406");
			}
		}
		else{
			return array("MESSAGE" => "500");
		}
	}

	function attemptInsertComments($userN, $comment){
		$connection = databaseConnection();
		
		if($connection != null){
			$sql = "SELECT * FROM Comments";
			$result = $connection->query($sql);
			$num_rows = $result->num_rows + 1;

			$sql = "INSERT INTO Comments(username, comments, commentNumber)
				VALUES('$userN','$comment','$num_rows')";

			if($connection->query($sql) === TRUE){
				$response = array("MESSAGE"=>"SUCCESS");
				$connection->close();
				return $response;
			}
			else{
				$connection->close();	
				return array("MESSAGE"=>"406");
			}
		}
		else{
			return array("MESSAGE" => "500");
		}
	}

	function attemptSearchUsers($user, $search){
		$connection = databaseConnection();

		if($connection != null){
			$sql = "SELECT * FROM Users WHERE username = '$search' OR email = '$search'";
			$result = $connection->query($sql);

			if($result->num_rows > 0){
				$c = 0;
				while($row = $result->fetch_assoc()){
					$users[$c++] = array("fName"=>$row["fName"],"lName"=>$row["lName"],"uName"=>$row["username"]);			
				}				
			}
			else{
				
				$connection->close();	
				return array("MESSAGE"=>"NOT_FOUND");
			}
			$p = 0;
			for($i = 0; $i < $c; $i++){
				$temporalUser = $users[$i]["uName"];
				$valid = 1;

				$sql = "SELECT * FROM Friends WHERE username1 = '$temporalUser' AND username2 = '$user'";
				$result = $connection->query($sql);
				if($result->num_rows > 0){
					while($row = $result->fetch_assoc()){
						if($row["accepted"] != "0"){
							$valid = 0;
						}
					}
				}
				$sql = "SELECT * FROM Friends WHERE username1 = '$user' AND username2 = '$temporalUser'";
				$result = $connection->query($sql);
				if($result->num_rows > 0){
					$valid = 0;
				}
				
				if($valid == 1){
					$response[$p++] = array("fName"=>$users[$i]["fName"],"lName"=>$users[$i]["lName"],"uName"=>$users[$i]["uName"],"origin"=>2,"counter"=>"0", "MESSAGE"=>"SUCCESS");			
				}

			}
			$response[0]["counter"] = $p;
			$connection->close();
			return $response;
		}
		else{
			return array("MESSAGE" => "500");
		}
	}

	



?>