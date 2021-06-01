<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'UserConfiguration.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'RuleConfiguration.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'ZProjectIntegration.php');
$ruleconfigstr='{
  "rulename": "Sample Rule",
  "projectid": "1600500000001081005",
  "priority": "1",
  "description": "Sample Description for this rule..",
  "emails":"appadurai@bizappln.com",
  "ruledata": {
    "condition": "AND",
    "rules": [
      {
        "id": "description",
        "field": "description",
        "type": "string",
        "input": "text",
        "operator": "contains",
        "value": "Project"
      },
      {
        "id": "email",
        "field": "email",
        "type": "string",
        "input": "text",
        "operator": "equal",
        "value": "appadurai@bizappln.com"
      },
      {
        "condition": "OR",
        "rules": [
          {
            "condition": "OR",
            "rules": [
              {
                "id": "summary",
                "field": "summary",
                "type": "string",
                "input": "text",
                "operator": "contains",
                "value": "Notification"
              },
              {
                "id": "email",
                "field": "email",
                "type": "string",
                "input": "text",
                "operator": "contains",
                "value": "zylker"
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
                "operator": "not_contains",
                "value": "google"
              },
              {
                "id": "summary",
                "field": "summary",
                "type": "string",
                "input": "text",
                "operator": "equal",
                "value": "Test"
              }
            ]
          }
        ]
      }
    ],
	
    "valid": true
  }
}';

/* New User Flow
1. Create a test user
2. Watch the User Calendar
3. Create a Rule
4. Create a gsuite event that matches the Rule
5. Verify the result that task  is created in Zoho Project for the accepted User
*/

testNewUserFlow();
function testNewUserFlow()
{
	global $ruleconfigstr;
	$testuser="appadurai@yaalidatrixproj.com";
	//stopWatcher($testuser,'4ede92f10b83c7da5fbc'); // The watcherid is stored in db table calendarconfig
	//$response=addUser($testuser);
	//trigger_error('Client Recieved ' . json_encode($response));
	//trigger_error('Fetch Zoho Projects ' . fetchZohoProjects());
	//saveRule($ruleconfigstr);
	// get channelid from the calendarconfig and pass it here
	testEventNotification();
}

function testEventNotification()
{
	$endpoint="http://localhost/gsuitecalendar/NotificationReciever.php";
	$method='POST';
	$dummypost='';
	$downch = curl_init();
		$headers = array(
				'X-Goog-Channel-Token: appadurai@yaalidatrixproj.com',
				'X-Goog-Resource-Uri: https://www.googleapis.com/calendar/v3/calendars/primary/events?alt=json',
				'X-Goog-Resource-Id: I305hkUAIzQQuG5W4GEYzg86HN4',
				'X-Goog-Message-Number: 2113378',
				'X-Goog-Resource-State: exists',
				'X-Goog-Channel-Expiration: Tue, 8 June 2021 01:43:59 GMT',
				'X-Goog-Channel-Id: 078c8451d36b4dc702a2' //use the latest channelid stored in calendarconfig table
		);

		curl_setopt($downch, CURLOPT_URL, $endpoint);
		curl_setopt($downch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($downch, CURLOPT_CUSTOMREQUEST, method);
		
		curl_setopt($downch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($downch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($downch, CURLOPT_TIMEOUT, 60);
		curl_setopt($downch, CURLOPT_VERBOSE, 1);
		curl_setopt($downch, CURLOPT_POSTFIELDS, $fieldsString);
		$response=curl_exec($downch);
		trigger_error('Post Response ' . $response);

}