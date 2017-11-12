//JQuery Code Registration

$(document).ready(function(){

	$("#registrationSubmit").on("click", function(){
		console.log("Register Clicked");
		var success = true;
		
		var fName = $("#fName").val();
		if(fName == ""){
			$("#nameError").text("Please provide First Name");
			success &= false;
		}
		else{
			$("#nameError").text("");
			success &= true;
		}

		var lName = $("#lName").val();
		if(lName == ""){
			$("#lNameError").text("Please provide Last Name");
			success &= false;
		}
		else{
			$("#lNameError").text("");
			success &= true;
		}

		var uName = $("#uName").val();
		if(uName == ""){
			$("#userError").text("Please provide Username");
			success &= false;
		}
		else{
			$("#userError").text("");
			success &= true;
		}
		
		var uDoc = $("#uDoc").val();
		if(uDoc == ""){
			$("#docError").text("Please provide Doctor name");
			success &= false;
		}
		else{
			$("#docError").text("");
			success &= true;
		}

		var uMail = $("#uMail").val();
		var validEM = false;
		if(uMail == ""){
			$("#mailError").text("Please provide an e-mail");
			success &= false;
		}
		else{
			$("#mailError").text("");
			success &= true;
			validEM = true;
		}
	  	
	  	var valid = false;
		for (var i = 0, len = uMail.length; i < len; i++){
	  		if(uMail[i] == '@'){
	  			valid = true;
	  		}
		}
		
		if((!valid || uMail.indexOf(".com") == -1)&& validEM){
			$("#mailError").text("Please provide a valid e-mail");
		}
		success &= valid;

		var uPass = $("#uPass").val();
		if(uPass == ""){
			$("#passError").text("Please provide Password");
			success &= false;
			bothPasswords &= false;
		}
		else{
			$("#passError").text("");
			success &= true;
		}



		var utipo = $("#utipo").val();
		if(utipo == null){
			$("#utipoMissing").text("Please select User Type");
		}
		else{
			$("#utipoMissing").text("");
		}

		var utipo = $("#utipo").val();
		if(utipo == null){
			$("#typeError").text("Please select user Type");
			success &= false;
		}
		else{
			$("#typeError").text("");
			success &= true;
		}

		var gender = $("input[name=gender]").is(":checked");
		if(!gender){
			$("#genderSpan").text("Please select Gender");
		}
		else{
			$("#genderSpan").text("");
		}
		success &= gender;
		
		var genderVal = $("input[name=gender]:checked").val();
		if(genderVal == '1'){
			gender = 'M';
		}
		else{
			gender = 'F';
		}
		
		var firstname = fName;
		var lastname = lName;
		var username = uName;
		var email = uMail;
		var password = uPass;
		var doctor = uDoc;
		var utipo = $("#utipo").find(":selected").text();
		
		if(success){
			var jsonToSend = {
								"fName" : firstname,
								"lName" : lastname,
								"uName" : username,
								"uEmail": email,
								"uPassword" : password,
								"uDoc": doctor,
								"uGender" : gender,
								"utipo" : utipo,
								"action"   : "REGISTRATION"
								};
			$.ajax({
				url : "./data/applicationLayer.php",
				type : "POST",
				data : jsonToSend,
				ContentType : "application/json",
				dataType : "json",
				success : function(dataReceived){
					alert(dataReceived.message);
					location.href = "home.html";
				},
				error : function(errorMessage){
					alert(errorMessage.statusText);		//Error from PHP File
				}
			});
		}
	});
	
	$("#cancelButton").on("click", function(){
		//console.log("Cancel Clicked");
		location.href = "index.html";
	});
});