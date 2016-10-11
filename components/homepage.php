<?php


class homepage extends Controller{
    
    public static $name = "home";
    public static $title = "Home";
    
    public function TaskNames() {
        return ["Home"];
    }
    
    public function APIMethod(){
        return ["Home"];
    }
}