<?php

class video_implementation extends Implementation{
    
    public static $model_name = "video";
    public static $db_name = "video";
    
}

class video_controller extends Controller{
    
    public static $implementation = "video_implementation";
    public static $title = "Videos";
    
}

class video_view extends View{
    
    public static $implemenation = "video_implementation";
    public static $api_path = "videos";
}
