<?php

/* Basic error container function */
function error($content){
    return '<span class="error" style="color: #ff0000;">' . $content . '</span>';
}

/* Error display settings */
if(0 || isset($_GET['debug'])){	//Error reporting - disable for production
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
}else{
    ini_set('display_errors', '0');     //don't show any errors...
    error_reporting(E_ALL | E_STRICT);  // ...but do log them
}

/* Try to load configuration */

if((!include 'config.php') && (!include 'config.sample.php')){
    
    echo error("Config file could not be loaded!");
    die();
    
}

/* Initialise database connection */

if(!$db = new MongoDb\Driver\Manager($config['mongodb_connect_string'])){
    echo error("Could not establish database connection");
}

/* Load in dependencies */

require 'components/authenticator.php';
require 'components/customform.php';
require 'components/modelform.php';
require 'components/dynamiclist.php';
require 'components/implementation.php';
require 'components/controller.php';
require 'components/view.php';

/* Load in classes */

require 'components/homepage.php';
require 'components/users.php';

/* Load implementations */

$i_dir = 'implementations';

$comp_includes = scandir($i_dir);

foreach($comp_includes as $comp_ifile){
    if(strpos($comp_ifile, '.php') !== false){
        //echo $dir . '/' . $comp_ifile;
        include($i_dir . '/' . $comp_ifile);
    }
}