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
    public function __construct($model,$id, $action, $method, $onReloadTask){
        $this->model = $model;
        $this->id = $id;
        $this->http_method = $method;
        $this->api_action = $action;
        $this->reload_task = $onReloadTask;
    }
    
    public function import_object($data){
        $this->data = $data;
    }
    
    public function render(){
        echo PHP_EOL, "<!-- ajaxForm2 $this->id starts -->", PHP_EOL;
        echo "<div id='$this->id' class='form'>", PHP_EOL;
        echo "<div id=\"{$this->id}_jsoneditor\" class=\"jsoneditor\"></div>", PHP_EOL;
        
        ?>
<div class="control-row">
    <span id="<?= $this->id ?>_result">Editing</span>
    <button
        onclick="submitForm(editor.getValue(),'<?= $this->api_action ?>','<?= $this->http_method ?>','<?= $this->id ?>_result', '<?= $this->reload_task ?>')"
        value="submit"
        >Save
    </button>
    <?php if($this->http_method == 'PUT'){ ?>
    <button
        onclick="submitForm(null,'<?= $this->api_action ?>','DELETE','<?= $this->id ?>_result', '<?= $this->reload_task ?>')"
        value="submit">
        Delete
    </button>
    <?php } ?>
</div>
<script>
    var jeditor_el = document.getElementById('<?= $this->id ?>_jsoneditor');
    
    var editor = new JSONEditor(jeditor_el, {
        schema : <?= json_encode($this->model) ?>,
        disable_properties: true,
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

