<?php
require_once(__DIR__.DIRECTORY_SEPARATOR.'config.inc.php');
trigger_error('Notification Recieved ' );
$entityBody = file_get_contents('php://input');
trigger_error('Request Body ' . $entityBody);
$headers =  getallheaders();
foreach($headers as $key=>$val){
  trigger_error( $key . ': ' . $val );
}
