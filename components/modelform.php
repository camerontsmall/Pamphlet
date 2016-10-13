<?php

class ModelForm{
    
    public $model;
    public $id;
    public $http_method;
    public $api_action;
    
    private $dom_data_ids;
    
    public $title;
    private $reload_action;
    
    
    /**
     * 
     * @param array $optionsArray
     * @param string $id - ID for the form. MUST NOT contain underscores
     * @param string $action - URL parameters
     * @param string $method - PUT or POST
     * @param string $onReloadAction - page to return to if reload request is received
     * 
     */
    public function __construct($model,$id, $action, $method, $onReloadAction){
        $this->model = $model;
        $this->id = $id;
        $this->http_method = $method;
        $this->api_action = $action;
        $this->reload_action = $onReloadAction;
    }
    
    public function import_object($data){
        $this->data = $data;
    }
    
    public function render(){
        echo PHP_EOL, "<!-- ajaxForm2 $this->id starts -->", PHP_EOL;
        echo "<div id='$this->id' class='form'>", PHP_EOL;   
        ?>
<script>
    var jeditor_el = document.getElementById('<?= $this->id ?>');
    
    var editor = new JSONEditor(jeditor_el, {
        schema : <?= json_encode($this->model) ?>,
        disable_properties: false,
        disable_collapse: true,
        disable_edit_json: false,
        no_additional_properties : false,
        theme: "jqueryui",
        template : "handlebars"
    });
    
    <?php if($this->data){ echo "editor.setValue(" . json_encode($this->data) . ");" ;}?>
    
</script>
<?php
        
        echo "</div>";
    }
    
    public function string($name,$value,$label){
        
    }
    
    public function html($name, $value, $label){
        
    }
    
    public function number($name, $value, $label){
        
    }
    
}

