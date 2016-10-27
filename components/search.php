<?php

/* Search functionality */

class search extends View{
    
    public static $api_endpoint = "search";
    
    public function __construct($task) {
        global $config;
        
        $this->task = $task;
        $this->task_parts = explode('/',$task);
        
        $this->searchable = $config['searchable_collections'];
        
    }
    
    function ApiMethod(){
        
        $tp = $this->task_parts;
        
        if(!isset($_GET['q'])){ 
            return ["Search API Options (GET parameters)" => [
                'q' => "(String) Search term [Required]", 
                "l" => "(Int) Limit results", 
                "o" => "(Int) Offset results", 
                "d" => "(Array[String]) Collections to search",
                "c" => "(String) Restrict search results to those matching one category"
                ], "Define which collections are searchable in config.php"];
        }
        
        $q = $_GET['q'];
        
        $data = ["generated" => time(), "search_term" => $q];
        
        $options = [];
        
        if(isset($_GET['l'])){
            $limit = (integer) $_GET['l'];
            $options['limit'] = $limit;
            $data['limit'] = $limit;
        }
        if(isset($_GET['o'])){
            $offset = (integer) $_GET['o'];
            $options['skip'] = $offset;
            $data['offset'] = $offset;
        }
        
        $filter = ['$or' => [["title" => ['$regex' => ".$q."]], ["tags" => ['$regex' => ".$q."]], ["description" => ['$regex' => ".$q."]], ["content" => ['$regex' => ".$q."]]]];

        if(isset($_GET['c'])){
            $category = $_GET['c'];
            
            
            $c_imp = new category_implementation;
            $all_cats = $c_imp->ReadMany();
            
            //Translate title to ID
            foreach($all_cats as $cat){
                if(strtolower($category) == strtolower($cat->title)){
                    $category = $cat->_id;
                }
            }
            
            $filter = ['$and' => [$filter, ['category' => $category]]];
            $data['category'] = $category;
        }
        
        //return $filter;
        $results = [];
        $num_results = 0;
        
        foreach($this->searchable as $col_name){
            
            
            $v_cname = View::loadViewByEndpoint($col_name);
            if(class_exists($v_cname)){
                
                $i_imp = new $v_cname();
                
                $col_results = $i_imp->implementation->ReadMany($filter,$options);
                $num_results += count($col_results);
                $results[$col_name] = $col_results;
                
            }
            
        }
        
        $data['number_of_results'] = $num_results;
        $data['results'] = $results;
        return $data;
        
        
    }
    
    
}