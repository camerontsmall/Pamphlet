<?php

class channel_implementation extends Implementation{
    
    public $model_name = "channel";
    public $collection_name = "channel";
    
}

class channel_controller extends Controller{
    
    public static $name = "channel";
    public static $title = "Channel";

    public $implementation_name = "channel_implementation";
    public $view_name = "channel_view";
    
    
    function PrepareModel(){
        $model = $this->implementation->model;
        
        $model['properties']['video_id']['enum'] = ["1","2"];
        
        return $model;
    }
    
    function PrepareData($data) {
        $c = [];
        foreach($data as $item){
            $c[] = ["action" => "channel/$item->_id", "Title" => $item->title, "Tags" => $item->tags, "Description" => substr(htmlentities($item->description),0,30) . "..."];
        }
        return $c;
    }
    
}

class channel_view extends View{
    
    public static $api_endpoint = "channel";
    
    public $implementation_name = "channel_implementation";
    
}
