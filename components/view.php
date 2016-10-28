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
                    
                    $filter = [];
                    //Add filter
                    if(isset($_GET['q'])){ 
                        $filter = array_merge($filter,((array) json_decode($_GET['q'])));
                    }
                    //Add limit (0 = no limit)
                    $limit = (isset($_GET['l']))? (integer) $_GET['l'] : 0;
                    
                    //Apply category filter
                    if(isset($_GET['c'])){
                        
                        $category = $_GET['c'];


                        $c_imp = new category_implementation;
                        $all_cats = $c_imp->ReadMany();

                        //Translate title to ID
                        foreach($all_cats as $cat){
                            if(strtolower($category) == strtolower($cat->title)){
                                $category = $cat->_id;
                            }
                        }

                        $filter = ['$and' => [(object) $filter, ['category' => $category]]];
                        $data['category'] = $category;
                    }
                    
                    
                    return $this->OutputMany($filter, $limit);
                }
                
                break;
            default:
                return ["ResponseStatus" => "Error", "ErrorName" => "InvalidRequestMethod", "Note" => "This API endpoint only supports GET requests"];
        }
    }
    
    public function Output($id){
        return $this->implementation->Read($id);
    }
    
    public function OutputMany($filter,$limit){
        $options = ($limit > 0)? ['limit' => $limit]: [];
        return $this->implementation->ReadMany($filter,$options);
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
