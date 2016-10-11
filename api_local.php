<?php

/* 
 * LOCAL DATA CONTROLLER API 
 * 
 * Read/write API requiring authentication
 */

require 'init.php';

//$auth = new Authenticator();

$task = $_GET['a'];

$task_parts = explode('/',$task);

ob_start();

$api_controller_name = Controller::loadControllerByName($task_parts[0]);

$api_controller = new $api_controller_name();

$data = $api_controller->APIMethod();

ob_end_clean();

echo json_encode($data);