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
    
    /**
     * Insert pre-validated documents into database
     * 
     * @global type $db
     * @param type $data
     */
    public function Insert($data){
        global $db;
        
        $cn = self::CollectionName();
        
        //Remove any preset IDs !IMPORTANT!
        unset($data->{'_id'});
        
        $bw = new MongoDB\Driver\BulkWrite();
        
        $bw->insert($data);
        
        $result = $db->executeBulkWrite($cn,$bw);
        
        $response = [];
        $response['editor_action'] = 'reload';
        $response['db_output'] = $result;
        
        return $response;
        
    }
    
    public function ReadMany($filter = [], $options = []){
        global $db;
        $cn = $this->CollectionName();
        
        $query = new MongoDB\Driver\Query($filter,$options);
        $cursor = $db->executeQuery($cn,$query);
        
        //$cursor->setTypeMap(['root' => 'array']);
        
        $output = [];
        foreach($cursor as $data_row){
            $doc = $data_row;
            $doc->{'_id'} = (string) $doc->{'_id'};
            $output[] = $doc;
        }
        
        return $output;
    }
    
    public function Read($id,$options = null){
        global $db;
        $cn = $this->CollectionName();
        
        try{
            $params = [
                '_id' => new MongoDB\BSON\ObjectId($id)
            ];

            if($options == null){
                $options = [];
            }

            $query = new MongoDB\Driver\Query($params,$options);
            $rows = $db->executeQuery($cn,$query);

            $output = [];
            foreach($rows as $data_row){
                $doc = $data_row;
                $doc->{'_id'} = (string) $doc->{'_id'};
                return $data_row;
            }
            
        }catch(Exception $e){
            return null;
        }
        
        return null;
    }
    
    public function Update($id_string, $data){
        global $db;
        
        $cn = self::CollectionName();
        
        //Remove any preset IDs !IMPORTANT!
        unset($data->{'_id'});
        
        $bw = new MongoDB\Driver\BulkWrite();
        
        $_id = new MongoDB\BSON\ObjectId($id_string);
        
        if(!$_id){ return ['editor_status' => 'Error: Invalid ObjectID provided']; }
                
        if(!$data){ return ['editor_status' => 'Error: Could not decode dataset']; }

        $bw->update(
            ['_id' => $_id], 
            ['$set' => $data],
            ['multi' => false, 'upsert' => true]
        );

        $result = $db->executeBulkWrite($cn,$bw);

        $response = [];
        $response['editor_status'] = 'Saved at ' . date('H:m:s', time());
        $response['db_output'] = $result;

        return $response;
        
    }
    
    public function Delete($id_string){
        global $db;
        
        $cn = self::CollectionName();
        
        $bw = new MongoDB\Driver\BulkWrite();
        
        $_id = new MongoDB\BSON\ObjectId($id_string);
        
        $bw->delete(
            ['_id' => $_id],
            ['limit' => 1]
        );
        
        $result = $db->executeBulkWrite($cn,$bw);
        
        $response = [];
        $response['editor_action'] = 'reload';
        $response['db_output'] = $result;
        
        return $response;
    }
    
    /* Return number of entries in the collection */
    public function Count(){
        global $db;
        $cn = $this->CollectionName();
        
        $param = [];
        $options = [];
        
        $query = new MongoDB\Driver\Query($params,$options);
        $cursor = $db->executeQuery($cn,$query);
        
        return count($cursor->toArray());
        
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
    
    /* Generic static functions */
    
    static function loadImplementationByName($name){
        
        foreach(get_declared_classes() as $class){
            if(is_subclass_of($class, 'Implementation')){
                if($class::$collection_name == $name){
                    return $class;
                }
            }
        }
    }
    
    static function listAll(){
        $controller_list = [];
        foreach(get_declared_classes() as $class){
            if(is_subclass_of($class, 'Implementation')){
                $controller_list[] = $class;
            }
        }
        return $controller_list;
    }
}
