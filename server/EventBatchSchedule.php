<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'DBUtility.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'RuleProcessor.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'GCalendarOperation.php');


function fetchGEventBatch()
{
	try
	{
		$dbname=DBNAME;
		$conn=getMysqlConnection();

		if($conn)
		{
			$eventdataarr=array();
			$eventsql="select id,gsuiteparentid,lastsynched,cc.gcalid as gcalid, cc.userid , cc.calendarid as dbcalendarid,cu.email as email from parent_recurring_event pr join calendarconfig cc join calendaruser cu where 
			pr.calendarid=cc.calendarid and cu.userid=cc.userid";
			mysqli_select_db($conn, $dbname);
			$queryresult = mysqli_query($conn, $eventsql);
			if(!empty(mysqli_error($conn)))
			{
				trigger_error('error:'.mysqli_error($conn));
			}
			$totalrows=mysqli_num_rows($queryresult);
			trigger_error('Matching Events in DB Fetched : Total Rows ' . $totalrows);
			if($totalrows > 0)
			{				
				while ($fetchrow = mysqli_fetch_assoc($queryresult)) 
				{	
					trigger_error('Event SQL Row ' . json_encode($fetchrow));
					$parentid=$fetchrow['id'];
					$recurringid=$fetchrow['gsuiteparentid'];
					$lastsynched=$fetchrow['lastsynched'];
					$gcalid=$fetchrow['gcalid'];
					$useremail=$fetchrow['email'];
					$dbcalendarid=$fetchrow['dbcalendarid'];

					$formatteddate=new DateTime();
					$formatteddate->setTimestamp($lastsynched);
					$onemonthafter=$formatteddate->add(new DateInterval('P30D'));
					$upperlimit=$onemonthafter->getTimestamp();
					$onemonthafterStr=$onemonthafter->format('c');
					trigger_error('Fetching Batch Events for ' . $useremail . ' Recurring id ' . $recurringid .  ' MaxTime ' . $onemonthafterStr );
					
					$instanceresult=fetchEventInstanceInBatch($useremail,$gcalid,$recurringid,$onemonthafterStr);
					
					if($instanceresult['status'] == 'success')
					{
						// Iterate and populate the DB
						// Sync with Zoho
						$instanceevents=$instanceresult['data'];
						trigger_error('Event Instance Count ' . sizeof($instanceevents));
						$index=0;
						foreach($instanceevents as $instanceevent)
						{
							trigger_error('Index Count ' . ++$index);
							$instanceevent['calendarid']=$dbcalendarid;
							$instanceevent['parentid']=$parentid;
							addEvent($instanceevent);
							$ruleresult=runRulesforEvent($instanceevent);					
							$matchingrules=$ruleresult['data'];
							$inputparams=null;
							if(count($matchingrules) > 0)
							{
								
								$thematchingrule=$matchingrules[0]; // TODO: Sort and get the highest priority matching rule
								trigger_error('Matching Rules Array ' . json_encode($thematchingrule));
								$inputparams=array('zprojectid'=>$thematchingrule['zprojectid']);
								
							}else{
								$inputparams=array('zprojectid'=>UNMATCHEDRULEPROJECT);
							}						
							createorUpdateZProjectTasks($instanceevent,$inputparams);
						}
						$updatelastsync="update parent_recurring_event set lastsynched=$upperlimit where id=$parentid";
						$updateresult = mysqli_query($conn, $updatelastsync);
						if(!empty(mysqli_error($conn)))
						{
							trigger_error('Failed to update lastsync : ' . ' ' . $updatelastsync . mysqli_error($conn));
						}
					}

				}
			}
		}
	}catch(Exception $e)
	{
		triggger_error('Unable to add batch event using the schedule to ' . $e->getMessage());
	}
}