<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once(__DIR__.DIRECTORY_SEPARATOR.'RuleConfiguration.php');



$rulesdata = json_decode(file_get_contents('php://input'), true);
//echo "Arul Migu Shree Pattalamman Thunai..";
//print_r($rulesdata);
    //addUser($useremail);
	$ruledata=json_encode($rulesdata,true);
	//print_r($ruledata);
    $response = saveRule($ruledata);
	echo $response;
    //if($response['status']=="failure"){
      //echo json_encode($response);
    //}
    //else{
      //  echo $response;
    //}

//print_r($urldata);
?>