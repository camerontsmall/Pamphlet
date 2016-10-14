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
                
                $this->PrintEditForm($this->task_parts[1]);
                
                
            }else{
                $this->PrintItemList();
            }
            
        }else{
            echo "Put your content for page {$this::$name} here"; 
        }
    }
    
    /** Method to generate API pages. Should return a single object */
    function APIMethod(){
        $tp = $this->task_parts;
        
        switch($_SERVER['REQUEST_METHOD']){
            case 'GET':
                
                if($tp[1]){
                    $id = $tp[1];
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
        $name = $this::$name;
        $title = $this::$title;
        $item_name = $this->implementation->model['title'];
        
        $tp = $this->task_parts;
        if($tp[1] == 'add'){
            echo "<a href=\"./?a=$name\">$title</a><i class=\"material-icons\">chevron_right</i><a href=\"./?a=$name/add\">New $item_name</a>";
        }
        else if($tp[1]){
            $doc = $this->implementation->Read($tp[1]);
            echo "<a href=\"./?a=$name\">$title</a><i class=\"material-icons\">chevron_right</i><a href=\"./?a=$name/$tp[1]\">{$doc->title}</a>";
        }else{
            echo "<a href=\"./?a=$name\">$title</a>";
            echo "<a class=\"bc-action\" href=\"./?a=$name/add\">New $item_name<i class=\"material-icons\">add</i></a>";
        }
    }
    
    function PrintItemList(){
        
        $data = $this->implementation->ReadMany([]);
        
        $data = $this->PrepareData($data);
        
        if(count($data) > 0){
        
            $list = new DynamicList($data, "datalist");

            $list->display();
        }else{
            echo "<div class=\"listcontrols\"><span>No items</span></div>";
        }
    }
    
    function PrintEditForm($id){
        
        $data = $this->implementation->Read($id);
        
        $action = $this::$name . '/' . $id;
        
        $model = $this->implementation->model;
        
        $model->title = "Edit " . $model->title;
        
        $form = new ModelForm($model, "edit-form", $action, "PUT", "");
        
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
