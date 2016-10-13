<?php

/**
 * Description of implementation
 *
 * @author Cameron
 */
class Implementation {
    
    /* Constants */
    
    /* Model name to use */
    public $model_name;
    
    /** MongoDB collection to use */
    public $collection_name;
    
    public $view_permission = "";
    public $add_permission = "";
    public $edit_permission = "";
    public $delete_permission = "";
   
    
    public function __construct(){
        
        $model_name = $this->model_name;
        
        $this->model = self::LoadModel($model_name);
    }
    
    /* Database operations */
    
    public function Insert($data){
        global $db;
        
        $cn = self::CollectionName();
    }
    
    public function ReadMany($params = null, $options = null){
        global $db;
        $cn = $this->CollectionName();
        
        if($options == null){
            $options = [
               'projection' => ['_id' => 0],
            ];
        }
        
        $query = new MongoDB\Driver\Query($params,$options);
        $rows = $db->executeQuery($cn,$query);
        
        $output = [];
        foreach($rows as $data_row){
            $output[] = $data_row;
        }
        
        return $output;
    }
    
    public function Read($id,$options = null){
        global $db;
        $cn = $this->CollectionName();
        
        $params = [
            'id' => $id
        ];
        
        if($options == null){
            $options = [
               'projection' => ['_id' => 0],
            ];
        }
        
        $query = new MongoDB\Driver\Query($params,$options);
        $rows = $db->executeQuery($cn,$query);
        
        $output = [];
        foreach($rows as $data_row){
            return $data_row;
        }
        
        return false;
    }
    
    public function Update($id, $data){
        
    }
    
    public function Delete($id){
        
    }
    
    /* Validation */
    
    public function ValidateInput($data){
        
    }
    
    public function CollectionName(){
        global $config;
        
        return $config['mongodb_db_name'] . '.' . $this->collection_name;
    }
    
    
    public static function LoadModel($model_name){
        
        try{
            
            $path = "models/" . $model_name . ".json";
            
            $file_contents = file_get_contents($path);
            
            return json_decode($file_contents,true);
            
            
        }catch(Exception $e){
            
            echo "<span class=\"error\">{$e->getMessage()}</span>";
            
           
        }
        
        return false;
    }
}
