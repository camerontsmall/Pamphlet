<?php

class video_implementation extends Implementation{
    
    public $model_name = "video";
    public $collection_name = "video";
    
}

class video_controller extends Controller{
    
    public static $name = "video";
    public static $title = "Videos";

    public $implementation_name = "video_implementation";
    
    public function TaskNames() {
        $task_parts = explode('/',$this->task);
        if($task_parts[1] != null){
            return ["Videos","New"];
        }else{
            return ["Videos"];
        }
    }
    
    public function UIMethod() {
        
        $task_parts = explode('/',$this->task);
        if($task_parts[1]){
            echo "New Video!";
            
            $model = $this->implementation->model;
            echo "<br />";
            var_dump($model);
            
        }else{
            
            $this->listVideoPage();
        }
    }
    
    function listVideoPage(){
        
            $data = $this->implementation->ReadMany([]);
            
            $video = $this->implementation->Read(15);
            
            ?>
<div class="richlist-top-bar theme-color">
    <span>Add</span>
</div>
<div class="richlist-parent">
    
    <div class="richlist-left-pane">
        <?php
        
        $list_data = self::convert_list($data);
        $list = new DynamicList($list_data, "videolist");
        $list->display();
        ?>
    </div>

    <script id="video-preview-template" type="text/x-handlebars-template">
        <div class="video-preview-parent">
            <div class="video-preview-container">
                <div class="video-preview">{{{source}}}</div>
            </div>
        </div>
        <div class="richlist-data-form">
            <h3>{{title}}</h3>
            <p>
                <span>Posted {{date}}</span>
                <span style="font-style:italic;">{{tags}}</span>
            </p>
            <p>{{{description}}}</p>
        </div>
        <div class="richlist-bottom-bar theme-color">
            <span>
                <a href="./?a=video/{{id}}">Edit</a>
            </span>
        </div>
    </script>
   
    
    <div class="richlist-right-pane" id="video-preview-section">
        <div class="video-preview-parent">
            <div class="video-preview-container">
                <div class="video-preview"></div>
            </div>
        </div>
        <div class="richlist-data-form">
            <p>Select a video to preview</p>
        </div>
        <div class="richlist-bottom-bar theme-color"></div>
    </div>
    
     <script>
        var vid_source = $("#video-preview-template").html();
        var template = Handlebars.compile(vid_source);
        //loadVideoPreview(15);
    </script>
    
</div>
<?php
    }
    
    static function processTags($tags){
        $tarray = explode(' ', $tags);
        $output = "";
        foreach($tarray as $tag){
            $output .= "#$tag ";
        }
        return $output;
    }
    
    static function convert_list($data){
        $output = [];
        foreach($data as $item){
            $output[] = ["Title" => $item->title, "Type" => $item->type, "Tags" => $item->tags, "Date posted" => $item->date, "onclick" => "loadVideoPreview($item->id);"];
        }
        return $output;
    }
    
}

class video_view extends View{
    
    public static $api_endpoint = "videos";
    
    public $implemenation_name = "video_implementation";
    
}
