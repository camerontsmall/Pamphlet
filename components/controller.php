<?php

/**
 * Description of controller
 *
 * @author Cameron
 */
class Controller {
    
    /* Root task name to access this page */
    public static $name;
     /* Friendly name to display in the sidebar */
    public static $title;
    
    /* The implementation this controller connects to */
    public static $implementation;
    
    /* Variables */
    
    /* Task name passed from URL */
    public $task;
    
    public function __construct($task) {
        $this->task = $task;
    }
    
    /** Method to display editor pages */
    function UIMethod(){
        global $task;
        echo "Loaded page $task";
    }
    
    /** Method to generate API pages. Should return a single object */
    function APIMethod(){
        global $task;
        return "Loaded API for $task";
    }
    
    /** Return task name for breadcrumbs (array of strings */
    function TaskNames(){
        return explode('/', $this->task);
    }
    
    
    /* Generic static functions */
    
    static function loadControllerByName($name){
        
        foreach(get_declared_classes() as $class){
            if(is_subclass_of($class, 'Controller')){
                if($class::$name == $name){
                    return $class;
                }
            }
        }
    }
    
    static function listAll(){
        $controller_list = [];
        foreach(get_declared_classes() as $class){
            if(is_subclass_of($class, 'Controller')){
                $controller_list[] = $class;
            }
        }
        return $controller_list;
    }
    
}
