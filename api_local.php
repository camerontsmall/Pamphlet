<?php

/* 
 * LOCAL DATA CONTROLLER API 
 * 
 * Read/write API requiring authentication
 */

//Ensure no whitespace is output
ob_start();

header('Content-Type:application/json');

require 'init.php';

if($config['enable_auth']){
    $auth = new authenticator();
}

//$auth = new Authenticator();

$task = $_GET['a'];

$task_parts = explode('/',$task);

$api_controller_name = Controller::loadControllerByName($task_parts[0]);

if(class_exists($api_controller_name)){

    $api_controller = new $api_controller_name($task);

    $data = $api_controller->APIMethod();


}else{
    
    $data = ["ResponseStatus" => "Error", "ErrorName" => "InvalidTask","Task" => $task];
}

ob_end_clean();

echo json_encode($data);