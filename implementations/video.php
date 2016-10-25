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
            
            $form = new ModelForm($this->prepareModel(), "add_form", 'video', "POST", "video");

            $form->render();
            
        }else if($tp[1]){
           
            $this->editVideoPage();
            
        }else{
            
            switch($_GET['f']){
                case 'live':
                    $filter = ['live' => 1];
                    break;
                case 'vod':
                    $filter = ['live' => 0];
                    break;
                default:
                    $filter = [];
            }
            
            $this->listVideoPage($filter);
        }
        
    }
    
    function listVideoPage($filter = []){
        
            $data = $this->implementation->ReadMany($filter,['sort' => ['date' => -1]]);
            
            
            ?>
<!--
<div class="richlist-top-bar theme-color">
    <span><i class="material-icons">add</i>Add</span>
</div> -->
<div class="row">
    
    <div class="small-12 large-8 column">
        <div class="list-filter">
            Filter
            <span class="links">
                <a href="./?a=video">All</a>
                <a href="./?a=video&f=live">Live</a>
                <a href="./?a=video&f=vod">VOD</a>
            </span>
        </div>
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
        
        $model = $this->PrepareModel();
        
        //$model['title'] = 'Cheese';

        $action = 'video/' . $tp[1];
        $form = new ModelForm($model, "add_form", $action, "PUT", 'video',"loadVideoPreview('" . $data->{'_id'} . "');");
        $form->import_object($data);
        
        ?>
<div class="row">
    
    <div class="small-12 medium-12 large-8 column">
        <?php
        
        $form->render();
        ?>
    </div>
   
    
     <div class="small-12 medium-12 large-4 column video-info" id="video-preview-section">
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
                    <iframe class="video-preview" src="./generated.php?a=video/{{_id}}" allowfullscreen></iframe>
                </div>
            </div>
            <div class="video-info-text">
                <h4>{{title}}</h4>
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
        
        $player_types = mediaPlayer::kpTypes();
        
        foreach($data as $item){
            $_id = (string) $item->{'_id'};
            $output[] = [ 
                "action" => "video/$_id",
                "Title" => $item->title, 
                "Type" => $player_types[$item->type], 
                "Tags" => $item->tags, 
                "Date posted" => $item->date, 
                "" => "<a href=\"javascript:void(0);\" onclick=\"loadVideoPreview('{$_id}');\"><i class=\"material-icons\">play_circle_outline</i></a>"
            ];
        }
        return $output;
    }
    
    
    function PrepareModel() {
        
        $model = $this->implementation->model;
        
        $show_imp = new show_implementation();
        $shows = $show_imp->ReadMany();
        
        $model['properties']['date']['default'] = date('Y-m-d');
        
        $model['properties']['show_id']['enum'][] = "";
        $model['properties']['show_id']['options']['enum_titles'][] = "";
        
        foreach($shows as $show){
            $model['properties']['show_id']['enum'][] = $show->{_id};
            $model['properties']['show_id']['options']['enum_titles'][] = $show->title;
        }
        
        $player_types = mediaPlayer::getPlayerTypes();
        
         foreach($player_types as $player_type){
            $model['properties']['type']['enum'][] = $player_type->name;
            $model['properties']['type']['options']['enum_titles'][] = $player_type->title;
        }
        
        $model = category_implementation::ModelAddCategories($model);
       
        return $model;
    }
    
    function PrintBreadcrumbs(){
        $name = $this::$name;
        $title = $this::$title;
        $item_name = $this->implementation->model['title'];
        
        $tp = $this->task_parts;
        if($tp[1] == 'add'){
            echo "<a href=\"./?a=$name\">$title</a><i class=\"material-icons\">chevron_right</i><a href=\"./?a=$name/add\">New $item_name</a>";
        }
        else if($tp[1]){
            $doc = $this->implementation->Read($tp[1]);
            echo "<a href=\"./?a=$name\">$title</a><i class=\"material-icons\">chevron_right</i><a href=\"./?a=$name/$tp[1]\">{$doc->title}</a>";
            echo "<a class=\"bc-action\" href=\"./?a=$name/add\">New $item_name<i class=\"material-icons\">add</i></a>";
            echo "<a class=\"bc-action\" target=\"_blank\" href=\"./api_public.php?a=$name/$tp[1]\">API<i class=\"material-icons\">swap_horiz</i></a>";
            echo "<a class=\"bc-action\" target=\"_blank\" href=\"./generated.php?a=$name/$tp[1]\">Embed<i class=\"material-icons\">code</i></a>";
        }else{
            echo "<a href=\"./?a=$name\">$title</a>";
            echo "<a class=\"bc-action\" href=\"./?a=$name/add\">New $item_name<i class=\"material-icons\">add</i></a>";
            echo "<a class=\"bc-action\" target=\"_blank\" href=\"./api_public.php?a=$name\">API<i class=\"material-icons\">swap_horiz</i></a>";
        }
    }
  
    
}

class video_view extends View{
    
    public static $api_endpoint = "video";
    
    public $implementation_name = "video_implementation";
    
    /* Return generated data */
    public function APIMethod(){
        $tp = $this->task_parts;
        
        switch($_SERVER['REQUEST_METHOD']){
            case 'GET':
                
                if($tp[1]){
                    $id = $tp[1];
                    return $this->Output($id);
                }else{
                    $params = (array) json_decode($_GET['q']);
                    return $this->implementation->ReadMany($params);
                }
                
                break;
            default:
                return ["ResponseStatus" => "Error", "ErrorName" => "InvalidRequestMethod", "Note" => "This API endpoint only supports GET requests"];
        }
    }
    
    public function Output($id){
        $data = $this->implementation->Read($id);
        
        //Convert params array to object
        $kp_params = [];
        foreach($data->params as $param){
            $kp_params[$param->name] = $param->value;
        }
        
        $data->params = $kp_params;
                     
        $player_name = mediaPlayer::getPlayer($data->type);
        $player = new $player_name();
        $data = $player->build($data);
        return $data;
    }
    
    function GenerateMethod() {
        $tp = $this->task_parts;
        
        if($tp[1]){
            
        echo "<!doctype html>",PHP_EOL;
        echo "<html>" . PHP_EOL . "<body style=\"margin:0px; height:100vh; width:100%; overflow:hidden;\">",PHP_EOL;
        
        $id = $tp[1];
        $data = $this->Output($id);
        
        echo $data->source;
        
        echo "<style>.video-js{ width:100%; height:100%; </style>", PHP_EOL;
        echo PHP_EOL . "</body>" . PHP_EOL . "</html>";
        
        }
        
    }
    
}


/**
 * Extendable class for defining media player modules
 * 
 * New media players MUST be an extension of this class or they will not be detected
 */
class mediaPlayer {
    
    /* $name: unique and machine-friendly (lowercase, no spaces) name for the player module */
    public $name;
    /* $title: human-friendly title for the player module */
    public $title;
    /* $supported: video player supported sources, array of MIME types as strings */
    public $supported = array();
    /* $properties: defines a set of text properties in key/pair format which may be used by the player */
    public $properties = array();
    /* bool $live: whether the player supports live playback */
    public $live = true;
    /* bool $ondemand: whether the player supports on-demand playback */
    public $ondemand = true;
    /**
     * build
     * Generates HTML source code for the player for use in an iframe.
     * Return as a string.
     * Return false for invalid input.
     * 
     * @param type $video
     * -Video object to generate a player from
     * @param type $setup
     * - Any further setup conditions, may be player-specific
     */
    public static function build($video){
        return $video;
    }
    
    public static function getPlayer($name){
        foreach(get_declared_classes() as $class){
            if(is_subclass_of($class, 'mediaPlayer')){
                $p = new $class();
                if($p->name == $name){
                    return $class;
                }
            }
        }
        return $false;
    }
    
    public static function getPlayerTypes(){
        $players = array();
        foreach(get_declared_classes() as $class){
            if(is_subclass_of($class, 'mediaPlayer')){
                $players[] = new $class();
            }
        }
        return $players;
    }
    
    public static function kpTypes(){
        $players = self::getPlayerTypes();
        $types = array();
        //$types['--'] = var_dump($players) . ' modules found';
        foreach($players as $player){
            $name = $player->name;
            $title = $player->title;
            $types[$name] = $title;
        }
        return $types;
    }
    
    public static function kpLiveTypes(){
        $players = self::getPlayerTypes();
        $types = array();
        //$types['--'] = var_dump($players) . ' modules found';
        foreach($players as $player){
            $name = $player->name;
            $title = $player->title;
            if($player->live){
                $types[$name] = $title;
            }
        }
        return $types;
    }
    
    public static function kpVodTypes(){
        $players = self::getPlayerTypes();
        $types = array();
        //$types['--'] = var_dump($players) . ' modules found';
        foreach($players as $player){
            $name = $player->name;
            $title = $player->title;
            if($player->ondemand){
                $types[$name] = $title;
            }
        }
        return $types;
    }
    
}