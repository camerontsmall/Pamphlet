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
                    $options = [];
                    //Add filter
                    if(isset($_GET['q'])){ 
                        $filter = array_merge($filter,((array) json_decode($_GET['q'])));
                    }
                    //Add limit (0 = no limit)
                    if(isset($_GET['l'])){
                        $limit = (integer) $_GET['l'];
                        if($limit > 0){
                            $options['limit'] = $limit;
                            $data['limit'] = $limit;
                        }
                    }
                    //Add offset
                    if(isset($_GET['o'])){
                        $offset = (integer) $_GET['o'];
                        $options['skip'] = $offset;
                        $data['offset'] = $offset;
                    }
                    
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
                    
                    
                    return $this->OutputMany($filter, $options);
                }
                
                break;
            default:
                return ["ResponseStatus" => "Error", "ErrorName" => "InvalidRequestMethod", "Note" => "This API endpoint only supports GET requests"];
        }
    }
    
    public function Output($id){
        $data = $this->implementation->Read($id);
        if($data['public'] === false){
            return null;
        }
        return $data;
    }
    
    public function OutputMany($filter,$options){
        $data =  $this->implementation->ReadMany($filter,$options);
        foreach($data as $key => $item){
            if($item->public === false){
                unset($data[$key]);
            }
        }
        return array_values($data);
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
