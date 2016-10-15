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
        
        $vid_imp = new video_implementation();
        $videos = $vid_imp->ReadMany([]);
        
        foreach($videos as $video){
            $model['properties']['video_id']['enum'][] = $video->{_id};
            $model['properties']['video_id']['options']['enum_titles'][] = $video->title;
        }
        
        return $model;
    }
    
    function PrepareData($data) {
        $c = [];
        foreach($data as $item){
            $on_air = ($item->on_air && $item->public)? 'On Air' : (($item->public)? "Off Air" : "Hidden" );
            $c[] = ["action" => "channel/$item->_id", "Title" => $item->title, "Status" => $on_air, "Description" => substr(htmlentities($item->description),0,30) . "..."];
        }
        return $c;
    }
    
}

class channel_view extends View{
    
    public static $api_endpoint = "channel";
    
    public $implementation_name = "channel_implementation";
    
}
