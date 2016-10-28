<?php

class category_implementation extends Implementation{
    
    public $model_name = "category";
    public $collection_name = "category";
 
    /**
     * Add category dropdown to form
     * 
     * To add to controller, insert the line
     * $model = category_implementation::ModelAddCategories($model);
     * into your PrepareModel function.
     * 
     * @param type $model
     * @return type
     */
    static function ModelAddCategories($model){
        
        $imp = new category_implementation();
        
        $categories = $imp->ReadMany();
        
        $model['properties']['category'] = ["type" => "string", "title" => "Category", "propertyOrder" => 0];
        
        $model['properties']['category']['enum'][] = "";
        $model['properties']['category']['options']['enum_titles'][] = "--";
        
        foreach($categories as $cat){
            $model['properties']['category']['enum'][] = $cat->_id;
            $model['properties']['category']['options']['enum_titles'][] = $cat->title;
        }
        
        return $model;
        
    }
    
    
    static function kpCategories(){
        $imp = new category_implementation();
        $data = $imp->ReadMany();
        
        $kp = [];
        
        foreach($data as $item){
            $kp[$item->_id] = $item->title;
        }
        
        return $kp;
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
