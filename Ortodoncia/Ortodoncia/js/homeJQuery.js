$(document).ready(function(){

	var user = "";
	var password = "";

	var jsonToSend = {
						"action": "SESSION_START"
						};
	$.ajax({
		url : "./data/applicationLayer.php",
		type : "POST",
		data : jsonToSend,
		ContentType : "application/json",
		dataType : "json",
		success : function(sessionJson){
			user = sessionJson.uName;
			password = sessionJson.passwrd;
			uEmail = sessionJson.email;

			$("#menu > li").on("click", function(){
				$(".selected").removeClass("selected");
				var currentClass = $(this).attr("class");
				$(this).addClass("selected");
				$(".selectedSection").removeClass("selectedSection").addClass("notSelectedSection");
				$("#" + currentClass + "Section").addClass("selectedSection").removeClass("notSelectedSection");
			});

			if(user != "" && password != ""){
				var jsonToSend = {
									"uName" : user,
									"uPassword" : password,
									"action"	: "RETRIEVE_DATA"
									};
				$.ajax({
					url : "./data/applicationLayer.php",
					type : "POST",
					data : jsonToSend,
					ContentType : "application/json",
					dataType : "json",
					success : function(dataReceived){
						$("#Welcome").text("Welcome " + dataReceived.firstName + " " + dataReceived.lastName);
						newHtml = '<span class="data">';
						newHtml += dataReceived.firstName;
						newHtml += '</span>';
						$("#fName").append(newHtml);
						
						newHtml = '<span class="data">';
						newHtml += dataReceived.lastName;
						newHtml += '</span>';
						$("#lName").append(newHtml);
						
						newHtml = '<span class="data">';
						newHtml += dataReceived.email;
						newHtml += '</span>';
						$("#email").append(newHtml);
						
						newHtml = '<span class="data">';
						newHtml += dataReceived.gender;
						newHtml += '</span>';
						$("#gender").append(newHtml);
						
						newHtml = '<span class="data">';
						newHtml += dataReceived.doctor;
						newHtml += '</span>';
						$("#doctor").append(newHtml);
						

						},
						
					error : function(errorMessage){
						alert(errorMessage.statusText);		//Error from PHP File
					}
				});
				
				//Retrieve User's Comments
				var jsonToSend = {
									"uName" : user,
									"action": "RETRIEVE_COMMENTS"
									};
				$.ajax({
					url : "./data/applicationLayer.php",
					type : "POST",
					data : jsonToSend,
					ContentType : "application/json",
					dataType : "json",
					success : function(dataReceived){
						var count = dataReceived[0].counter;
						for(var i = 0; i < count; i++){
							var newHtml = '<div>';
							newHtml += '<div class="comments">' + dataReceived[i].com + '</div>';
							newHtml += '</div>';
							$("#commentsDatabase").append(newHtml);
						}
					},
						
					error : function(errorMessage){
					}
				});


				$("#submitComment").on("click", function(){
					var commentText = $("#comments").val();
					var newHtml = '<div id="nComment">';
					if(commentText != " " && commentText != "" && commentText != 'undefined'){
						$("#emptySpan").css('color', 'white');
						console.log(user);
						console.log(commentText);
						newHtml += commentText + '</div>';
						
						var jsonToSend = {
											"uName" : user,
											"com"  : commentText,
											"action": "INSERT_COMMENT"
											};
						$.ajax({
							url : "./data/applicationLayer.php",
							type : "POST",
							data : jsonToSend,
							ContentType : "application/json",
							dataType : "json",
							success : function(dataReceived){
								console.log(dataReceived.comm);						},
							error : function(errorMessage){
								alert(errorMessage.statusText);		//Error from PHP File
							}
						});
					}
					else{
						$("#emptySpan").css('color', 'red');
					}
					$("#nComment").append(newHtml);
					$("#comments").val(" ");
				});

				$("#submitSearch").on("click", function(){
					var searchText = $("#searchInput").val();

					var newHtml = '<div class="usersFound">';
					if(searchText != " " && searchText != "" && searchText != 'undefined'){
						$("#emptySearch").css('color', 'white');
						newHtml += searchText + '</div>';
						
						var jsonToSend = {
											"uName" : user,
											"email"	: uEmail,
											"search"  : searchText,
											"action": "SEARCH_USER"
											};
						$.ajax({
							url : "./data/applicationLayer.php",
							type : "POST",
							data : jsonToSend,
							ContentType : "application/json",
							dataType : "json",
							success : function(dataReceived){
								$(".userSearch").remove();
								$(".sendnote").remove();
								var count = dataReceived[0].counter;
								for(var i = 0; i < count; i++){
									var newHtml = '<div>';
									newHtml += '<span class="userSearch">' + dataReceived[i].firstName + " " + dataReceived[i].lastName + "   ";
									newHtml += '<input class="writenote" id="notetext" type="text" value="Write medical notes"/>' +'</span>';
									newHtml += '<input class="sendnote" id="'+ dataReceived[i].userName +'" type="submit" value="Send"/>' +'</span>';
									newHtml += '<input class="deleteuser" id="'+ dataReceived[i].userName +'" type="submit" value="Delete"/>' +'</span>';
									newHtml += '</div>';
									$("#searchUser").append(newHtml);

								}	
								if(count == 0){
									var newHtml = '<div>';
									newHtml += '<div class="userSearch"  style="color:red";>' + "User Not Found" + '</div>';
									newHtml += '</div>';
									$("#searchUser").append(newHtml);


								}					
							},
							error : function(errorMessage){
								alert(errorMessage.statusText);		//Error from PHP File
							}
						});
					}
					else{
						$("#emptySearch").css('color', 'red');
					}
					$("#searchInput").val("");
				});

				$("#searchUser").on("click", ".sendnote", function(){			
					var sendnoteClick = $(this).attr("id");
					var com = $(notetext).val();
					console.log(com);
					console.log("username="+sendnoteClick);
					$(".userSearch").remove();
					$(".sendnote").remove();
					$(".deleteuser").remove();
					
					var jsonToSend = {
									"uName" : sendnoteClick,
									"com" : com,
									"action": "INSERT_COMMENT"
									};
					$.ajax({
						url : "./data/applicationLayer.php",
						type : "POST",
						data : jsonToSend,
						ContentType : "application/json",
						dataType : "json",
						success : function(dataReceived){
							alert(dataReceived.comm);
						},
						error : function(errorMessage){
							alert(errorMessage.statusText);
						}
					});	
				});
				$("#searchUser").on("click", ".deleteuser", function(){			
					var sendnoteClick = $(this).attr("id");
					console.log("username="+sendnoteClick);
					$(".userSearch").remove();
					$(".sendnote").remove();
					$(".deleteuser").remove();
					
					var jsonToSend = {
									"uName" : sendnoteClick,
									"action": "DELETE_USER"
									};
					$.ajax({
						url : "./data/applicationLayer.php",
						type : "POST",
						data : jsonToSend,
						ContentType : "application/json",
						dataType : "json",
						success : function(dataReceived){
							alert(dataReceived.comm);
						},
						error : function(errorMessage){
							alert(errorMessage.statusText);
						}
					});	
				});
			}
		},
		error : function(errorMessage){
			alert("No Session has been started");
			location.href = "index.html";
		}
	});

	$("#logoutButton").on("click", function(){
		console.log("Logout Clicked");
		var jsonToSend = {
						"action": "SESSION_END"
						};
		$.ajax({
			url : "./data/applicationLayer.php",
			type : "POST",
			data : jsonToSend,
			ContentType : "application/json",
			dataType : "json",
			success : function(dataReceived){
				alert(dataReceived.message);
				location.href = "index.html";
			},
			error : function(errorMessage){
				alert(errorMessage.statusText);
			}
		});
	});
});