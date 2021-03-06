//JQuery Code Login

$(document).ready(function(){
	console.log("Good Morning, Sir");
 	
	$("#submitLogin").on("click", function(){
		var success = true;
		//console.log("You clicked the Submit Button");
		var user = $("#user").val();
		//console.log(user);
		if(user == ""){
			//console.log("Username Missing");
			$("#userSpan").text("Please provide Username");
			success &= false;
		}
		else{
			$("#userSpan").text("");
			//localStorage.setItem("username",user);
			success &= true;
		}

		var password = $("#passw").val();
		if(password == ""){
			$("#passwSpan").text("Please provide Password");			
			success &= false;
		}
		else{
			$("#passwSpan").text("");
			//localStorage.setItem("password",password);
		}

		var remember = 0;
		if($("#remember").is(':checked')){
			console.log("Remember Me checked");
			remember = 1;
		}
		
		if(user != "" && password != ""){
			var jsonToSend = {
								"uName" : user,
								"uPassword" : password,
								"remember" : remember,
								"action" : "LOGIN"
								};
			$.ajax({
				url : "./data/applicationLayer.php",
				type : "POST",
				data : jsonToSend,
				ContentType : "application/json",
				dataType : "json",
				success : function(dataReceived){
					if(dataReceived.utipo=="Paciente"){
					alert(" Welcome Back Paciente " + dataReceived.firstName
									+ " " + dataReceived.lastName);
					location.href = "home.html";
					}
					else {
						alert(" Welcome Back Dr. " + dataReceived.firstName
									+ " " + dataReceived.lastName);
						location.href = "homeDoc.html";
					}
				},
				error : function(errorMessage){
					alert(errorMessage.statusText);		//Error from PHP File
				}
			});
		}
	});

	var jsonCookie = {
						"action": "COOKIE"
						};
	$.ajax({
		url : "./data/applicationLayer.php",
		type : "POST",
		data : jsonCookie,
		ContentType : "application/json",
		dataType : "json",
		success : function(cookieJson){
			$("#user").val(cookieJson.user);
		},
		error : function(errorMessage){
			//alert(errorMessage.statusText);		//Error from PHP File
			console.log("No cookies detected");
		}
	});
	

});