<?php


class homepage extends Controller{
    
    public static $name = "home";
    public static $title = "Home";
    
    public function PrintBreadcrumbs() {
        echo "<a>Home</a>";
    }
    
    public function APIMethod(){
        return ["Home"];
    }
    
    public function UIMethod() {
        global $config;
        ?>
<div class="content-box">
    <h4><?= $config['welcome_message'] ?></h4>
    <div class="pages">
        <p>Click a page on the left to get started</p>
        <h5>Looking for help?</h5>
        <p>Read the documentation <a href="https://github.com/camerontsmall/pamphlet_nosql/tree/master/docs">here.</a></p>
    </div>
</div>
<?php
    }
}