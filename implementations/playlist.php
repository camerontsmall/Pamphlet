<?php

class playlist_implementation extends Implementation{
    
    public $model_name = "playlist";
    public $collection_name = "playlist";
    
}

class playlist_controller extends Controller{
    
    public static $name = "playlist";
    public static $title = "Playlists";

    public $implementation_name = "playlist_implementation";
    
    function PrepareData($data) {
        $c = [];
        foreach($data as $item){
            $c[] = ["action" => "playlist/$item->_id", "Title" => $item->title];
        }
        return $c;
    }
    
    function PrepareModel(){
        $model = $this->implementation->model;
        
        $vid_imp = new video_implementation();
        //$streams = $vid_imp->ReadMany(['live' => 1]);
        $streams = $vid_imp->ReadMany();
        
        foreach($streams as $video){
            $model['properties']['videos']['items']['enum'][] = $video->{_id};
            $model['properties']['videos']['items']['options']['enum_titles'][] = $video->title;
        }
        
        return $model;
    }
    
}

class playlist_view extends View{
    
    public static $api_endpoint = "playlist";
    
    public $implementation_name = "playlist_implementation";
    
}
