var rules_basic = {
  condition: 'AND',
  rules: [{
    id: 'price',
    operator: 'less',
    value: 10.25
  }]
};
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
  if (!$.isEmptyObject(result)) {
      requestdata.rulesdata = result;
      
      requeststring = JSON.stringify(requestdata, null, 2);
    console.log("Complete Requestdata..");
    console.log(requestdata);
    console.log("Complete Requestdata in string..");
    console.log(requeststring);
    /*$.ajax({
        contentType: 'application/json',
        data: requeststring,
        dataType: 'json',
        success: function(response) {
            console.log("response");
            console.log(response);
        },
        error: function(reason){
            console.log("Failed..");
            console.log(reason);
        },
        processData: false,
        type: 'POST',
        url: 'https://crmprojects.bizappln.com/gcal-integration/gcalserver/rar-files-sent-locally/server/RuleProcessor.php'
    });*/
  }
});