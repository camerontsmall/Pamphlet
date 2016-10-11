<?php

/* Basic error container function */
function error($content){
    return '<span class="error">' . $content . '</span>';
}

/* Try to load configuration */

try{
    require_once 'config.php';
}catch(Exception $e){
    require_once 'config_sample.php';
}finally{
    echo error("Config file could not be loaded!");
    die();
}
/* Initialise database connection */



/* Load in dependencies */

require_once 'components/customform.php';
require_once 'components/dynamiclist.php';
require_once 'components/model.php';
require_once 'components/implementation.php';
require_once 'components/controller.php';
require_once 'components/view.php';
