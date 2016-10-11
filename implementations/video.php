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
        $task_parts = explode('/',$this->task);
        if($task_parts[1]){
            return ["Videos","New"];
        }else{
            return ["Videos"];
        }
    }
    
    public function UIMethod() {
        
        $task_parts = explode('/',$this->task);
        if($task_parts[1]){
            echo "New Video!";
        }else{
            
            echo "<a href=\"./?a=video/new\">Go!</a>";
            $imp = new video_implementation();

            $data = $imp->ReadMany(["id" => 18]);


            foreach($data as $item){
                echo "<div class=\"video-preview\">";
                echo $item->source;
                echo "</div>";
            }

            $list = new DynamicList($data, "test");
            //$list->display();
        }
    }
    
}

class video_view extends View{
    
    public static $implemenation = "video_implementation";
    public static $api_path = "videos";
}
