<?php

class video_implementation extends Implementation{
    
    public $model_name = "video";
    public $collection_name = "video";
    
}

class video_controller extends Controller{
    
    public static $name = "video";
    public static $title = "Videos";

    public $implementation_name = "video_implementation";
    
    public function TaskNamesTwo() {
        $task_parts = explode('/',$this->task);
        if($task_parts[1] != null){
            return ["Videos","Add"];
        }else{
            return ["Videos"];
        }
    }
    
    public function UIMethod() {
        
        $tp = $this->task_parts;
        if($tp[1] == 'add'){
            
            $form = new ModelForm($this->implementation->model, "add_form", $action, "POST", "");

            $form->render();
            
        }else if($tp[1]){
             
            $data = $this->implementation->Read($tp[1]);
            
            $form = new ModelForm($this->implementation->model, "add_form", $action, "PUT", "");
            $form->import_object($data);
            $form->render();
            
        }else{
            
            $this->listVideoPage();
        }
        
        self::videoPreviewTemplate();
    }
    
    function listVideoPage(){
        
            $data = $this->implementation->ReadMany([]);
            
            
            ?>
<!--
<div class="richlist-top-bar theme-color">
    <span><i class="material-icons">add</i>Add</span>
</div> -->
<div class="richlist-parent">
    
    <div class="richlist-left-pane">
        <?php
        
        $list_data = self::convert_list($data);
        $list = new DynamicList($list_data, "videolist");
        $list->display();
        ?>
    </div>
   
    
    <div class="richlist-right-pane" id="video-preview-section">
        <div class="video-preview-parent">
            <div class="video-preview-container">
                <div class="video-preview"></div>
            </div>
        </div>
        <div class="richlist-data-form">
            <p>Select a video to load preview</p>
        </div>
        <div class="richlist-bottom-bar theme-color"></div>
    </div>
    
    
    
</div>
<?php
    }
    
    static function videoPreviewTemplate(){
        ?>
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
                    <a href="./?a=video/{{_id}}">
                    Edit
                    <i class="material-icons">edit</i>
                    </a>
                </span>
            </div>
        </script>
        <script>
        var vid_source = $("#video-preview-template").html();
        var template = Handlebars.compile(vid_source);
        </script>
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
            $_id = (string) $item->{'_id'};
            $output[] = [ 
                "Title" => $item->title, 
                "Type" => $item->type, 
                "Tags" => $item->tags, 
                "Date posted" => $item->date, 
                "" => "<a href=\"./?a=video/$_id\"><i class=\"material-icons\">edit</i></a>",
                "onclick" => "loadVideoPreview('{$_id}');"];
        }
        return $output;
    }
    
    
}

class video_view extends View{
    
    public static $api_endpoint = "video";
    
    public $implementation_name = "video_implementation";
    
}
