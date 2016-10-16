<?php

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
        
        return $model;
    }
    
    function PrepareData($data) {
        $c = [];
        foreach($data as $item){
            $c[] = ["action" => "blog/$item->_id", "Title" => $item->title, "Tags" => $item->tags, "Date" => $item->date];
        }
        return $c;
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
    
}
