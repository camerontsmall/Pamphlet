<?php

class channel_implementation extends Implementation{
    
    public $model_name = "channel";
    public $collection_name = "channel";
    
}

class channel_controller extends Controller{
    
    public static $name = "channel";
    public static $title = "Channels";

    public $implementation_name = "channel_implementation";
    public $view_name = "channel_view";
    
    
    function UIMethod(){
        
        /* Create standard control only if we have an implementation */
        if($this->implementation){
            
            if($this->task_parts[1] == "add"){
                
                $this->PrintAddForm();
                
            }else if($this->task_parts[1] == "studio"){
            
                $this->BroadcastStudio($this->task_parts[2]);
                
            }else if(($this->task_parts[1]) > 0){
                
                $this->PrintEditForm($this->task_parts[1]);
                
                
            }else{
                $this->PrintItemList();
            }
            
        }else{
            echo "Put your content for page {$this::$name} here"; 
        }
    }
    
    function PrepareModel(){
        $model = $this->implementation->model;
        
        $vid_imp = new video_implementation();
        //$streams = $vid_imp->ReadMany(['live' => 1]);
        $streams = $vid_imp->ReadMany();
        
        foreach($streams as $video){
            $model['properties']['video_id']['enum'][] = $video->{_id};
            $model['properties']['video_id']['options']['enum_titles'][] = $video->title;
        }
        
        return $model;
    }
    
    function PrepareData($data) {
        $c = [];
        foreach($data as $item){
            $on_air = ($item->on_air && $item->public)? 'On Air' : (($item->public)? "Off Air" : "Hidden" );
            $c[] = [
                "action" => "channel/$item->_id", 
                "Title" => $item->title, 
                "Status" => $on_air, 
                "Description" => substr(htmlentities($item->description),0,30) . "...",
                "Studio" => "<a href=\"./?a=channel/studio/{$item->_id}\"><i class=\"material-icons\">dvr</i></a>"
                ];
        }
        return $c;
    }
    
    function PrintBreadcrumbs(){
        $name = $this::$name;
        $title = $this::$title;
        $item_name = $this->implementation->model['title'];
        
        $tp = $this->task_parts;
        if($tp[1] == 'add'){
            echo "<a href=\"./?a=$name\">$title</a><i class=\"material-icons\">chevron_right</i><a href=\"./?a=$name/add\">New $item_name</a>";
        }else if($tp[1] == 'studio'){
            $doc = $this->implementation->Read($tp[2]);
            echo "<a href=\"./?a=$name\">$title</a><i class=\"material-icons\">chevron_right</i><a href=\"./?a=$name/$tp[2]\">{$doc->title}</a><i class=\"material-icons\">chevron_right</i><a href=\"./?a=channel/studio/{$tp[2]}\">Broadcast Studio</a>";
            echo "<a class=\"bc-action\" target=\"_blank\" href=\"./api_public.php?a=$name/$tp[2]\">API<i class=\"material-icons\">swap_horiz</i></a>";
            echo "<a class=\"bc-action\" target=\"_blank\" href=\"./generated.php?a=$name/$tp[2]\" title=\"Append '&autoplay' to autoplay videos\">Embed<i class=\"material-icons\">code</i></a>";
        }else if($tp[1]){
            $doc = $this->implementation->Read($tp[1]);
            echo "<a href=\"./?a=$name\">$title</a><i class=\"material-icons\">chevron_right</i><a href=\"./?a=$name/$tp[1]\">{$doc->title}</a>";
            echo "<a class=\"bc-action\" href=\"./?a=$name/add\">New $item_name<i class=\"material-icons\">add</i></a>";
            echo "<a class=\"bc-action\" target=\"_blank\" href=\"./api_public.php?a=$name/$tp[1]\">API<i class=\"material-icons\">swap_horiz</i></a>";
            echo "<a class=\"bc-action\" target=\"_blank\" href=\"./generated.php?a=$name/$tp[1]\" title=\"Append '&autoplay' to autoplay videos\">Embed<i class=\"material-icons\">code</i></a>";
            echo "<a class=\"bc-action\" href=\"./?a=$name/studio/$tp[1]\">Studio<i class=\"material-icons\">dvr</i></a>";
        }else{
            echo "<a href=\"./?a=$name\">$title</a>";
            echo "<a class=\"bc-action\" href=\"./?a=$name/add\">New $item_name<i class=\"material-icons\">add</i></a>";
            echo "<a class=\"bc-action\" target=\"_blank\" href=\"./api_public.php?a=$name\">API<i class=\"material-icons\">swap_horiz</i></a>";
        }
    }
    
    function BroadcastStudio($channel_id){
        
        $input_data = $this->implementation->Read($channel_id);
        
        include('res/channel/studio.php');
        
    }

    
}

class channel_view extends View{
    
    public static $api_endpoint = "channel";
    
    public $implementation_name = "channel_implementation";
    
    public function Output($id){
        $data = $this->implementation->Read($id);
        
        //&fast is used by generate page to get content id quickly
        if(!isset($_GET['fast'])){
            if($data->on_air){
                $v_view = new video_view(null);
                $data->content = $v_view->Output($data->video_id);
            }else{
                $data->content->source = $this->PosterHTML($data->poster);
            }
        }
        //Content ID to indicate whether state has changed
        $data->content_id = ($data->on_air)? "video_" . $data->video_id : "poster_" . $data->poster;
        
        return $data;
    }
    
    function GenerateMethod() {
        $tp = $this->task_parts;
        
        if($tp[1]){

            echo "<!doctype html>",PHP_EOL;
            echo "<html>" . PHP_EOL . "<body style=\"margin:0px; height:100vh; width:100%; overflow:hidden;\">",PHP_EOL;

            $id = $tp[1];
            
            $data = $this->Output($id);
            
            //If a type is set ie. this is a video object
            if($data->content->type){
                $player_name = mediaPlayer::getPlayer($data->content->type);
                $player = new $player_name();
                $data->content = $player->build($data->content);
            }

            echo $data->content->source;

            echo "<style>.video-js{ width:100%; height:100%; </style>", PHP_EOL;
            echo "<script>var channel_id = '$data->_id'; var content_id = '$data->content_id';</script>";
            echo "<script src=\"bower_components/jquery/dist/jquery.min.js\"></script>";
            echo "<script src=\"res/channel/js/auto_refresh.js\"></script>";
            echo PHP_EOL . "</body>" . PHP_EOL . "</html>";
        
        }
        
    }
    
    function PosterHTML($url){
        $html = "<img src=\"$url\" style=\"height:auto; width:100%\" />";
        return $html;
    }
}
