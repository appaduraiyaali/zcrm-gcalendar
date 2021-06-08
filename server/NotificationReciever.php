<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'config.inc.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'DBUtility.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'GCalendarOperation.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'CommonUtility.php');

trigger_error('Notification Recieved from Gsuite ' );
$entityBody = file_get_contents('php://input');
trigger_error('Request Body ' . $entityBody);
$headers =  getallheaders();


foreach($headers as $key=>$val){
  trigger_error( 'HeaderInfo ' . $key . ': ' . $val );
}
handleNotification($headers);

function handleNotification($headers)
{
	$channelid=$headers['X-Goog-Channel-Id'];
	$state=$headers['X-Goog-Resource-State'];
	$token=$headers['X-Goog-Channel-Token'];
	$resourceid=$headers['X-Goog-Resource-Id'];
	try{
		if($state == 'sync') // this is received the first time when Watcher is created, so ignore this notification from gsuite
		{
			return;
		}
		$conn=getMysqlConnection();
		$dbname=DBNAME;
		mysqli_select_db($conn, $dbname);
		$calendarsql="SELECT STATUS,calendarid,nextsynctoken,gcalid,email FROM `calendarconfig` cconfig join calendaruser cuser on cconfig.userid=cuser.userid and gcalid='primary' and email='$token'";
		$queryresult = mysqli_query($conn, $calendarsql);
		trigger_error('mysql error:'.mysqli_error($conn));
		$totalrows=mysqli_num_rows($queryresult);
		$fetchrow = mysqli_fetch_array($queryresult); 
		$email=$fetchrow['email'];
		$nextsynctoken=$fetchrow['nextsynctoken'];
		$gcalid=$fetchrow['gcalid'];
		$dbcalendarid=$fetchrow['calendarid'];
		trigger_error('Fetch Events from synctoken ' . $nextsynctoken . ' for '. $email);
		$eventresult=fetchEventsFromSyncToken($gcalid,$nextsynctoken,$email,$dbcalendarid);
		updateNextSyncToken($dbcalendarid,$eventresult['nexteventsynctoken']);

	}catch(Exception $e)
	{
		trigger_error('Notification handling failed for resource ' . $channelid);
	}
}

function updateNextSyncToken($dbcalendarid,$nexttoken)
{
	try{
		$conn=getMysqlConnection();
		$dbname=DBNAME;
		mysqli_select_db($conn, $dbname);
		$updatetokensql="update calendarconfig set nextsynctoken='$nexttoken' where calendarid=$dbcalendarid";
		$queryresult = mysqli_query($conn, $updatetokensql);
		if(!$queryresult)
		trigger_error('mysql query result and error:' . $queryresult .mysqli_error($conn));
		

	}catch(Exception $e)
	{
		trigger_error('Notification handling failed for resource ' . $channelid);
	}
}
