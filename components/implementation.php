<?php

/**
 * Description of implementation
 *
 * @author Cameron
 */
class Implementation {
    
    /* Constants */
    
    /* Model name to use */
    public static $model;
    
    /** MongoDB collection to use */
    public static $collection;
   
    
    public function __construct(){
        
        $this->model = new Model($this::$model);
        
    }
    
    /* Database operations */
    
    public function Insert($data){
        $cn = self::CollectionName();
    }
    
    public function ReadAll($params = null){
        global $db;
        $cn = $this->CollectionName();
        
        $params = [];
        
        $options = [
           'projection' => ['_id' => 0],
        ];
        
        $query = new MongoDB\Driver\Query($params,$options);
        $rows = $db->executeQuery($cn,$query);
        
        $output = [];
        foreach($rows as $data_row){
            $output[] = $data_row;
        }
        
        return $output;
    }
    
    public function Read($id){
        
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
        
        return $config['mongodb_db_name'] . '.' . $this::$collection;
    }
    
}
