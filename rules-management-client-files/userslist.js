var app = angular.module('usersapp', []);

app.controller('userlistctrl', function($scope, $http, $window) {

function IsJsonString(data) {
	try {
		JSON.parse(data);
	} catch (e) {
		return false;
	}
	return true;
}
$.get("/gsuitecalendar/usersendpoint.php?method=fetchuser",
	function(data) {
		var responsedata = JSON.parse(data);
		$scope.usersdata = responsedata.data;
		console.log($scope.usersdata);
		$scope.$apply();
	}
);

$scope.adduser=function(email){
	console.log(email);
	if(!email){
		alert("Enter the Valid Email Address..");
	}
	else{
		$.get("/gsuitecalendar/usersendpoint.php?method=adduser&useremail="+email,
			function(data) {
				if(data==''){
					console.log("Data is Empty now..");
					alert("Provided Email address not exist in GSuite..");
				}
				else{
				
				
				if(IsJsonString(data)){
					var addrequestdata = JSON.parse(data);
					console.log("addrequestdata"); 
					console.log(addrequestdata);
					if(addrequestdata.status=="failure"&&addrequestdata.reason.includes("already exist")){
						alert(addrequestdata.reason);
					}
					if(addrequestdata.status=="failure"&&!addrequestdata.reason.includes("already exist")){
						alert("User Email not available in GSuite..");
					}
				}
				else{
					alert("User Email Added Successfully..");
						$('#usersmodal').modal('toggle');
						$window.location.reload();
				}
				
				
				}
			}
		);
	}
}

$scope.removeuser=function(email){
	console.log(email);
	var confirmation = prompt("Please type 'DELETE' and Then Click 'OK' to Confirm Deleting  the User : "+email);
	if (confirmation == "DELETE") {
		$.get("/gsuitecalendar/usersendpoint.php?method=removeuser&useremail="+email,
			function(data) {
				//var removeuserrequestdata = JSON.parse(data);
				alert("User has been Removed..");
				$window.location.reload();
			}
		);
	}
}


//appadurai@yaalidatrixproj.com

});