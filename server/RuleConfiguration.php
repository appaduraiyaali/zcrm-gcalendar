<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'DBUtility.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');
$ruleconfigstr='{
  "rulename": "Sample Rule Test Two By Manush..",
  "projectid": "1600500000001081005",
  "priority": "1",
  "description": "Sample Description for this rule..",
  "emails":"appadurai@bizappln.com",
  "ruledata": {
    "condition": "AND",
    "rules": [
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

/* Manush Start */

$urldata = $_GET;
$method = $_GET['method'];
//echo $method;
if($method=="fetchrules"){
    //fetchallusers();
    $response = fetchAllRules();
    echo $response;
	
}


/* Manush End */
//checkRuleNameExists('Sample Rule');

function saveRule($ruleconfigstr)
{
	$ruleconfig=json_decode($ruleconfigstr,true);
	print_r($ruleconfigstr);
	//echo "test";
	$result=array('status'=>'');
	$conn=getMysqlConnection();
	try{
			$dbname=DBNAME;
			$rulename=$ruleconfig['rulename'];
			$zprojectid=$ruleconfig['projectid'];
			$priority=$ruleconfig['priority'];
			$description=$ruleconfig['description'];
			$emails=$ruleconfig['emails'];
			$criteria=json_encode($ruleconfig['ruledata']);


			if($conn)
			{
				mysqli_select_db($conn, $dbname);			
				
				$saverulesql= "INSERT INTO ruleconfig(rulename, zprojectid,description,criteria,priority,emails)
				VALUES ('$rulename', '$zprojectid', '$description','$criteria',$priority,'$emails')";
				$queryresult =mysqli_query($conn, $saverulesql);
				if ($queryresult === TRUE) {
				  $last_id = $conn->insert_id;
				  trigger_error( "Rule COnfig Insert Successfully : " . $saverulesql);
				  $result=array('status'=>'Success');
				  $result=array('message'=>"Rule COnfig Insert Successfully : " . $saverulesql);
				} else {
				  trigger_error( "Rule COnfig Insert failure : " . $saverulesql. " " . mysqli_error($conn));
				  $result=array('status'=>'Failure');
				  $result=array("Rule COnfig Insert failure : " . $saverulesql. " " . mysqli_error($conn));
				}		
			}

	}catch(Exception $e)
	{

		trigger_error('Unable to save rule '. $ruleconfig['rulename'].' to db ' . $e->getMessage());
	}
	return json_encode($result);
}

function checkRuleNameExists($rulename)
{
	$result=array("nameexists"=>"false");
	$conn=getMysqlConnection();
	try{
			$dbname=DBNAME;
			if($conn)
			{
				mysqli_select_db($conn, $dbname);			
				
				$checkrulesql= "select rulename from ruleconfig where rulename='$rulename'";
				$queryresult =mysqli_query($conn, $checkrulesql);
				
					$rowcount=mysqli_num_rows($queryresult);
					trigger_error('Total Rows ' . $rowcount);
					if($rowcount > 0)
					{
						$result["nameexists"]="true";
					}
				
			}
	}catch(Exception $e)
	{
		trigger_error('Unable to execute rulename '. $rulename.' to db ' . $e->getMessage());
	}
	echo json_encode($result);
	return json_encode($result);
}

function fetchAllRules()
{
	$result=array();
	try
	{
		$ruledataarr=array();
		$dbname=DBNAME;
		$conn=getMysqlConnection();
		if($conn)
		{
			$allprofilequery="select ruleid,rulename,zprojectid,criteria,description,priority,emails from ruleconfig where isactive=true";
			mysqli_select_db($conn, $dbname);
			$queryresult = mysqli_query($conn, $allprofilequery);
			trigger_error('error:'.mysqli_error($conn));
			$totalrows=mysqli_num_rows($queryresult);
			trigger_error('Total Rows ' . $totalrows);
			if($totalrows > 0)
			{
				
				while ($fetchrow = mysqli_fetch_array($queryresult)) 
				{	
					$ruledata=array();			
					$ruledata['ruleid']=$fetchrow['ruleid'];
					$ruledata['rulename']=$fetchrow['rulename'];
					$ruledata['zprojectid']=$fetchrow['zprojectid'];
					$ruledata['criteria']=$fetchrow['criteria'];
					$ruledata['description']=$fetchrow['description'];
					$ruledata['priority']=$fetchrow['priority'];
					$ruledata['emails']=$fetchrow['emails'];
					array_push($ruledataarr,$ruledata);
				}						
				$result['data']=$ruledataarr;
				$result['status']='success';

			}else{
				$result['data']=array();
				$result['status']='success';
			}
	}
	closeConnection($conn);	

	}catch(Exception $e)
	{
		trigger_error('Unable to fetch the rules list due to error ' . $e->getMessage());
		$result["status"]='failure';
		$result['reason']=$e->getMessage();
	}
	//echo json_encode($result);
	//echo json_encode($result);
	return json_encode($result);
	
}