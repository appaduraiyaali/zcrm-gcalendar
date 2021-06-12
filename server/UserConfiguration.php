<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'DBUtility.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'GCalendarOperation.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'CommonUtility.php');

//addUser('appadurai@yaalidatrixproj.com');
//fetchallusers();
//removeUser('dummyuser@test.com');


function addUser($gsuiteemail)
{
	$result=array('status'=>'');
	$guserid='';
	if(checkUserExist($gsuiteemail))
	{
		$result['status']='failure';
		$result['reason']='User ' .$gsuiteemail .' already exist';
		trigger_error('New User Creation Failed. User already exists ' . $gsuiteemail);
		return json_encode($result);
	}

	$nextEventSyncToken=null;
	$gcheckresult=checkUserExistGsuite($gsuiteemail);
	if($gcheckresult['status'] == 'failure')
	{
		$result['status']='failure';
		$result['reason']=$gcheckresult['error'];
		return $result;
	}
	$gcheckuserexist=$gcheckresult['userexist'];
	if($gcheckuserexist == 'true')
	{
		trigger_error('User is availabel in Gsuite account with id ' . $gcheckresult['guserid']);
	}else{
		$result=array('status'=>'failed','reason'=>'User '  .$gsuiteemail.' found in Gsuite Account');
		trigger_error('User  '.$gsuiteemail.' is not available in Gsuite account');
		return json_encode($result);
	}

		$conn=getMysqlConnection();
		try{
				$dbname=DBNAME;
				$nexteventsynctoken='';
				$userdataarr=array();
				if($conn)
				{
					mysqli_select_db($conn, $dbname);
					$guserid=$gcheckresult['guserid'];
					$syncresult=doFullCalendarEventsSynch($gsuiteemail);
					if($syncresult['status'] == 'success')
					{
						$nexteventsynctoken=$syncresult['nexteventsynctoken'];
					}
					$usersql = "INSERT INTO calendaruser (guserid, email,status)
					VALUES ('$guserid', '$gsuiteemail', 'active')";
					$queryresult =mysqli_query($conn, $usersql);

				if ($queryresult === TRUE) {
				  $last_id = $conn->insert_id;
				  $result['status']='success';
				  $result['message']='User successfully created with id ' .$last_id;
				  $currentimemilli=(int)(microtime(true)*1000);
				  $tokenexpiry=$currentimemilli+ (6*86400000) ; // after 6 days from now
				  $channelid=random_strings();
				  $watchresponse=setWatcher($gsuiteemail,$channelid);
				  $resourceId=$watchresposne['resourceId'];
				  trigger_error( "New User record created successfully " .$last_id);
				  $calendarsql= "INSERT INTO calendarconfig (gcalid, nextsynctoken,userid,tokenexpiry,channelid,watcherid)
							VALUES ('primary', '$nexteventsynctoken', $last_id,$tokenexpiry,'$channelid','$resourceId')";
				  $queryresult = mysqli_query($conn, $calendarsql);
				  if ($queryresult === TRUE) {
					  $last_id = $conn->insert_id;
					  trigger_error( "New Calendarconfig record created successfully " .$last_id);
					  
						} else {
					  trigger_error( "Error: " . $calendarsql . "<br>" . mysqli_error($conn));
					  stopWatcher($gsuiteemail,$channelid,$resourceId);
					}		

				} else {
				  trigger_error( "Error: " . $usersql . "<br>" . mysqli_error($conn));
				}			

			}
			closeConnection($conn);
		}catch(Exception $e)
		{
			$result["status"]='failure';
			$result['reason']=$e->getMessage();
		}
		trigger_error('Add User Result ' . json_encode($result));
		return json_encode($result);	 
}

function checkUserExist($gsuiteEmail)
{
	$conn=getMysqlConnection();
	$dbname=DBNAME;
	if($conn)
	{
		$allprofilequery="select userid,email from calendaruser where email='".$gsuiteEmail."'";
		mysqli_select_db($conn, $dbname);
		$queryresult = mysqli_query($conn, $allprofilequery);
		 trigger_error('error:'.mysqli_error($conn));
		$totalrows=mysqli_num_rows($queryresult);
		trigger_error('Total Rows ' . $totalrows);
		if($totalrows > 0)
		{
			return true;
		}else{
			return false;
		}
	}
	closeConnection($conn);
}

function fetchAllUsers()
{
	$result=array("status"=>"success");	
	try{
	$userdataarr=array();
	$dbname=DBNAME;
	$conn=getMysqlConnection();
	if($conn)
	{
		$allprofilequery="select userid,email from calendaruser where status='active'";
		mysqli_select_db($conn, $dbname);
		$queryresult = mysqli_query($conn, $allprofilequery);
		trigger_error('error:'.mysqli_error($conn));
		$totalrows=mysqli_num_rows($queryresult);
		trigger_error('Total Rows ' . $totalrows);
		if($totalrows > 0)
		{
			
			while ($fetchrow = mysqli_fetch_array($queryresult)) 
			{	
				$userdata=array();			
				$email=$fetchrow['email'];
				$userdata['email']=$email;
				array_push($userdataarr,$userdata);
			}						
			$result['data']=$userdataarr;
		}else{
			$result['data']=array();
		}
	}
	closeConnection($conn);
	}catch(Exception $e)
	{
		$result["status"]='failure';
		$result['reason']=$e->getMessage();
	}
	trigger_error('Fetch All Users result ' . json_encode($result));
	return json_encode($result);
}

function removeUser($useremail)
{

	$result=array("status"=>"success");
	try{
			$dbname=DBNAME;
		$userdataarr=array();
		$conn=getMysqlConnection();
		if($conn)
		{
			$allprofilequery="select cc.userid,email,channelid,watcherid from calendaruser cu join calendarconfig cc on cu.userid=cc.userid  where email='".$useremail."'";
			mysqli_select_db($conn, $dbname);
			$queryresult = mysqli_query($conn, $allprofilequery);
			trigger_error('error:'.mysqli_error($conn));
			$totalrows=mysqli_num_rows($queryresult);
			trigger_error('Total Rows ' . $totalrows);
			if($totalrows > 0)
			{
				/*
				// calednarconfig
				$calendarconfigsql="select calendarid from calendarconfig where userid=$dbuserid";
				$calresult = mysqli_query($conn, $calendarconfigsql);
				$calrows==mysqli_num_rows($calresult);
				if($calrows >0)
				{
					//delete events
					$fetchrow = mysqli_fetch_array($calresult);
					$calendarid=$fetchrow['calendarid'];
					$eventsql="select eventid from gevent where calendarid=$calendarid";
					$eventresult = mysqli_query($conn, $eventsql);
					$eventrows=mysqli_num_rows($eventresult);
					if($eventrows > 0)
					{

					}

				}*/
				$fetchrow = mysqli_fetch_array($queryresult);
				trigger_error('User Result ' . json_encode($fetchrow));
				$dbuserid=$fetchrow['cc.userid'];
				$channelid=$fetchrow['channelid'];
				$resourceid=$fetchrow['watcherid'];
				
				$delsql="delete from calendaruser where email='".$useremail."'"; 
				//FK relationship established to Remove all associated Events and other dependencies
				$delresult = mysqli_query($conn, $delsql);
				if(!$delresult)
				{
				  trigger_error( "Error: " . $delsql . "<br>" . mysqli_error($conn));
				}else{
					$wresult=stopWatcher($useremail,$channelid,$resourceid);
					if ($wresult->getStatusCode() == 200)
					  {
						 $stopresponse= $res->getBody()->getContents();
						 trigger_error('Stop Watcher result ' .$stopresponse);
					  }else if($wresult != null){
						trigger_error('Stop Watcher Error result ' .$wresult->getStatusCode());
					  }
								
				}

			}else{
				$result['status']='failure';
				$result['reason']='User does not exist';
			}
		}
		closeConnection($conn);
		}catch(Exception $e)
		{
			$result["status"]='failure';
			$result['reason']=$e->getMessage();
		}
		trigger_error('Delete User Result ' . json_encode($result));
		return json_encode($result);
}