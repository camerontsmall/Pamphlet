<?php

class category_implementation extends Implementation{
    
    public $model_name = "category";
    public $collection_name = "category";
 
    static function ModelAddCategories($model){
        
        $imp = new category_implementation();
        
        $categories = $imp->ReadMany();
        
        $model['properties']['category'] = ["type" => "string", "title" => "Category", "propertyOrder" => 0];
        
        $model['properties']['category']['enum'][] = "";
        $model['properties']['category']['options']['enum_titles'][] = "--";
        
        foreach($categories as $cat){
            $model['properties']['category']['enum'][] = $cat->title;
            $model['properties']['category']['options']['enum_titles'][] = $cat->title;
        }
        
        return $model;
        
    }
    
}

class category_controller extends Controller{
    
    public static $name = "category";
    public static $title = "Categories";

    public $implementation_name = "category_implementation";
    
    function PrepareData($data) {
        $c = [];
        foreach($data as $item){
            $c[] = ["action" => "category/$item->_id", "Title" => $item->title];
        }
        return $c;
    }
    
    
    
}

//Aggregate all content
class category_view extends View{
    
    public static $api_endpoint = "category";
    
    public $implementation_name = "category_implementation";
    
    
    
}
