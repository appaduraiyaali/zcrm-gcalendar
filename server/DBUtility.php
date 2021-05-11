<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'config.inc.php');
//runSampleQuery();
getMysqlConnection();
function getMysqlConnection()
{
	$servername = DBSERVER;
    $username = DBUSER;
    $password = DBPWD;
    $dbname=DBNAME;

    $conn =mysqli_connect($servername, $username, $password,$dbname);
	//trigger_error('Mysql Connection ' . $conn);     
    if ($conn) {
      //trigger_error("Connection success: ");
    } else {
            trigger_error('error:'.mysqli_error($conn));
    }    
    return $conn;
}

function closeConnection($conn)
{
	mysqli_close($conn);

}



function runSampleQuery()
{
	$conn=getMysqlConnection();
	 $dbname=DBNAME;
	if($conn)
	{
		$allprofilequery="select calendarid,email from calendarconfig";
		mysqli_select_db($conn, $dbname);
		$queryresult = mysqli_query($conn, $allprofilequery);
		 trigger_error('error:'.mysqli_error($conn));
		$totalrows=mysqli_num_rows($queryresult);
		trigger_error('Total Rows ' . $totalrows);
	}
	closeConnection($conn);

}