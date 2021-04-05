<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'config.inc.php');
include_once __DIR__ . '/google-api-php-client--PHP8.0/vendor/autoload.php';

use ReallySimpleJWT\Token;
//putenv('GOOGLE_APPLICATION_CREDENTIALS='.__DIR__.'/gsuitecredentials.json');
putenv('GOOGLE_APPLICATION_CREDENTIALS='.__DIR__.'/keywordrepo-8ed71daeee65.json');
doFullCalendarEventsSynch();

function connectGoogleClient()
{
	$client = new Google\Client();
	$client->useApplicationDefaultCredentials();
	$client->setApplicationName("Client_Library_Examples");
	//$client->setSubject('appadurai@yaalidatrixproj.com');
	$client->setSubject('paolo@bytekmarketing.it');
	$client->setScopes(['https://www.googleapis.com/auth/calendar','https://www.googleapis.com/auth/calendar.events']);	
	//$client->fetchAccessTokenWithAssertion();
	$service = new Google_Service_Calendar($client);
/*
$channel =  new Google_Service_Calendar_Channel($client);
$channel->setId('20fdedbf0-a845-11e3-1515e2-0800200c9a6689111');
$channel->setType('web_hook');
$channel->setAddress('https://d40okpmrbb5wv.cloudfront.net/gsuitecalendar/NotificationReciever.php');
$watchEvent = $service->events->watch('primary', $channel);
trigger_error('Watch Response ' . json_encode($watchEvent));
*/
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


function doFullCalendarEventsSynch()
{
	$client = new Google\Client();
	$client->useApplicationDefaultCredentials();
	$client->setApplicationName("Client_Library_Examples");
	//$client->setSubject('appadurai@yaalidatrixproj.com');
	$client->setSubject('paolo@bytekmarketing.it');
	$client->setScopes(['https://www.googleapis.com/auth/calendar','https://www.googleapis.com/auth/calendar.events']);	
	//$client->fetchAccessTokenWithAssertion();
	$service = new Google_Service_Calendar($client);
	$calendarId = 'primary';
	$optParams = array(
	  'maxResults' => 2000,
	  'singleEvents' => true  
	);
	$nextpage=null;
	do{
		if($nextpage != null)
		{
			$optParams['pageToken']=$nextpage;
		}
		$results = $service->events->listEvents($calendarId, $optParams);
		$nextpage=$results->getNextPageToken( );
		trigger_error('Event Next Sync Token ' . $results->getNextSyncToken( ));
		trigger_error('Event Next Page ' . $nextpage);
		$events = $results->getItems();
		trigger_error('Events ' . json_encode($events,JSON_UNESCAPED_SLASHES ));
	}while(!empty($nextpage));
	
}

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