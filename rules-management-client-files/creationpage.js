var rules_basic = {
  condition: 'AND',
  rules: [{
    id: 'price',
    operator: 'less',
    value: 10.25
  }]
};
function IsJsonString(data) {
	try {
		JSON.parse(data);
	} catch (e) {
		return false;
	}
	return true;
}
$.get("/gsuitecalendar/ZProjectIntegration.php?method=fetchprojects",
	function(data) {
		if(IsJsonString(data)){
			var responsedata = JSON.parse(data);
			console.log("projectdata..");
			console.log(responsedata);
			$.each(responsedata, function(key, value) {   
				 $('#projectid')
					 .append($("<option></option>")
								.attr("value", value['projectid'])
								.text(value['projectname'])); 
			});
		}
	}
);

$('#builder-basic').queryBuilder({
  
  filters: [{
    id: 'summary',
    label: 'Title',
    type: 'string',
    input: 'text',
	operators: ['contains', 'not_contains','equal','not_equal']
  }, {
    id: 'description',
    label: 'Description',
    type: 'string',
    input: 'text',
	operators: ['contains', 'not_contains','equal','not_equal']
  },{
    id: 'email',
    label: 'Attendee Email',
    type: 'string',
    input: 'text',
	operators: ['contains', 'not_contains','equal','not_equal']
  }],

});

$('#btn-reset').on('click', function() {
  $('#builder-basic').queryBuilder('reset');
});

$('#btn-set').on('click', function() {
  $('#builder-basic').queryBuilder('setRules', rules_basic);
});

$('#btn-get').on('click', function() {
  var result = $('#builder-basic').queryBuilder('getRules');
  var requestdata = {};
 
  requestdata.rulename = $("#rulename").val();
  requestdata.projectid = $("#projectid").val();
  requestdata.priority = $("#priority").val();
  requestdata.description = $("#description").val();
  requestdata.emails="appadurai@bizappln.com";
  if (!$.isEmptyObject(result)&&$("#rulename").val()!=""&&$("#description").val()!="") {
    requestdata.ruledata = result;
    requeststring = JSON.stringify(requestdata, null, 2);
    console.log("Complete Requestdata..");
    console.log(requestdata);
    console.log("Complete Requestdata in string..");
    console.log(requeststring);
    $.ajax({
        contentType: 'application/json',
        data: requeststring,
        dataType: 'json',
        success: function(response) {
            console.log("response");
            console.log(response);
			alert("Watcher Rule Created Successfully..");
			window.location = "/gsuitecalendar/rules-management-client-files/dashboard.html";

        },
        error: function(reason){
            console.log("Failed..");
            console.log(reason);
			alert("Watcher Rule Created Successfully..");
			window.location = "/gsuitecalendar/rules-management-client-files/dashboard.html";

        },
        processData: false,
        type: 'POST',
        url: '/gsuitecalendar/createrule.php'
    });
  }













});