var app = angular.module('usersapp', []);

app.controller('userlistctrl', function($scope, $http, $window) {

$.get("../server/usersendpoint.php?method=fetchuser",
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
		$.get("../server/usersendpoint.php?method=adduser&useremail="+email,
			function(data) {
				if(data==''){
					console.log("Data is Empty now..");
					alert("Provided Email address not exist in GSuite..");
				}
				else{
				var addrequestdata = JSON.parse(data);
				console.log("addrequestdata"); 
				console.log(addrequestdata);
					if(addrequestdata.status=="failure"){
						alert(addrequestdata.reason);
					}
					if(addrequestdata.status=="success"){
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
	$.get("../server/usersendpoint.php?method=removeuser&useremail="+email,
		function(data) {
			var removeuserrequestdata = JSON.parse(data);
			console.log("removeuserrequestdata"); 
			console.log(removeuserrequestdata);
		}
	);
}


//appadurai@yaalidatrixproj.com

});