var app = angular.module('rulesapp', []);

app.controller('rulesctrl', function($scope, $http, $window) {
function IsJsonString(data) {
	try {
		JSON.parse(data);
	} catch (e) {
		return false;
	}
	return true;
}
$.get("/gsuitecalendar/RuleConfiguration.php?method=fetchrules",
	function(data) {
		if(IsJsonString(data)){
			var responsedata = JSON.parse(data);
			$scope.rulesdata = responsedata;
			console.log("RulesData..");
			console.log($scope.rulesdata);
			$scope.$apply();	
		}
	}
);
$scope.viewrule = function(ruleindex){
	var ruledata = JSON.parse($scope.rulesdata.data[ruleindex].criteria);
console.log(ruledata);
$('#builder-basic').queryBuilder("destroy");
$('#builder-basic').queryBuilder({
      filter_readonly: true,
    operator_readonly: true,
    value_readonly: true,
  condition_readonly: true,
  no_add_rule: true,
  no_add_group: true,
  no_delete: true,
  filters: [{
    id: 'summary',
    label: 'Summary',
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
  rules: ruledata

});
$("#criteriamodal").modal()
	
}



//appadurai@yaalidatrixproj.com

});









































var rules_basic = {
  "condition": "AND",
  "rules": [
    {
      "id": "title",
      "field": "title",
      "type": "string",
      "input": "text",
      "operator": "contains",
      "value": "New Title"
    },
    {
      "condition": "AND",
      "rules": [
        {
          "id": "email",
          "field": "email",
          "type": "string",
          "input": "text",
          "operator": "not_equal",
          "value": "marshall@yopmail.com"
        },
        {
          "condition": "OR",
          "rules": [
            {
              "id": "email",
              "field": "email",
              "type": "string",
              "input": "text",
              "operator": "equal",
              "value": "jhonny@yopmail.com"
            },
            {
              "id": "email",
              "field": "email",
              "type": "string",
              "input": "text",
              "operator": "equal",
              "value": "jhon@yopmail.com"
            }
          ]
        }
      ]
    },
    {
      "condition": "OR",
      "rules": [
        {
          "id": "description",
          "field": "description",
          "type": "string",
          "input": "text",
          "operator": "not_contains",
          "value": "ball room"
        },
        {
          "id": "description",
          "field": "description",
          "type": "string",
          "input": "text",
          "operator": "contains",
          "value": "pizza"
        }
      ]
    }
  ],
  "valid": true
};
/*$('#builder-basic').queryBuilder({
  
  filters: [{
    id: 'title',
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
  rules: rules_basic

});*/

$('#btn-reset').on('click', function() {
  $('#builder-basic').queryBuilder('reset');
});

$('#btn-set').on('click', function() {
  $('#builder-basic').queryBuilder('setRules', rules_basic);
});

$('#btn-get').on('click', function() {
  var result = $('#builder-basic').queryBuilder('getRules');
  
  if (!$.isEmptyObject(result)) {
    console.log(JSON.stringify(result, null, 2));
  }
});