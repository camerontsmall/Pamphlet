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
        
        /* Create standard control only if we have an implementation */
        if($this->implementation){
            
            if($this->task_parts[1] == "add"){
                
                $form = new ModelForm($this->implementation->model, "add_form", $action, "PUT", "");

                $form->render();
                
            }else if(($this->task_parts[1]) > 0){
                
                $this->PrintEditForm(intval($this->task_parts[1]));
                
                
            }else{
                $this->PrintItemList();
            }
            
        }else{
            echo "Put your content for page $this->$name here"; 
        }
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
    
    function PrintBreadcrumbs(){
        
        $task_names = $this->TaskNames();
        
        $full_task = "";
        
        for($i = 0; $i < count($task_names); $i++){
            if($i > 0){ echo '<i class="material-icons">chevron_right</i>'; $slash = '/'; }
            $full_task .= $slash . $task_names[$i];
            echo "<a href=\"./?a=$full_task\">" . $task_names[$i] . "</a>";
        }
        if($this->task_parts[1] != "add") echo "<a class=\"bc-action\" href=\"./?a=video/add\">New<i class=\"material-icons\">add</i></a>";
    }
    
    function PrintItemList(){
        
        $data = $this->implementation->ReadMany([]);
        
        $data = $this->PrepareData($data);
        
        $list = new DynamicList($data, "datalist");
        
        $list->display();
    }
    
    function PrintEditForm($id){
        
        $data = $this->implementation->Read($id);
        
        $action = $this::$name . '/' . $id;
        
        $form = new ModelForm($this->implementation->model, "edit-form", $action, "PUT", "");
        
        $form->import_object($data);
        
        $form->render();
        
    }
    
    /**
     * Convert data set for use in DynamicList
     * @param type $data
     * @return type
     */
    function PrepareData($data){
        return $data;
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
