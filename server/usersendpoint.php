<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once(__DIR__.DIRECTORY_SEPARATOR.'UserConfiguration.php');


$urldata = $_GET;
$method = $_GET['method'];
//echo $method;
if($method=="fetchuser"){
    //fetchallusers();
    $response = fetchallusers();
    echo $response;
}
if($method=="adduser"){
    $useremail = $_GET['useremail'];
	$username=$_GET['username'];
    //addUser($useremail);
    $response = addUser($useremail,$username);
    if($response['status']=="failure"){
        echo json_encode($response);
    }
    else{
        echo $response;
    }
}
if($method=="removeuser"){
    $useremail = $_GET['useremail'];
    removeUser($useremail);
    $response = removeUser($useremail);
    echo $response;
}
//print_r($urldata);
?>