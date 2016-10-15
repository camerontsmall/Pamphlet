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
        
        self::loadVideoPreviewTemplate();
        
        if($tp[1] == 'add'){
            
            $form = new ModelForm($this->implementation->model, "add_form", 'video', "POST", "");

            $form->render();
            
        }else if($tp[1]){
           
            $this->editVideoPage();
            
        }else{
            
            $this->listVideoPage();
        }
        
    }
    
    function listVideoPage(){
        
            $data = $this->implementation->ReadMany([]);
            
            
            ?>
<!--
<div class="richlist-top-bar theme-color">
    <span><i class="material-icons">add</i>Add</span>
</div> -->
<div class="row">
    
    <div class="small-12 large-8 column">
        <?php
        
        $list_data = self::convert_list($data);
        $list = new DynamicList($list_data, "videolist");
        $list->display();
        ?>
    </div>
   
    
    <div class="small-12 large-4 column video-info" id="video-preview-section">
        <div class="video-preview-parent">
            <div class="video-preview-container">
                <div class="video-preview"></div>
            </div>
        </div>
        <div class="video-info-text">
            <p>Select a video to load preview</p>
        </div>
        <div class="video-info-actions theme-color"></div>
    </div>
    
    
    
</div>
<?php
    }
    
    
    function editVideoPage(){
        $tp = $this->task_parts;  
        
        $data = $this->implementation->Read($tp[1]);
        
        $model = $this->implementation->model;
        
        //$model['title'] = 'Cheese';

        $action = 'video/' . $tp[1];
        $form = new ModelForm($model, "add_form", $action, "PUT", 'video',"loadVideoPreview('" . $data->{'_id'} . "');");
        $form->import_object($data);
        
        ?>
<div class="row">
    
    <div class="small-12 large-8 column">
        <?php
        
        $form->render();
        ?>
    </div>
   
    
     <div class="small-12 large-4 column video-info" id="video-preview-section">
        <div class="video-preview-parent">
            <div class="video-preview-container">
                <div class="video-preview"></div>
            </div>
        </div>
        <div class="video-info-text">
        </div>
        <div class="video-info-actions theme-color"></div>
    </div>
    
    <script>
        loadVideoPreview('<?= $data->{'_id'} ?>');
    </script>
    
</div>
<?php
        
    }
    
    static function loadVideoPreviewTemplate(){
        ?>
        <script id="video-preview-template" type="text/x-handlebars-template">
            <div class="video-preview-parent">
                <div class="video-preview-container">
                    <iframe class="video-preview" src="./generated.php?a=video/{{_id}}" ></iframe>
                </div>
            </div>
            <div class="video-info-text">
                <h3>{{title}}</h3>
                <p>
                    <span>Posted {{date}}</span>
                    <span style="font-style:italic;">{{tags}}</span>
                </p>
                <p>{{{description}}}</p>
            </div>
            <div class="richlist-bottom-bar theme-color">
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
    
    function GenerateMethod() {
        $tp = $this->task_parts;
        
        echo "<!doctype html>",PHP_EOL;
        echo "<html>" . PHP_EOL . "<body style=\"margin:0px; height:100vh; width:100%; overflow:hidden;\">",PHP_EOL;
        
        if($tp[1]){
            $id = $tp[1];
            $data = $this->implementation->Read($id);
            echo $data->source;
        }
        
        echo "<style>.video-js{ width:100%; height:100%; </style>", PHP_EOL;
        echo PHP_EOL . "</body>" . PHP_EOL . "</html>";
        
    }
    
}
