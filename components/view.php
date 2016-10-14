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
    
    public function __construct() {
        
        if(class_exists($this->implementation_name)){
            $this->implementation = new $this->implementation_name();
        }else{
            throw new Exception("Invalid implementation name \"$this->implementation_name\"");
        }
    }
    
    public function APIMethod(){
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
                return ["ResponseStatus" => "Error", "ErrorName" => "InvalidRequestMethod", "Note" => "This API endpoint only supports GET requests"];
        }
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
