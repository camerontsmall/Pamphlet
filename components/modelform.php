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
     * @param jsfunction $optional_function - JavaScript function to execute on form submit
     */
    public function __construct($model,$id, $action, $method, $onReloadTask, $optional_function){
        $this->model = $model;
        $this->id = $id;
        $this->http_method = $method;
        $this->api_action = $action;
        $this->reload_task = $onReloadTask;
        $this->optional_function = $optional_function;
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
    <span id="<?= $this->id ?>_result">No changes detected</span>
    <button class="button tiny"
        onclick="submitForm(editor.getValue(),'<?= $this->api_action ?>','<?= $this->http_method ?>','<?= $this->id ?>_result', '<?= $this->reload_task ?>',function(){<?= $this->optional_function ?>});"
        value="submit"
        >Save
    </button>
    <?php if($this->http_method == 'PUT'){ ?>
    <button class="button tiny"
        onclick="if(confirm('Are you sure you want to delete this?')){submitForm(null,'<?= $this->api_action ?>','DELETE','<?= $this->id ?>_result', '<?= $this->reload_task ?>');}"
        value="submit">
        Delete
    </button>
    <?php } ?>
</div>
<script>
    JSONEditor.plugins.selectize.enable = true;
    
    var jeditor_el = document.getElementById('<?= $this->id ?>_jsoneditor');
    
    var editor = new JSONEditor(jeditor_el, {
        schema : <?= json_encode($this->model) ?>,
        disable_properties: true,
        disable_collapse: true,
        disable_edit_json: false,
        no_additional_properties : false,
        theme: "foundation6",
        template : "handlebars"
    });
    
    editor.on('change', function(){
       $('#<?= $this->id ?>_result').html('Not saved');
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

