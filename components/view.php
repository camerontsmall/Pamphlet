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
        $this->$implemenation = new $this->implementation_name();
    }
    
    public function APIMethod(){
        
        
        
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
