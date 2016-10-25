<?php

/* 
 * This is a sample plugin
 * 
 */

if(false){
    

class sample_implementation extends Implementation{
    
    public $model_name = "sample";
    public $collection_name = "sample";
    
}

class sample_controller extends Controller{
    
    public static $name = "sample";
    public static $title = "Sample Implementation";

    public $implementation_name = "sample_implementation";
    
    function PrepareModel(){
        $model = $this->implementation->model;
        
        //Process model here 
        
        return $model;
    }
    
    function PrepareData($data) {
        $c = [];
        foreach($data as $item){
            $c[] = ["action" => "sample/$item->_id", "Title" => $item->title, "Tags" => $item->tags, "Date" => $item->date];
        }
        return $c;
    }
    
}

class sample_view extends View{
    
    public static $api_endpoint = "sample";
    
    public $implementation_name = "sample_implementation";
    
}

    
    
    
}