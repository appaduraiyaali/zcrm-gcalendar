<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'DBUtility.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'GCalendarOperation.php');

addUser('appadurai@yaalidatrixproj.com');
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
				  trigger_error( "New User record created successfully " .$last_id);
				  $calendarsql= "INSERT INTO calendarconfig (gcalid, nextsynctoken,userid)
							VALUES ('primary', '$nexteventsynctoken', $last_id)";
				  $queryresult = mysqli_query($conn, $calendarsql);
				  if ($queryresult === TRUE) {
					  $last_id = $conn->insert_id;
					  trigger_error( "New Calendarconfig record created successfully " .$last_id);
					  $result["status"]='success';
                        $result['message']="User Email - ".$gsuiteemail." has been added to watch events Successfully..";
						} else {
					  trigger_error( "Error: " . $calendarsql . "<br>" . mysqli_error($conn));
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
			$allprofilequery="select userid,email from calendaruser  where email='".$useremail."'";
			mysqli_select_db($conn, $dbname);
			$queryresult = mysqli_query($conn, $allprofilequery);
			trigger_error('error:'.mysqli_error($conn));
			$totalrows=mysqli_num_rows($queryresult);
			trigger_error('Total Rows ' . $totalrows);
			if($totalrows > 0)
			{			
				$delsql="delete from calendaruser set email='".$useremail."'";


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