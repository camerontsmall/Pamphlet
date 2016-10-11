<?php

/* PUBLIC API CONTROLLER - NO AUTHENTICATION PROVIDED! */

require 'init.php';

$task = $_GET['a'];

$task_parts = explode('/',$task);

$api_controller = View::loadControllerByName($task_parts[0]);