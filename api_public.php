<?php

/* 
 * PUBLIC API CONTROLLER
 * 
 * !!!!NO AUTHENTICATION BY DEFAULT!!!!
 */

//Ensure no whitespace or errors are output
ob_start();

require 'init.php';

$task = $_GET['a'];

$task_parts = explode('/',$task);

$api_controller_name = View::loadViewByEndpoint($task_parts[0]);

if(class_exists($api_controller_name)){
    
    $api_controller = new $api_controller_name($task);

    $data = $api_controller->APIMethod();
    
}else{
    
    $data = ["ResponseStatus" => "Error", "ErrorName" => "InvalidTask","Task" => $task];
}

ob_end_clean();

header('Content-Type:application/json');
echo json_encode($data);