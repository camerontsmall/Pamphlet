<?php

if($config['enable_users']){

class user_implementation extends Implementation{
    
    public $model_name = "user";
    public $collection_name = "users";
    
}

class user_controller extends Controller{
    
    public static $name = "user";
    public static $title = "Users";

    public $implementation_name = "user_implementation";
    
    
    function PrepareModel() {
        
        $model = $this->implementation->model;
        
        $g_imp = new group_implementation();
        
        $groups = $g_imp->ReadMany();
        
        foreach($groups as $group){
            $model['properties']['groups']['items']['enum'][] = $group->_id;
            $model['properties']['groups']['items']['options']['enum_titles'][] = $group->title;
        }
        
        $controllers = Controller::listAll();
        
        foreach($controllers as $cnt){
            $model['properties']['permissions']['items']['properties']['collection_name']['enum'][] = $cnt::$title;
            $model['properties']['permissions']['items']['properties']['collection_name']['enum_titles'][] = $cnt::$title;
        }
        
        return $model;
    }
    
    function PrepareData($data) {
        foreach($data as $key => $row){
            $data[$key] = ["Username" => $row->username, "Full name" => $row->full_name, "Email" => $row->email, "action" => "user/{$row->_id}"];
        }
        return $data;
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
            if(!$doc->title){ $doc->title = $doc->_id; }
            echo "<a href=\"./?a=$name\">$title</a><i class=\"material-icons\">chevron_right</i><a href=\"./?a=$name/$tp[1]\">{$doc->full_name}</a>";
            echo "<a class=\"bc-action\" href=\"./?a=$name/add\">New $item_name<i class=\"material-icons\">add</i></a>";
            echo "<a class=\"bc-action\" target=\"_blank\" href=\"./api_public.php?a=$name/$tp[1]\">API<i class=\"material-icons\">swap_horiz</i></a>";
        }else{
            echo "<a href=\"./?a=$name\">$title</a>";
            echo "<a class=\"bc-action\" href=\"./?a=$name/add\">New $item_name<i class=\"material-icons\">add</i></a>";
            echo "<a class=\"bc-action\" target=\"_blank\" href=\"./api_public.php?a=$name\">API<i class=\"material-icons\">swap_horiz</i></a>";
        }
    }

    
}

class user_view extends View{
    
    public static $api_endpoint = "user";
    
    public $implementation_name = "user_implementation";
    
}


class group_implementation extends Implementation{
    public $model_name = "group";
    public $collection_name = "groups";
    
}

class group_controller extends Controller{
    
    public static $name = "group";
    public static $title = "Groups";

    public $implementation_name = "group_implementation";
    
     function PrepareData($data) {
        foreach($data as $key => $row){
            $data[$key] = ["Title" => $row->title, "Description" => htmlspecialchars($row->description), "action" => "group/{$row->_id}"];
        }
        return $data;
    }
    
}

class session_implementation extends Implementation{
    public $model_name = "session";
    public $collection_name = "sessions";
}

class session_controller extends Controller{
    public static $name = "session";
    public static $title = "Sessions";
    
    public $implementation_name = "session_implementation";
}


}