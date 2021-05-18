<?php
//echo phpinfo();
require_once(__DIR__.DIRECTORY_SEPARATOR.'config.inc.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'EventManagement.php');

include_once __DIR__ . '/google-api-php-client--PHP8.0/vendor/autoload.php';

use ReallySimpleJWT\Token;
putenv('GOOGLE_APPLICATION_CREDENTIALS='.__DIR__.'/gsuitecredentials.json');
//putenv('GOOGLE_APPLICATION_CREDENTIALS='.__DIR__.'/keywordrepo-8ed71daeee65.json');
//doFullCalendarEventsSynch('appadurai@yaalidatrixproj.com');
//checkUserExistGsuite('paolo@bytekmarketing.it');
//connectGoogleClient();
//setWatcher('appadurai@yaalidatrixproj.com','20fdedbf0-a845-11e3-1515e2-0800200c9a6689111');
//fetchEventsFromSyncToken('primary','CLD1k4zqpPACELD1k4zqpPACGAUgyv7jrwE=','appadurai@yaalidatrixproj.com'); //CLD1k4zqpPACELD1k4zqpPACGAUgyv7jrwE=

//stopWatcher('appadurai@yaalidatrixproj.com','20fdedbf0-a845-11e3-1515e2-0800200c9a6689111');

function connectGoogleClient()
{

	$client = new Google\Client();
	$client->useApplicationDefaultCredentials();
	$client->setApplicationName("Client_Library_Examples");
	$client->setSubject('appadurai@yaalidatrixproj.com');
	//$client->setSubject('paolo@bytekmarketing.it');
	$client->setScopes(['https://www.googleapis.com/auth/calendar','https://www.googleapis.com/auth/calendar.events','https://www.googleapis.com/auth/admin.directory.user']);	
	//$client->fetchAccessTokenWithAssertion();
	$service= new Google_Service_Directory($client);
	$users = $service->users;
	trigger_error('Users ' . $users);
	$temp=array();
	try{
	$userinfo=$users->get('appadur12ai@yaalidatrixproj.com',$temp);
	trigger_error('User List ' . $userinfo->getCustomerId());

	}catch(Exception $e)
	{
		trigger_error($e->getMessage());
	}
		if(true)
	{
		return;
	}

$calendarId = 'primary';
$optParams = array(
  'maxResults' => 10,
  'singleEvents' => true  
);
//'timeMin' => date('c'),  'orderBy' => 'startTime',

$results = $service->events->listEvents($calendarId, $optParams);

trigger_error('Event Next Sync Token ' . $results->getNextSyncToken( ));
trigger_error('Event Next Page ' . $results->getNextPageToken( ));
trigger_error('Event Result ' . $results);
$events = $results->getItems();
trigger_error('Events ' . json_encode($events));
}



function stopWatcher($useremail,$watcherid)
{
	try{

		$client = new Google\Client();
		$client->useApplicationDefaultCredentials();
		$client->setApplicationName("Client_Library_Examples");
		$client->setSubject($useremail);	
		$client->setScopes(['https://www.googleapis.com/auth/calendar','https://www.googleapis.com/auth/calendar.events','https://www.googleapis.com/auth/admin.directory.user']);
		$calendarService = new Google_Service_Calendar($client);
		$channels = $calendarService->channels; //Google_Service_Calendar_Resource_Channels
		$channel =  new Google_Service_Calendar_Channel($client);
			$channel->setId($watcherid);
			$channel->setType('web_hook');
			$channel->setResourceId($useremail.'_primary');
			$channel->setToken($useremail);
			//$channel->setAddress('https://d40okpmrbb5wv.cloudfront.net/gsuitecalendar/NotificationReciever.php');
		$optparams=array();
		$stopresult=$channels->stop($channel,$optparams);
		trigger_error('Google Calendar Channels for user ' . $stopresult);		
		trigger_error('Google Calendar Channels for user ' . $channels);
	}
	catch(Exception $e)
	{
		trigger_error('Stop Watcher failed with message ' . $e->getMessage());
		$result['status']='failure';
		$result['error']=$e->getMessage();		
	}	
}

function setWatcher($useremail,$watcherid)
{
	$result=array();
	try{
		$client = new Google\Client();
		$client->useApplicationDefaultCredentials();
		$client->setApplicationName("Client_Library_Examples");
		$client->setSubject($useremail);
		//$client->setSubject('paolo@bytekmarketing.it');
		$client->setScopes(['https://www.googleapis.com/auth/calendar','https://www.googleapis.com/auth/calendar.events','https://www.googleapis.com/auth/admin.directory.user']);	
		$service = new Google_Service_Calendar($client);
		$channel =  new Google_Service_Calendar_Channel($client);
		$channel->setId($watcherid);
		$channel->setType('web_hook');
		$channel->setResourceId($useremail.'_primary');
		$channel->setToken($useremail);
		$channel->setAddress('https://d40okpmrbb5wv.cloudfront.net/gsuitecalendar/NotificationReciever.php');
		trigger_error('Event class ' . $service->events); // Google_Service_Calendar_Resource_Events
		trigger_error('Calendar List ' . $service->calendarList);
		$calendarlist=$service->calendarList->listCalendarList(); // Google_Service_Calendar_CalendarList
		trigger_error('Calendar List ' . json_encode($calendarlist->items));
		
		$watchEvent = $service->events->watch('primary', $channel);
		trigger_error('Watch Response ' . json_encode($watchEvent));
	}catch(Exception $e)
	{
		trigger_error($e->getMessage());
		$result['status']='failure';
		$result['error']=$e->getMessage();
		
	}
}


function checkUserExistGsuite($useremail)
{
	$result=array('status'=>'','userexist'=>'','guserid'=>'','error'=>'');
	$client = new Google\Client();
	$client->useApplicationDefaultCredentials();
	$client->setApplicationName("Client_Library_Examples");
	$client->setSubject(SUBJECTEMAIL);
	//$client->setSubject('paolo@bytekmarketing.it');
	$client->setScopes(['https://www.googleapis.com/auth/calendar','https://www.googleapis.com/auth/calendar.events','https://www.googleapis.com/auth/admin.directory.user']);	
	//$client->fetchAccessTokenWithAssertion();
	$service= new Google_Service_Directory($client);
	$users = $service->users;
	trigger_error('Users ' . $users);
	$temp=array();
	try{

		$userinfo=$users->get($useremail,$temp); //Google_Service_Directory_User
		$gsuiteid=$userinfo->getCustomerId();
		if($gsuiteid != null)
		{
			trigger_error('User Id in Gsuite ' . $userinfo->getCustomerId());
			$result['status']='success';
			$result['userexist']='true';
			$result['guserid']=$gsuiteid;
			
		}else{
			$result['status']='success';
			$result['userexist']='false';
			
		}
		
	}catch(Exception $e)
	{
		trigger_error($e->getMessage());
		$result['status']='failure';
		$result['error']=$e->getMessage();
		
	}
	return $result;
}

function doFullCalendarEventsSynch($useremail)
{

	$result=array();
	$client = new Google\Client();
	$client->useApplicationDefaultCredentials();
	$client->setApplicationName("Client_Library_Examples");
	//$client->setSubject('appadurai@yaalidatrixproj.com');
	$client->setSubject($useremail);
	$client->setScopes(['https://www.googleapis.com/auth/calendar','https://www.googleapis.com/auth/calendar.events']);	
	//$client->fetchAccessTokenWithAssertion();
	$service = new Google_Service_Calendar($client);
	$calendarId = 'primary';
	$optParams = array(
	  'maxResults' => 2000,
	  'singleEvents' => true  
	);
	/*
	$calendarsList=$service->calendarList->listCalendarList()->getItems( );
	trigger_error('Calendars ' . json_encode($calendarsList));
	foreach($calendarsList as $calendar)
	{
		trigger_error('Calendar Id ' . $calendar->getId());
	}
	*/

	$nextpage=null;
	try{
		do{
			if($nextpage != null)
			{
				$optParams['pageToken']=$nextpage;
			}
			$results = $service->events->listEvents($calendarId, $optParams);
			$nextpage=$results->getNextPageToken( );
			if(empty($nextpage))
			{
				$nexteventsynctoken=$results->getNextSyncToken();
				$result['status']='success';
				$result['nexteventsynctoken']=$nexteventsynctoken;
			}
			trigger_error('Event Next Sync Token ' . $results->getNextSyncToken( ));
			trigger_error('Event Next Page ' . $nextpage);
			$events = $results->getItems();
			trigger_error('Events ' . json_encode($events,JSON_UNESCAPED_SLASHES ));
		}while(!empty($nextpage));
		
	}catch(Exception $e)
	{
		trigger_error($e->getMessage());
		$result['status']='failure';
		$result['error']=$e->getMessage();		
	}
	return $result;
}

function fetchEventsFromSyncToken($calendarId, $synctoken,$useremail,$dbcalendarid)
{
	$response=array();
	try
	{
		$client = new Google\Client();
		$client->useApplicationDefaultCredentials();
		$client->setApplicationName("Client_Library_Examples");
		//$client->setSubject('appadurai@yaalidatrixproj.com');
		$client->setSubject($useremail);
		$client->setScopes(['https://www.googleapis.com/auth/calendar','https://www.googleapis.com/auth/calendar.events']);	
		//$client->fetchAccessTokenWithAssertion();
		$service = new Google_Service_Calendar($client);
		trigger_error('Event class ' . $service->events);
		$optParams = array(
	  'syncToken' => $synctoken
	);
		$results = $service->events->listEvents($calendarId, $optParams); //Google_Service_Calendar_Resource_Events -> Google_Service_Calendar_Events
			$nextpage=$results->getNextPageToken( );
			if(empty($nextpage))
			{
				$nexteventsynctoken=$results->getNextSyncToken();
				$result['status']='success';
				$result['nexteventsynctoken']=$nexteventsynctoken;
			}
			trigger_error('Event Next Sync Token ' . $results->getNextSyncToken( ));
			trigger_error('Event Next Page ' . $nextpage);
			$events = $results->getItems();
			trigger_error('Events List ' . json_encode($events));
			foreach($events as $theevent)
			{
				$eventcreated=$theevent['created'];
				$desc=$theevent['description'];
				$geventid=$theevent['id'];
				
				$status=$theevent['status'];
				$summary=$theevent['summary'];
				$updated=$theevent['updated'];
				$organizerjson=$theevent['organizer'];
				$organizer=$organizerjson['email'];
				$creatorjson=$theevent['creator'];
				$creator=$creatorjson['email'];
				$eventstart=$theevent['start']['dateTime'];
				$eventend=$theevent['end']['dateTime'];
				$attendees=$theevent['attendees'];

				foreach($attendees as $attendee)
				{
					$attemail=$attendee['email'];
					$attresponse=$attendee['responseStatus'];
					
				}
				$eventresult=fetchEventFromGEventId($geventid);
				if($eventresult['status'] == 'success_withnodata')
				{
					// Add Event data into db
					$theevent['calendarid']=$dbcalendarid;
					addEvent($theevent);
					$ruleresult=runRulesforEvent($theevent);
					trigger_error('Matching Rules Array ' . json_encode($ruleresult));
				}else if($eventresult['status'] == 'success')
				{
					//check for modified record and update
					checkModifiedEvent($theevent);
				}else{
					trigger_error('Something wrong happend while fetching event details for $geventid ' . json_encode($eventresult));
				}

			}
		
	}catch(Exception $e)
	{

		trigger_error('Unable to fetch events for calendar ' . $calendarid . ' at synctoken ' . $synctoken . ' due to error ' . $e->getMessage());
		$response['status']='failure';
		$response['reason']=$e->getMessage();
	}
}



/**
function generateToken()
{
	$header = json_encode([ 'alg' => 'RS256','typ' => 'JWT']);
	$base64UrlHeader=str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
	trigger_error('B64 Header ' . $base64UrlHeader);
	$payload=json_encode(["iss" => "datrixcalendar@glass-stratum-307800.iam.gserviceaccount.com",
						   "sub"=>"appadurai@yaalidatrixproject.com",
							"scope"=>"https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/calendar.events",
							"aud"=>"https://oauth2.googleapis.com/token",
							"exp"=>1615900500,
							"iat"=>1615897578
		],JSON_UNESCAPED_SLASHES );
	$base64UrlPayload=	base64_encode($payload);
trigger_error('!!!!!!!!!!!B64 Payload ' . $base64UrlPayload);
	$base64UrlPayload=str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
	trigger_error('B64 Payload ' . $base64UrlPayload);

	$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCqrrwf+jjqSTTi\nKoFbmd4Xx42tlafcVfsnKTKlUA5RlYIK2HgR2TgiG9J7oLBGhE7RyNV5UptBp3zG\nogEETzc6mARdiAUOpvBNaAvmJctjBMA0WPyEYHoM1TMADQDimc6gKgVLlF44mJsE\n1d0bi1FHWMz4+M10qx+9R7d2vJJB0LJ2D6K8yV4K6n93CfzqIqLLD+/5UDL9lCGk\nP2ElvjMgCnFjU2lizM5/HTek6sW0o5xQefMXsPnnsPGopEJ+aTyObQf/PlJh66FT\nsI2FZFHWr8b6mK+/VH5O6i68+8perbrx4ITDb40umgxgsxDj5eluuwVH8WcSw9nM\npkA+JqwzAgMBAAECggEAE5UOkmCj7QVPIu+bfcXufC3x7EtyQc4/jWOlot/HdMbB\nb7scM3gHf6pPgM2Ty9NcM6dmDpBSrbqojyWBFlx8SqWsQorbga3XfhoE23fUmNyv\nrJHVDt7+wcAoJMZZJqZc1XVcvCoekUG471oWDxXx0ky23420DMGhV5cFzoKeCRzX\nlSKVK2w2iVDXJiKhZfLxtg1cnhbJKJ7VGXQAU0uG2jc3kOC+Obb45G6ZapYIZi+h\nCFyVKNhIUDUBjZIopQ/Q31Y/2GnlfY/SjXyhovn5A166qpFAhwikhEFKmFrMZKnN\nJcxMSUw+48j5CKp9c6HzuKzg8i1a50uDjQuhSIqoIQKBgQDTEQZA63VQzsOgBZV3\nDQ3guwHeGveMihWYRDqxH5XD1nY63fOAmH9UqEZD/ikZevGPJyoXweNFazc1/hGC\nM8TebNGvaxXhhaWM08ZMinNSebIy0PJWHVYnxHatwSVQjRV0jY9tGRnXTwv1GG1c\nuqT95g9B6lW/yTJfaLgaYSHg8QKBgQDPBNBgfVruO+9Ky2GKX0P1eVN4EAHbEvia\n3ZSctZLTqr84zIXEI9b4r1QLwBs3kpdrTUdcfsf6z5LVfQcvT8R58Uo0ovF1RV3O\nlym2oFJFN1fe0V5WWsLfhAXX4koBlTBNNwTbsCw8FfeKedO1yo4PBgvBOAKBx4c8\nM4sPSMefYwKBgQCSx9wZeaV0NNZGipuO1z03c9WwqABPdBfgdFJ8qwJz1skR1xhn\n7aVfMkbrMe1TEvS6IPpc2zE6LX2PxshWS7o1FGgeHdJxq4edkAjvYbkhOhB61mQx\nDuXDchU2cIOKqRKqo2eC71nRKd+e6wXi3smu6DbkGBrZ7Qli4Ghnr/TngQKBgHSI\nFjAqgDQYotOsdqSoiZ21hQnRoOnL3Qt37X7lKthhhjCX2DiXoBvuvdW7dtaqevfY\n0XG8oB+MFvB75753HXczHv1QN7E2sd5n8wAtcQIrbBguLPdZMdcV/yAN7nDDpSPh\n86cinVPZXbWibE0pzxuf4KAMQkUBpxt5PC8HXe9XAoGBALl7zw0QV9sXcXK4Nzo8\nkukknqHajFD2325fj969+xEnww++GAPMS1CaQozoHnspSX3D+36Qj22EbunNjHFL\nfzWWHxO59J9JKuU7DCZhr8usfk0VTC1tgApypeXJkeot7RJl7ERxPFfUEjxQd6f8\ndR9sPWWlEGHo9GPdrTEF1+OQ\n-----END PRIVATE KEY-----\n', true);
	trigger_error('Byte Array Signature ' . $signature);
	$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

	trigger_error('B64 Url Signature ' . $base64UrlSignature );

	$jwttoken = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

	trigger_error('JWT Token ' . $jwttoken);

}
*/