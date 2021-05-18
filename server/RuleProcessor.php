<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'config.inc.php');
   $operators = array(
			// a => rulevalue b =>eventdatavalue
            'equal' => function ($a, $b) {
                return $a == $b;
            },            
            'not_equal' => function ($a, $b) {
                return $a != $b;
            },
			'contains' => function ($a, $b) {
				$a=strtolower($a);
				$b=strtolower($b);
				
				trigger_error(' Contains check ' .$a . $b .strpos($a,$b));
                return strpos($a,$b) !== false;
            },
			'not_contains' => function ($a, $b) {
				$a=strtolower($a);
				$b=strtolower($b);
				trigger_error('Not Contains check ' .$a . $b .strpos($a,$b));
               return strpos($a, $b) == false;
					
            });

$nestedlevel1json='{
  "condition": "OR",
  "rules": [
    {
      "id": "summary",
      "field": "summary",
      "type": "string",
      "input": "text",
      "operator": "contains",
      "value": "Test"
    },
    {
      "id": "description",
      "field": "description",
      "type": "string",
      "input": "text",
      "operator": "contains",
      "value": "Note"
    },
    {
      "id": "email",
      "field": "email",
      "type": "string",
      "input": "text",
      "operator": "contains",
      "value": "appadurai"
    }
  ],
  "valid": true
}';

$nestedlevel2json='{
  "condition": "AND",
  "rules": [
    {
      "condition": "AND",
      "rules": [
        {
          "id": "summary",
          "field": "summary",
          "type": "string",
          "input": "text",
          "operator": "equal",
          "value": "Test-Token"
        },
        {
          "id": "description",
          "field": "description",
          "type": "string",
          "input": "text",
          "operator": "not contains",
          "value": "This is a test meeting"
        }
      ]
    },
    {
      "condition": "AND",
      "rules": [
        {
          "id": "description",
          "field": "description",
          "type": "string",
          "input": "text",
          "operator": "contains",
          "value": "Test"
        },
        {
          "id": "email",
          "field": "email",
          "type": "string",
          "input": "text",
          "operator": "contains",
          "value": "appadurai"
        }
      ]
    }
  ],
  "valid": true
}';

$nestedlevel3json='{
  "condition": "AND",
  "rules": [
    {
      "id": "summary",
      "field": "summary",
      "type": "string",
      "input": "text",
      "operator": "contains",
      "value": "TestAppa"
    },
    {
      "condition": "OR",
      "rules": [
        {
          "id": "email",
          "field": "email",
          "type": "string",
          "input": "text",
          "operator": "contains",
          "value": "appadi"
        },
        {
          "id": "email",
          "field": "email",
          "type": "string",
          "input": "text",
          "operator": "contains",
          "value": "appadurai"
        },
        {
          "condition": "OR",
          "rules": [
            {
              "id": "description",
              "field": "description",
              "type": "string",
              "input": "text",
              "operator": "contains",
              "value": "Note"
            },
            {
              "id": "description",
              "field": "description",
              "type": "string",
              "input": "text",
              "operator": "contains",
              "value": "Test"
            }
          ]
        }
      ]
    }
  ],
  "valid": true
}';
$datastr='{
	"anyoneCanAddSelf": null,
	"attendeesOmitted": null,
	"colorId": null,
	"created": "2021-04-30T01:35:42.000Z",
	"description": "This meeting is intended to test the next synch token \nEvent Addition",
	"endTimeUnspecified": null,
	"etag": "\"3239493690712000\"",
	"guestsCanInviteOthers": null,
	"guestsCanModify": null,
	"guestsCanSeeOtherGuests": null,
	"hangoutLink": "https:\/\/meet.google.com\/eqq-hqfs-wnn",
	"htmlLink": "https:\/\/www.google.com\/calendar\/event?eid=NTFxbmNidDl2aTNiam92cjdpZTZrM20ya3EgYXBwYWR1cmFpQHlhYWxpZGF0cml4cHJvai5jb20",
	"iCalUID": "51qncbt9vi3bjovr7ie6k3m2kq@google.com",
	"id": "51qncbt9vi3bjovr7ie6k3m2kq",
	"kind": "calendar#event",
	"location": "Chennai, Tamil Nadu, India",
	"locked": null,
	"privateCopy": null,
	"recurrence": null,
	"recurringEventId": null,
	"sequence": 0,
	"status": "confirmed",
	"summary": "Test-Sync-Token",
	"transparency": null,
	"updated": "2021-04-30T01:40:45.356Z",
	"visibility": null,
	"creator": {
		"displayName": null,
		"email": "appadurai@yaalidatrixproj.com",
		"id": null,
		"self": true
	},
	"organizer": {
		"displayName": null,
		"email": "appadurai@yaalidatrixproj.com",
		"id": null,
		"self": true
	},
	"start": {
		"date": null,
		"dateTime": "2021-05-01T10:30:00+05:30",
		"timeZone": null
	},
	"end": {
		"date": null,
		"dateTime": "2021-05-01T11:30:00+05:30",
		"timeZone": null
	},
	"attendees": [{
		"additionalGuests": null,
		"comment": null,
		"displayName": null,
		"email": "appadurai@bizappln.com",
		"id": null,
		"optional": null,
		"organizer": null,
		"resource": null,
		"responseStatus": "accepted",
		"self": null
	}, {
		"additionalGuests": null,
		"comment": null,
		"displayName": null,
		"email": "appadurai@yaalidatrixproj.com",
		"id": null,
		"optional": null,
		"organizer": true,
		"resource": null,
		"responseStatus": "accepted",
		"self": true
	}, {
		"additionalGuests": null,
		"comment": null,
		"displayName": null,
		"email": "appadiappa@gmail.com",
		"id": null,
		"optional": null,
		"organizer": null,
		"resource": null,
		"responseStatus": "needsAction",
		"self": null
	}],
	"conferenceData": {
		"conferenceId": "eqq-hqfs-wnn",
		"notes": null,
		"signature": "ACn9hYH8B15SKBFJA\/XN7lLZr8vo",
		"entryPoints": [{
			"accessCode": null,
			"entryPointFeatures": null,
			"entryPointType": "video",
			"label": "meet.google.com\/eqq-hqfs-wnn",
			"meetingCode": null,
			"passcode": null,
			"password": null,
			"pin": null,
			"regionCode": null,
			"uri": "https:\/\/meet.google.com\/eqq-hqfs-wnn"
		}, {
			"accessCode": null,
			"entryPointFeatures": null,
			"entryPointType": "more",
			"label": null,
			"meetingCode": null,
			"passcode": null,
			"password": null,
			"pin": "8376119114154",
			"regionCode": null,
			"uri": "https:\/\/tel.meet\/eqq-hqfs-wnn?pin=8376119114154"
		}, {
			"accessCode": null,
			"entryPointFeatures": null,
			"entryPointType": "phone",
			"label": "+1 404-721-2193",
			"meetingCode": null,
			"passcode": null,
			"password": null,
			"pin": "122339953",
			"regionCode": "US",
			"uri": "tel:+1-404-721-2193"
		}],
		"conferenceSolution": {
			"iconUri": "https:\/\/fonts.gstatic.com\/s\/i\/productlogos\/meet_2020q4\/v6\/web-512dp\/logo_meet_2020q4_color_2x_web_512dp.png",
			"name": "Google Meet",
			"key": {
				"type": "hangoutsMeet"
			}
		}
	},
	"reminders": {
		"useDefault": true
	}
}';
$datajson=json_decode($datastr,true);
trigger_error('Attendees ' . json_encode($datajson['attendees']));
//$ruleexpression=json_decode($nestedlevel1json,true);
$ruleexpression=json_decode($nestedlevel3json,true);

//echo $ruleexpression['condition'];
$rulearr=$ruleexpression['rules'];
/*
foreach($rulearr  as $nestedruleexpression)
{
	$nestedrule=$nestedruleexpression['rules'];
	$condition=$nestedruleexpression['condition'];
	evaluateNestedRule($nestedrule,$condition,$datajson);
	
}
*/

/*
$ismatch=parseAndEvaluate($ruleexpression,$datajson);
if($ismatch)
{
	trigger_error('Rules Matched for dataset');
}else{
	trigger_error('Rules Unmatched');
}
*/

function parseAndEvaluate($ruleexpression,$data)
{
	$nestedresultarr=array();
	$ruleresult=null;
	
	$rule=$ruleexpression['rules'];
	$condition=$ruleexpression['condition'];
	
	foreach($rule as $ruleelement)
	{
		$isNestedExpression=checkIsExpression($ruleelement);
		if($isNestedExpression)
		{
			$ruleresult=parseAndEvaluate($ruleelement,$data);
			array_push($nestedresultarr,$ruleresult);
			trigger_error('Rule Result ' . $ruleresult);			
		}else{					
			$ruleresult=evaluateNestedRule($ruleelement,$data);
			array_push($nestedresultarr,$ruleresult);
			trigger_error('Evaluate Expression Result ' . $ruleresult);
			
		}
	}
	
	if($condition == 'AND')
	{
		$ruleresult=!in_array(false,$nestedresultarr);
	}else if($condition == 'OR'){
		$ruleresult=in_array(true,$nestedresultarr);
	}else{
		trigger_error('Condition is empty as this might be the nestedrules without condition' . $condition);
	}
	trigger_error('***********************Rule Result on Exit ' . $condition . ' ' . json_encode($nestedresultarr). $ruleresult.'***************');
	return $ruleresult;
}

function checkIsExpression($ruleelement)
{	
		if(array_key_exists("condition",$ruleelement))
		{
			return true;		
		}
	
	return false;
}

function evaluateNestedRule($nestedexpression,$data)
{
	global $operators;
	$result=false;
		 $field=$nestedexpression['field'];
		 $operator=$nestedexpression['operator'];
		 $rulefieldvalue=$nestedexpression['value'];
		 $operation=$operators[$operator];
		 if($field != 'email')
			{
				$eventfieldvalue=$data[$field];
				$result=call_user_func_array($operation, array($eventfieldvalue,$rulefieldvalue));
				
				if($result)
				{
					trigger_error('Entry Matched ' . $field . ' ' . $eventfieldvalue .' ' . $operator. ' ' . $rulefieldvalue  );
				}else{
					trigger_error('Entry Unmatched '  . $field . ' ' . $eventfieldvalue .' ' . $operator. ' ' . $rulefieldvalue );
				}
			}else {
				$attendees=$data['attendees'];
				
				foreach($attendees as $attendeeinfo)
				{
					$eventemail=$attendeeinfo['email'];					
					$tempresult=call_user_func_array($operation, array($eventemail,$rulefieldvalue));
					if($tempresult)
					{
						$result=true;
						trigger_error('Email Entry Matched ' . $field . ' ' . $eventemail .' ' . $operator. ' ' . $rulefieldvalue   );
					}else{
						trigger_error('Email Evaulate Unmatched '  . $field . ' ' . $eventemail .' ' . $operator.  ' ' . $rulefieldvalue  );
					}
				}				
				
			}
	
		return $result;
}



?>


