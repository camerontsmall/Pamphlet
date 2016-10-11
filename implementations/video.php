<?php

class video_implementation extends Implementation{
    
    public static $model = "video";
    public static $collection = "video";
    
}

class video_controller extends Controller{
    
    public static $implementation = "video_implementation";
    public static $name = "video";
    public static $title = "Videos";
    
    public function TaskNames() {
        return ["Videos"];
    }
    
    public function UIMethod() {
        echo "Video killed the radio star";
        
        $imp = new video_implementation();
        
        $data = $imp->ReadAll();
        
        foreach($data as $item){
            echo "<div class=\"video-preview\">";
            echo $item->source;
            echo "</div>";
        }
    }
    
}

class video_view extends View{
    
    public static $implemenation = "video_implementation";
    public static $api_path = "videos";
}
