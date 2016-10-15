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
        <p>All collections</p>
        <ul class="collections">
        <?php 
        
        ?>
        </ul>
    </div>
</div>
<?php
    }
}