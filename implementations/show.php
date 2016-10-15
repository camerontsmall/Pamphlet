<?php

class show_implementation extends Implementation{
    
    public $model_name = "show";
    public $collection_name = "show";
    
}

class show_controller extends Controller{
    
    public static $name = "show";
    public static $title = "Shows";

    public $implementation_name = "show_implementation";
    
    function PrepareData($data) {
        $c = [];
        foreach($data as $item){
            $c[] = ["action" => "show/$item->_id", "Title" => $item->title, "Tags" => $item->tags, "Description" => substr(htmlentities($item->description),0,30) . "..."];
        }
        return $c;
    }
    
}

class show_view extends View{
    
    public static $api_endpoint = "show";
    
    public $implementation_name = "show_implementation";
    
}
