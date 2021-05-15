<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'config.inc.php');
fetchZohoProjects();

function getAccessToken()
{

	$reFresh_token= "1000.9070ecccc0385ee729fb25cccd568b30.04ae2e29d1a12c3609ed838608370f27";
	$client_id= "1000.7D1I5R0FUQXMDOCZLXWDZUP4V6ZTHI";
	$client_secret= "67c3871c3efd04cb35781cf25d31643a409e3dc10f";
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
			$accesstoken=getAccessToken();
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
