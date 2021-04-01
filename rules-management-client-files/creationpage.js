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

});

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