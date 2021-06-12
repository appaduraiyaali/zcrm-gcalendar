<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'DBUtility.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'RuleProcessor.php');
//strtotimecheck();

function fetchEventFromGEventId($gid)
{
	$result=array();
	try{
		$dbname=DBNAME;
		$conn=getMysqlConnection();

		if($conn)
		{
			$eventdataarr=array();
			$eventsql="select eventid,geventid,description,title,updated from gevent where geventid='$gid'";
			mysqli_select_db($conn, $dbname);
			$queryresult = mysqli_query($conn, $eventsql);
			if(!empty(mysqli_error($conn)))
			{
				trigger_error('error:'.mysqli_error($conn));
			}
			$totalrows=mysqli_num_rows($queryresult);
			trigger_error('Events Fetched : Total Rows ' . $totalrows);
			if($totalrows > 0)
			{				
				while ($fetchrow = mysqli_fetch_array($queryresult)) 
				{	
					$eventdata=array();			
					$id=$fetchrow['eventid'];
					$eventdata['id']=$id;
					$eventdata['description']=$fetchrow['description'];
					$eventdata['title']=$fetchrow['title'];
					$eventdata['updated']=$fetchrow['updated'];
					array_push($eventdataarr,$eventdata);
				}
				$result['status']='success';
				$result['data']=$eventdataarr;
			}else{
				$result['status']='success_withnodata';
				$result['data']=array();
			}
		}
		closeConnection($conn);
	}catch(Exception $e)
	{
		$e->getMessage();
	}
	trigger_error('Event Result for id $gid '.json_encode($result));
	return $result;
}

function addEvent($theevent)
{
	try{
			trigger_error('DBEvent Addition invoked for ' . $theevent['id']);
			$dbname=DBNAME;
			$conn=getMysqlConnection();
			mysqli_select_db($conn, $dbname);
			$eventcreated=$theevent['created'];		
			$createdts=strtotime($eventcreated);
			$desc=$theevent['description'];
			$geventid=$theevent['id'];
			$calendarid=$theevent['calendarid'];	
			$status=$theevent['status'];
			$summary=$theevent['summary'];
			$updated=$theevent['updated'];
			$updatedts=strtotime($updated);
			$organizerjson=$theevent['organizer'];
			$organizer=$organizerjson['email'];
			$creatorjson=$theevent['creator'];
			$creator=$creatorjson['email'];
			$eventstart=$theevent['start']['dateTime'];
			$eventstartts=strtotime($eventstart);
			$eventend=$theevent['end']['dateTime'];
			$eventendts=strtotime($eventend);
			$eventinsert="INSERT INTO gevent (calendarid,geventid,starttime,endtime,created,updated,status,description,title)
				VALUES ('$calendarid', '$geventid', $eventstartts,$eventendts,$createdts,$updatedts,'$status','$desc','$summary')";
				$queryresult =mysqli_query($conn, $eventinsert);

				if ($queryresult === TRUE) {
					  $last_id = $conn->insert_id;
					  trigger_error( "New Event record created successfully " .$last_id);
					$attendees=$theevent['attendees'];
					foreach($attendees as $attendee)
					{
						$attemail=$attendee['email'];
						$attresponse=$attendee['responseStatus'];
						$insertattendee="insert into attendees(eventid,attendee,responsestatus) VALUES ($last_id,'$attemail','$attresponse')";
						$queryresult =mysqli_query($conn, $insertattendee);
						if(!$queryresult)
						{
							trigger_error(' Insert attendee failed due to error:'.mysqli_error($conn));
						}
					}
				}else{
					trigger_error(' DBEvent Addition failed due to error:'.mysqli_error($conn));
				}
	}catch(Exception $e)
	{
		triggger_error('Unable to add event to the system ' . $e->getMessage());
	}
}

function checkModifiedEvent($gsuiteevent)
{
	$runrules=false;
		
	trigger_error('CheckModifiedEvent initiated for event ' . $gsuiteevent['id']);
	try
	{
		$dbname=DBNAME;
		$conn=getMysqlConnection();
	
		$modificationtext='';
		$geventid=$gsuiteevent['id'];
		$geventsummary=$gsuiteevent['summary'];
		$geventdesc=$gsuiteevent['description'];
		
		$dbevent=fetchEventFromGEventId($geventid);
		$dbeventdata=$dbevent['data'][0];
		$dbsummary=$dbeventdata['title'];
		$dbdesc=$dbeventdata['description'];
		$dbeventid=$dbeventdata['id'];

		if($dbsummary != $geventsummary)
		{
			$modificationtext='SUMMARY_CHANGED|';
			trigger_error('Summary changed for event '.$geventid.' and rules will be triggered');
			$runrules=true;
		}

		if($dbdesc != $geventdesc)
		{
			$modificationtext=$modificationtext.'DESCRIPTION_CHANGED|';
			trigger_error('Desciption changed for '.$geventid.' and rules will be triggered ' );
			$runrules=true;
		}

		$attendees=$gsuiteevent['attendees'];
		$gattendeevsstatus=array();
		foreach($attendees as $attendee)
		{
			$attemail=$attendee['email'];
			$attresponse=$attendee['responseStatus'];
			$gattendeevsstatus[$attemail]=$attresponse;
		}
		$attendeesql="SELECT attendee,responsestatus,eventid,id FROM `attendees` where eventid=$dbeventid";
		$dbattendeevsstatus=array();
		
		mysqli_select_db($conn, $dbname);
		$queryresult = mysqli_query($conn, $attendeesql);
		trigger_error('error:'.mysqli_error($conn));
		$totalrows=mysqli_num_rows($queryresult);
		trigger_error('Attendees Fetched : Total Rows ' . $totalrows);
		if($totalrows > 0)
		{			
			while ($fetchrow = mysqli_fetch_array($queryresult)) 
			{	
				$dbattendee=$fetchrow['attendee'];
				$dbstatus=$fetchrow['responsestatus'];				
				$dbattendeevsstatus[$dbattendee]=$dbstatus;				
			}				
		}
		// Iterate and compare the attendee in gevent and db. Based on the results update/insert the attendee dbtable
		foreach($gattendeevsstatus as $gemail=>$gstatus)
		{
			if(array_key_exists($gemail,$dbattendeevsstatus))
			{
				// check for matching status
				$dbstatus=$dbattendeevsstatus[$gemail];
				if($dbstatus != $gstatus)
				{
					trigger_error('Status changed for event '.$dbeventid.' for attendee '.$gemail);
					// update attendee table
					$updatestatussql="update attendees set responsestatus='$gstatus' where attendee='$gemail' and eventid=$dbeventid";
					$queryresult =mysqli_query($conn, $updatestatussql);
					if(!$queryresult)
					{
						trigger_error(' Unable to update response status for event '.$dbeventid.' and attendee '.$gemail .mysqli_error($conn));
					}
					
				}
			}else{
				// insert the $geventattendee and status in db
				trigger_error('New attendeed added for event '.$dbeventid.' for attendee'. $gemail);
				$insertnewattendee="insert into attendees(eventid,attendee,responsestatus) values($dbeventid,'$gemail','$gstatus')";
				$queryresult =mysqli_query($conn, $insertnewattendee);
				if(!$queryresult)
				{
					trigger_error(' Insert attendee failed for '.$gemail.'due to error:'.mysqli_error($conn));
				}
				$runrules=true;
			}
		}
	}catch(Exception $e)
	{
		trigger_error('Exception occured while processing Event Modified ' . $e->getMessage());
	}
	return $runrules;
}

function runRulesForEvent($eventdata)
{
	$result=array("status"=>"success");
	$matchingrules=array();
	
	$userdataarr=array();
	$dbname=DBNAME;
	$conn=getMysqlConnection();
	try
	{
		$ruleconfigsql='select ruleid,rulename, criteria,priority,emails,zprojectid from ruleconfig';
		mysqli_select_db($conn, $dbname);
		$queryresult = mysqli_query($conn, $ruleconfigsql);
		trigger_error('error:'.mysqli_error($conn));
		$totalrows=mysqli_num_rows($queryresult);
		trigger_error('Total Rows ' . $totalrows);
		if($totalrows > 0)
		{			
			while ($fetchrow = mysqli_fetch_array($queryresult)) 
			{	
				$rulestr=$fetchrow['criteria'];
				$ruleexpression=json_decode($rulestr,true);
				$ruleid=$fetchrow['ruleid'];
				$priority=$fetchrow['priority'];
				$ismatch=parseAndEvaluate($ruleexpression,$eventdata);
				if($ismatch)
				{
					array_push($matchingrules,$fetchrow);
					trigger_error('Rules Matched for ruleid ' . $ruleid);
				}else{
					trigger_error('Rules Unmatched ' . $ruleid);
				}
				
			}						
			$result['data']=$matchingrules;

		}else{
			$result['data']=array();
		}

	}catch(Exception $e)
	{
		trigger_error('Unable to identify matching Rules for Event ' . $eventdata['id'] . ' due to error ' . $e->getMessage());
	}
	return $result;
}

function strtotimecheck()
{
	$sampledt='2021-04-30T01:40:45.356Z'; //2021-05-01T10:30:00+05:30
//$sampledt='2021-05-01T10:30:00+05:30'; 
	//$format='YYYY-';
	echo strtotime($sampledt) . "\n";
	$dt= new DateTime();
	$dt->setTimestamp(strtotime($sampledt));
	echo " \n". date_default_timezone_get();
	$dt->setTimezone(new DateTimeZone('Asia/Calcutta'));
	echo $dt->format('c');
}