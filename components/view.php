<?php

/**
 * Description of view
 *
 * @author Cameron
 */
class View {
    
    /* API endpoint name to use */
    public static $api_endpoint;
    
    /* Class name of the implementation this should connect to */
    public $implementation_name;
    
    /* Implementation class to use for connections */
    public $implementation;
    
    public function __construct($task) {
 
        $this->task = $task;
        $this->task_parts = explode('/',$task);
        
        $implementation_name = $this->implementation_name;
        
        if(class_exists($implementation_name)){
            $this->implementation = new $implementation_name();
        }
        
    }
    
    public function APIMethod(){
        $tp = $this->task_parts;
        
        switch($_SERVER['REQUEST_METHOD']){
            case 'GET':
                
                if($tp[1]){
                    $id = $tp[1];
                    return $this->Output($id);
                }else{
                    return $this->OutputMany();
                }
                
                break;
            default:
                return ["ResponseStatus" => "Error", "ErrorName" => "InvalidRequestMethod", "Note" => "This API endpoint only supports GET requests"];
        }
    }
    
    public function Output($id){
        return $this->implementation->Read($id);
    }
    
    public function OutputMany(){
        $params = (array) json_decode($_GET['q']);
        return $this->implementation->ReadMany($params);
    }
    
    public function GenerateMethod(){
        
        echo "This interface does not have a generate function defined.<br />API Endpoint: " . $this::$api_endpoint;
        
    }
    
    static function loadViewByEndpoint($name){
        
        foreach(get_declared_classes() as $class){
            if(is_subclass_of($class, 'View')){
                if($class::$api_endpoint == $name){
                    return $class;
                }
            }
        }
    }
}
