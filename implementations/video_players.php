<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//Local content players

class videojs_5 extends mediaPlayer{
    public $name = 'html5';
    public $title = 'VideoJS';
    
    public $live = true;
    public $ondemand = true;
    
    public static function build($video){
        $poster = $video->poster;
        $data = json_decode($video->source,1);
        $css = false;   //Whether a primary CSS file has been included yet
        
        $audioonly = true;
        foreach($content->sources as $source){
            if(stripos($source->type,'audio') === false){
                $audioonly = false;
            }
        }
        
        $content->audioonly = $audioonly;
        
        ob_start();
       
            //html::css("plugins/video/videojs/core/video-js-custom.css");
        echo "<link rel=\"stylesheet\" href=\"bower_components/video.js/dist/video-js.min.css\" />", PHP_EOL;
        echo "<script src=\"bower_components/video.js/dist/video.min.js\"></script>", PHP_EOL;
        
        echo "<link rel=\"stylesheet\" href=\"bower_components/vjs-resolution-switcher/lib/videojs-resolution-switcher.css\" />", PHP_EOL;
        echo "<script src=\"bower_components/vjs-resolution-switcher/lib/videojs-resolution-switcher.js\"></script>", PHP_EOL;
        
        
        if($data['autoplay'] == 1 || $_GET['autoplay'] == true){
            $autoplay = 'autoplay';
        }else{
            $autoplay = '';
        }
        
        $muted = (isset($_GET['muted']) || $params['muted'])? "muted" : "";
        
        echo "<video id=\"video\" class=\"vidplayer video-js vjs-default-skin html5vid\" width=\"100%\" height=\"100%\" poster=\"$poster\" controls $muted $autoplay data-setup='{\"techOrder\": [\"html5\",\"flash\"] , \"plugins\": { \"videoJsResolutionSwitcher\" : { \"default\" : \"720\" } }}' $video->code>", PHP_EOL;
        foreach($video->sources as $source){
            $src = $source->src;
            $type = $source->type;
            $res = $source->res;
            $label = $res . 'p';
            echo "<source label=\"$label\" res=\"$res\" src=\"$src\" type=\"$type\" >", PHP_EOL;
        }
        echo "Your browser does not support the video tag";
                
        echo "</video>";
        
        ?>
        <script>
            videojs('#video'); 
            var video = document.getElementById('video_html5_api');
            if (video.addEventListener) {
                video.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                }, false);
            } else {
                video.attachEvent('oncontextmenu', function() {
                    window.event.returnValue = false;
                });
            }
        </script>
        <?php
        
        //echo '<script>videojs(\'#video\').videoJsResolutionSwitcher</script>';
        
        $video->source =  ob_get_contents();
        ob_end_clean();
        
        return $video;
    }
    
}

class audio extends mediaPlayer{
    
    public $name = 'audio';
    public $title = 'Audio';
    
    public $live = true;
    public $ondemand = true;
    
    public static function build($video){
        ob_start();

        $poster = $video->poster;
        $params = $video->params;
        
        echo "<link rel=\"stylesheet\" href=\"bower_components/video.js/dist/video-js.min.css\" />", PHP_EOL;
        echo "<script src=\"bower_components/video.js/dist/video.min.js\"></script>", PHP_EOL;
        
        
        if($params['autoplay'] == 1 || isset($_GET['autoplay'])){
            $autoplay = 'autoplay';
        }else{
            $autoplay = '';
        }
        
        $muted = (isset($_GET['muted']) || $params['muted'])? "muted" : "";
        
        //Load nowplaying information
        if($params['server_type'] && $params['nowplaying_url']){
            $video->server_info = self::ProcessRadioData($params);
        }
        
        if($params['animated_background_type'] == "gifv"){
            $anim_bg = $params['animated_background_url'];
            ?>
            <video preload="auto" style="position:absolute; width:100%; height:100%; "  id="animated-background" autoplay="autoplay" loop="loop">
                   <source src="<?= $anim_bg ?>" type="video/webm" />
            </video>
            <style>
                body{
                    background-color:black;
                }
                .video-js{
                    background-color:transparent;
                }
                .vjs-tech{
                    display:none;
                }
            </style>
        <?php
        }else if($params['animated_background_type'] == 'gif'){
             $anim_bg = $params['animated_background_url'];
            ?>
            <img style="position:absolute; width:100%; height:auto;" src="<?= $anim_bg ?>" />
            <style>
                body{
                    background-color:black;
                }
                .video-js{
                    background-color:transparent;
                }
                .vjs-tech{
                    display:none;
                }
            </style>
            <?php
        }
        
        echo "<video id=\"video\" class=\"vidplayer video-js vjs-default-skin html5vid vjs-nofull\" width=\"100%\" height=\"100%\" poster=\"$poster\" controls $muted $autoplay data-setup='{\"techOrder\": [\"html5\",\"flash\"], \"plugins\": { \"videoJsResolutionSwitcher\" : { \"default\" : \"720\" } }, \"inactivityTimeout\" : 0}'>", PHP_EOL;
        foreach($video->sources as $source){
            $src = $source->src;
            $type = $source->type;
            $res = $source->res;
            $label = $res . 'p';
            echo "<source label=\"$label\" res=\"$res\" src=\"$src\" type=\"$type\" >", PHP_EOL;
        }
        echo "Your browser does not support the video tag";
                
        echo "</video>";
        
        ?>
        <script>
            videojs('#video'); 
            var video = document.getElementById('video_html5_api');
            if (video.addEventListener) {
                video.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                }, false);
            } else {
                video.attachEvent('oncontextmenu', function() {
                    window.event.returnValue = false;
                });
            }
        </script>
        <style>
            .vjs-nofull .vjs-fullscreen-control {
                display:none;
            }
        </style>
        <?php
        
        //echo '<script>videojs(\'#video\').videoJsResolutionSwitcher</script>';
        
        $video->source =  ob_get_contents();
        ob_end_clean();
        return $video;
    }
    
    public static function ProcessRadioData($params){
        $server_type = $params['server_type'];
        $nowplaying_url = $params['nowplaying_url'];
        
        $info = [];
        
        switch($server_type){
            case 'icecast':
                $stringdata = file_get_contents($nowplaying_url);
                
                $info['raw'] = json_decode($string);
                $stats = $info['raw']->icestats;
                
                if(is_array($stats->source)){
                    $sourceinfo = $stats->source[0];
                }else{
                    $sourceinfo = $stats->source;
                }
                
                $info['title'] = $sourceinfo->title;
                $info['description'] = $sourceinfo->server_description;
                $info['bitrate'] = $sourceinfo->audio_info;
                $info['genre'] = $sourceinfo->genre;
                
                break;
            case 'shoutcast':
                $opts = ['http' => ['method' => 'GET', 'header' => 'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.11) Gecko/2009060215 Firefox/3.0.11 (.NET CLR 3.5.30729)']];
                $context = stream_context_create($opts);
                
                $response = file_get_contents($nowplaying_url,false,$context);
                
                $start = stripos($response, "<body");
                $end = stripos($response, "</body");
                $body = substr($response,$start,$end-$start);
                $items = explode(',',$body);
                $info['title'] = end($items);
                $info['raw'] = $items;
                break;
        }
        
        
        return $info;
        
    }
}


//API players

class youtube extends mediaPlayer{
    
    public $name = 'youtube';
    public $title = "YouTube";
    
    public $live = true;
    public $ondemand = true;
    
    public static function build($video){
        $primarySource = $video->sources[0];
        $id = $primarySource->src;
        
        //$hash = unserialize(file_get_contents("https://gdata.youtube.com/feeds/api/videos/$id?v=2"));
        
        $autoplay = ($_GET['autoplay'])? '?autoplay=1' : '';

        $video->source = "<iframe frameborder=\"0\" class=\"vidplayer\" width=\"100%\" height=\"100%\" allowfullscreen src=\"https://www.youtube.com/embed/$id$autoplay\"></iframe>";
        $video->poster = "http://img.youtube.com/vi/$id/maxresdefault.jpg";
        return $video;
    }
    
}

class vimeo extends mediaPlayer{
    public $name = 'vimeo';
    public $title = 'Vimeo';
    
    public $live = false;
    public $ondemand = true;
    
    public static function build($video){
        $primarySource = $video->sources[0];
        $id = $primarySource->src;

        $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));

        $poster_url = $hash[0]['thumbnail_large'];          
        
        $autoplay = ($_GET['autoplay'])? '?autoplay=1' : '';
        
        $video->title = $hash[0]['title'];
        $video->description = $hash[0]['description'];
        $video->source = "<iframe frameborder=\"0\" class=\"vidplayer\" width=\"100%\" height=\"100%\" allowfullscreen src=\"https://player.vimeo.com/video/$id$autoplay\"></iframe>";
        $video->poster = $poster_url;
        return $video;
    }
}

class iframe extends mediaPlayer{
    
    public $name = 'iframe';
    public $title = 'IFrame Embed';
    
    public $live = true;
    public $ondemand = true;
    
    public static function build($video){
        $primarySource = $video->sources[0];
        $src = $primarySource->src;
        
        $video->source = "<iframe frameborder=\"0\" class=\"vidplayer\" width=\"100%\" height=\"100%\" allowfullscreen src=\"$src\"></iframe>";
        return $video;
    }
}


class soundcloud extends mediaPlayer{
    public $name = 'soundcloud';
    public $title = 'SoundCloud';
    
    public $live = false;
    public $ondemand = true;
    
    public static function build($video){
        $primarySource = $video->sources[0];
        $id = $primarySource->src;
        $autoplay = ($_GET['autoplay'])? '&auto_play=true' : '';
        
        $video->source = "<iframe frameborder=\"0\" class=\"vidplayer\" width=\"100%\" height=\"100%\" allowfullscreen src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/$id&hide_related=false&show_comments=true&show_user=true&show_reposts=false&visual=true$autoplay\"></iframe>";;
        
        $video->alt_url = "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/$id&hide_related=false&show_comments=true&show_user=true&show_reposts=false&visual=false$autoplay";
        return $video;
    }
    
}
