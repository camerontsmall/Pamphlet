<?php
/**
 * Generate HTML content by extending the View::GenerateMethod class
 */

require('init.php');

$task = $_GET['a'];

$task_parts = explode('/',$task);

$api_controller_name = View::loadViewByEndpoint($task_parts[0]);

if(class_exists($api_controller_name)){
    
    $api_controller = new $api_controller_name($task);

    $api_controller->GenerateMethod();
    
}else{
    
    header('document-type:application/json');
    echo json_encode(["ResponseStatus" => "Error", "ErrorName" => "InvalidTask"]);
}

?>
