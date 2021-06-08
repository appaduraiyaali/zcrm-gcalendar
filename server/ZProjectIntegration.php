<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'config.inc.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'DBUtility.php');


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
	"id": "7j95ls5oo4cb4cj1hbsiuk2ut4",
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
		"email": "appadurai@gmail.com",
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

$samplerule=array('ruleid'=>1,'rulename'=>'Sample Rule','zprojectid'=>'1600500000001081005','criteria'=>'{"condition":"OR","rules":[{"id":"description","field":"description","type":"string","input":"text","operator":"contains","value":"Demo"},{"id":"email","field":"email","type":"string","input":"text","operator":"equal","value":"jhon@test.com"},{"condition":"OR","rules":[{"condition":"OR","rules":[{"id":"summary","field":"summary","type":"string","input":"text","operator":"contains","value":"Meeting"},{"id":"email","field":"email","type":"string","input":"text","operator":"contains","value":"zylker"}]},{"condition":"OR","rules":[{"id":"description","field":"description","type":"string","input":"text","operator":"not_contains","value":"google"},{"id":"summary","field":"summary","type":"string","input":"text","operator":"equal","value":"Demo Meeting"}]}]}],"valid":true}','priority'=>'1','emails'=>'appadurai@bizappln.com');
//fetchZohoProjects();

if($_GET['method']=="fetchprojects"){
	//echo "Projects New Test Data..";
	$projectsdata = fetchZohoProjects();
	print_r($projectsdata);
}

//createZProjectTask($datastr,'appadurai@bizappln.com',$samplerule);

//checkAndcreateZProjectTasks(json_decode($datastr,true),$samplerule);
function getAccessToken($reFresh_token)
{

	$client_id=CLIENT_ID;
	$client_secret= CLIENT_SECRET;
	$access_url = "https://accounts.zoho.com/oauth/v2/token?refresh_token=".$reFresh_token."&client_id=".$client_id."&client_secret=".$client_secret."&grant_type=refresh_token";

	$access_curl = curl_init();

	curl_setopt_array($access_curl, array(
	  CURLOPT_URL => $access_url,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 45,
	  CURLOPT_SSL_VERIFYPEER => FALSE,
	  CURLOPT_SSL_VERIFYHOST => FALSE,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",

	));

	$access_response = curl_exec($access_curl);
	trigger_error('Access Token Response ' . $access_response);
	$access_err = curl_error($access_curl);
	if($access_err != null)
	{
		trigger_error('Access token error ' . $access_err);
	}
	curl_close($access_curl);
	$res = json_decode($access_response);
	$final_access_token = $res->access_token;
	trigger_error('Latest Access Token ' . $final_access_token);
	return $final_access_token;
}

function fetchZohoProjects()
{
	$result=array();
	try{
			$reFresh_token= REFRESH_TOKEN;
	
			$accesstoken=getAccessToken($reFresh_token);
			$request_url = 'https://projectsapi.zoho.com/restapi/portal/'.PROJECTPORTAL.'/projects/';
			$method_name = 'GET';
			$downch = curl_init();
			$headers = array(
				'Authorization:Zoho-oauthtoken ' .$accesstoken
			);
			
		
			 $request_parameters = array(
			 'index' => 1,
			 'range' => 1,
			 'status' => 'active'
			 );
			// $request_url .= '?' . http_build_query($request_parameters);
			 echo $request_url;
			 
		 curl_setopt($downch, CURLOPT_URL, $request_url);
    curl_setopt($downch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($downch, CURLOPT_CUSTOMREQUEST, "GET");
	
    curl_setopt($downch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($downch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($downch, CURLOPT_TIMEOUT, 60);
	curl_setopt($downch, CURLOPT_VERBOSE, 1);
		 
		 $response = curl_exec($downch);
		 trigger_error('Response ' . $response);
		 $projectsjson=json_decode($response,true);

		 $projectdetails=$projectsjson['projects'];
		 foreach($projectdetails as $theproject)
			{
				 trigger_error("Project Name and Id " . $theproject['name'] . $theproject['id_string']);
				 $projectname=$theproject['name'] ;
				 $projectid=$theproject['id_string'];
				 $result[$projectid]=$projectname;
			}
		 $response_info = curl_getinfo($downch);
		 curl_close($downch);
		 $response_body = substr($response, $response_info['header_size']);
		 echo "Response HTTP Status Code : ";
		 echo $response_info['http_code'];
		  echo "\n";
		 echo "Response Body : ";
		 echo $response;
	}catch(Exception $e)
	{
		trigger_error('Unable to fetch Zoho Projects ' . $e->getMessage());
	}
	return json_encode($result);
}

function createorUpdateZProjectTasks($event,$matchedrule)
{
	$result=array("status"=>"success");	
	$gsuiteattendeevsstatus=array();
	$dbattendeevsevent=array();
	try{
			$acceptedattendees=array();
			$attendees=$event['attendees'];
			foreach($attendees as $theattendee)
			{
				$attemail=$theattendee['email'];
				$eventresponse=$theattendee['responseStatus'];
				if($eventresponse == 'accepted')
				{
					array_push($acceptedattendees,$attemail);
				}
				$gsuiteattendeevsstatus[$attemail]=$eventresponse;
			}

			$dbuserwoztask=array();
			$dbname=DBNAME;
			$conn=getMysqlConnection();
			$geventid=$event['id'];
			$eventsql="SELECT a.attendee attendeeemail, responsestatus,a.eventid dbeventid,ztaskid FROM attendees a join gevent g on a.eventid=g.eventid where geventid = '$geventid'";
			mysqli_select_db($conn, $dbname);
			$queryresult = mysqli_query($conn, $eventsql);
			trigger_error('error:'.mysqli_error($conn));
			$totalrows=mysqli_num_rows($queryresult);
			trigger_error('Total Rows for event ' . $totalrows);
			$createtask=false;
			if($totalrows > 0)
			{	
				$dbeventid=null;
				while ($fetchrow = mysqli_fetch_array($queryresult)) 
				{	
					$ztaskid=$fetchrow['ztaskid'];
					if($ztaskid == null)
					{
						$createtask=true;
					}
					$dbemail=$fetchrow['attendeeemail'];
					$dbeventid=$fetchrow['dbeventid'];
					$dbresponsestatus=$fetchrow['responsestatus'];
					$dbattendeevsevent[$dbemail]=$dbresponsestatus;
				}						
				$result['data']=$dbattendeevsevent;
			}else{
				$result['data']=array();
			}

			// Check if Ztaskid is created for the Gsuite Event and create in Zoho Project
			$acceptedcount=sizeof($acceptedattendees);
			if($createtask)
			{				
					$accepteduser=$acceptedattendees[0];
					$ztaskid=createZProjectTask($event,$theattendee,$matchedrule);
					trigger_error('Zoho Task created with id ' . $ztaskid);
					updateZTaskIdinDBEvent($ztaskid,$accepteduser,$dbeventid);				
			}
			
			if(!$createtask || $acceptedcount > 1) // Eiter Event is modified
			{
				foreach($acceptedattendees as $theattendee) // TODO: Delete this loop and apply the inner code to the below iteration of all gsuite attendees 
				{
					if(array_key_exists($theattendee,$dbattendeevsevent))  // gsuite accepted attendee is present in db
					{
						$dbstatus=$dbattendeevsevent[$theattendee];
						if($dbstatus != 'accepted')
						{
							trigger_error('New Attendee accepted ' . $theattendee . ' event ' , $dbeventid);
							// update the zoho task to add the owner record (if internal user)
							// update the dbstatus of attendee to accepted.
						}else
						{
							trigger_error('Attendee already accepted ' . $theattendee . ' event ' . $dbeventid);
							// No action needed continue
							continue;;
						}
					}else{ // Gsuite attendee not present in db
						// Add attendee to db
						// Update zoho task to update the owner for internal users
						trigger_error('Gsuite Accepted Event Attendee not present in db ' . $theattendee);

					}
					trigger_error('TaskId from Zoho ' . $ztaskid);					
				}

				/*** Iterate the db attendees to check if any of them is removed in Gsuite Event ***/
				foreach($dbattendeevsevent as $dbattendee => $dbstatus)
				{
					
					if(!array_key_exists($dbattendee,$gsuiteattendeevsstatus)) //present in db but not in gsuite event
					{
						trigger_error('Present in DB but not in Gsuite ' . $dbattendee . ' eventid ' . $dbeventid);
						if($dbstatus == 'accepted')
						{
							//  remove the ownerid from the zoho task
							//  remove from dbattendee table
						}else{
							// remove from db
						}
					}else if($dbstatus == 'accepted' && $gsuiteattendeevsstatus[$dbattendee] != 'accepted' )
						// Accepted in DB but changed in Gsuite Event
					{
						trigger_error('DB Attendee Accepted but Gsuite status is different ' . $dbattendee . $$gsuiteattendeevsstatus[$dbattendee]);
						// remove the ownerid from the zoho task
						// update the db with the new status
					}
				}
				/*** Iterate all the gsuite attendees to check missing db entries ***********/
				foreach($gsuiteattendeevsstatus as $gsuiteattendee => $gsuitestatus)
				{
					if($gsuitestatus != 'accepted')
					{
						if(!array_key_exists($gsuiteattendee,$dbattendeevsevent)) //present in db but not in gsuite event
						{
							trigger_error('Present in GsuiteEvent but not in DB ' . $gsuiteattendee . ' eventid ' . $dbeventid);
							// add the gsuite attendee to the eventattendee table
						}
					}
				}

			}
	}catch(Exception $e)
	{
		trigger_error('Unable to create Task for attendees in event ' . json_encode($event));
	}	
}

function getUserIdFromProject($useremail,$projectid)
{
	$zuserid=null;
	try
	{

$reFresh_token= '1000.3af45485d8fc6f33f6b0001d84440e4a.c595dc62ef3838386c3a580ee04e569e';
	
			$accesstoken=getAccessToken($reFresh_token);
			$request_url = 'https://projectsapi.zoho.com/restapi/portal/'.PROJECTPORTAL.'/projects/'.$projectid.'/users/';
			$method_name = 'GET';
			$downch = curl_init();
			$headers = array(
				'Authorization:Zoho-oauthtoken ' .$accesstoken
			);
			
		 curl_setopt($downch, CURLOPT_URL, $request_url);
    curl_setopt($downch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($downch, CURLOPT_CUSTOMREQUEST, "GET");
	
    curl_setopt($downch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($downch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($downch, CURLOPT_TIMEOUT, 60);
	curl_setopt($downch, CURLOPT_VERBOSE, 1);
		 
		 $response = curl_exec($downch);
		 trigger_error('Response ' . $response);
		 $usersjson=json_decode($response,true);
		 $response_info = curl_getinfo($downch);
		 curl_close($downch);
		 
		 $userdetails=$usersjson['users'];
		 foreach($userdetails as $theuser)
			{
				 trigger_error("User Name and email " . $theuser['name'] . $theuser['email']);
				 $zemail=$theuser['email'] ;
				 $zuerid=$theuser['id'];
				 if($zemail == $useremail)
				{
					 return $zuserid;
				}
			}
		 
	}catch(Exception $e)
	{
		trigger_error(' Unable to fetch user ' .$useremail .' from project ' .$projectid . ' dur to ' .$e->getMessage()) ;
	}
	return $zuserid;
}

function createZProjectTask($event,$email,$matchedrule)
{
	$ztaskid=null;
	$refresh_token2='1000.66c1ba6b5a9c978f519981a37f0d83fe.eca69580e7a5fe47e7651a8a6771112d';
	
	$projectid=$matchedrule['zprojectid'];
	$endpoint='https://projectsapi.zoho.com/restapi/portal/'.PROJECTPORTAL.'/projects/'.$projectid.'/tasks/';
	$taskname=$event['summary'];
	$taskdescription=$event['description'];
	$owner=getUserIdFromProject($email,$projectid);;
	$startdate=$event['start']['dateTime'];
	$enddate=$event['end']['dateTime'];
	trigger_error('Owner ' . $owner. 'Stdate ' . $startdate . ' ' . $enddate);
	$tasklistid=ZTASKLISTID;
	try
	{
		$post_data=array();
		$post_data['name']=$taskname;
		$post_data['description']=$taskdescription;
		$post_data['tasklist_id']=$tasklistid;
		if($owner != null)
		{
			$post_data['person_responsible']=$owner;
		}
		
		$startdateDT=DateTime::createFromFormat('Y-m-d\TH:i:sP',$startdate);//'2021-05-06T09:00:00+05:30');
		$post_data['start_date']=$startdateDT->format('m-d-Y');
		$post_data['start_time']=$startdateDT->format('H:i');
		
		$enddateDT = DateTime::createFromFormat('Y-m-d\TH:i:sP',$enddate);		
		$post_data['end_date']=$enddateDT->format('m-d-Y');		
		$post_data['end_time']=$enddateDT->format('H:i');
		
		$interval=$startdateDT->diff($enddateDT);	
		$diffhr=$interval->format("%H");
		$post_data['duration_type']='hrs';
		$post_data['duration']=$diffhr;

		$fieldsString = http_build_query($post_data);
		trigger_error('Zoho Task to Post ' . $fieldsString);
		$accesstoken=getAccessToken($refresh_token2);
		$downch = curl_init();
		$headers = array(
				'Authorization:Zoho-oauthtoken ' .$accesstoken
		);

		curl_setopt($downch, CURLOPT_URL, $endpoint);
		curl_setopt($downch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($downch, CURLOPT_CUSTOMREQUEST, "POST");
		
		curl_setopt($downch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($downch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($downch, CURLOPT_TIMEOUT, 60);
		curl_setopt($downch, CURLOPT_VERBOSE, 1);
		curl_setopt($downch, CURLOPT_POSTFIELDS, $fieldsString);
		$response=curl_exec($downch);
		trigger_error('Zoho Project response ' . $response);
		$taskresponse=json_decode($response,true);
		if(!array_key_exists('error',$taskresponse))
		{
			$thetask=$taskresponse['tasks'][0];
			$ztaskid=$thetask['id'];
			trigger_error('TaskId from Zoho ' . $ztaskid);
		}else{
			trigger_error('Unable to create task for attendee ' . $email);
		}

	}catch(Exception $e)
	{
		trigger_error('Unable to create zoho project task for attendee ' . $email );
	}
	return $ztaskid;
	
}

function updateZTaskIdinDBEvent($ztaskid,$theattendee,$dbeventid)
{

	try{
		$dbname=DBNAME;
		$conn=getMysqlConnection();
		mysqli_select_db($conn, $dbname);
		$updaeventsql="update gevent set ztaskid='$ztaskid' where eventid=$dbeventid";
		$queryresult =mysqli_query($conn, $updaeventsql);
		if(!$queryresult)
		{
			trigger_error(' Unable to update projecttaskid for event '.$dbeventid.' and attendee '.$theattendee .mysqli_error($conn));
		}else{
			
			$updatesql="update attendees set responsestatus='accepted' where attendee='$theattendee' and eventid=$dbeventid";
			$queryresult = mysqli_query($conn, $updatesql);
			if(!$queryresult)
			{
				trigger_error(' Unable to update  for  '.$dbeventid.' and attendee '.$theattendee .mysqli_error($conn));
			}
		}
	}catch(Exception $e)
	{
		trigger_error('Unable to update dbevent with ztaskid ' . $taskid);
	}					
}
