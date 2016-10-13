<?php

/**
 * Controller class handles user actions on an Implementation
 * 
 * The class defines a 
 *
 * @author Cameron
 */
class Controller {
    
    /* Root task name to access this page */
    public static $name;
     /* Friendly name to display in the sidebar */
    public static $title;
    
    /* The name of the implementation this controller connects to */
    public $implementation_name;
    
    /* Variables */
    
    /* Task name passed from URL */
    public $task;
    
    public function __construct($task) {
        $this->task = $task;
        $this->task_parts = explode('/',$task);
        
        $implementation_name = $this->implementation_name;
        
        if(class_exists($implementation_name)){
            $this->implementation = new $implementation_name();
        }
    }
    
    /** Method to display editor pages */
    function UIMethod(){
        global $task;
        echo "Loaded page $task";
    }
    
    /** Method to generate API pages. Should return a single object */
    function APIMethod(){
        $tp = $this->task_parts;
        
        switch($_SERVER['REQUEST_METHOD']){
            case 'GET':
                
                if($tp[1]){
                    $id = intval($tp[1]);
                    return $this->implementation->Read($id);
                }else{
                    $params = (array) json_decode($_GET['q']);
                    return $this->implementation->ReadMany($params);
                }
                
                break;
            default:
                return ["ResponseStatus" => "Error", "ErrorName" => "InvalidRequestMethod"];
        }
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
