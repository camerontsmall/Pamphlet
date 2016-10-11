<?php

class ModelForm{
    
    private $optionsArray = array();
    public $id; /* ID for each individual form */
    public $method; /* Method (GET or POST) */
    private $optionsIDs = array();
    public $title;
    public $name = 'customForm';
    private $onReloadAction;
    
    /**
     * 
     * @param array $optionsArray
     * @param string $id - ID for the form. MUST NOT contain underscores
     * @param string $action - URL parameters
     * @param string $method - GET or POST
     * @param string $onReloadAction - page to return to if reload request is received
     * 
     */
    public function __construct($optionsArray,$id, $action, $method, $onReloadAction){
        $this->optionsArray = $optionsArray;
        $this->id = $id;
        $this->method = $method;
        $this->action = $action;
        $this->onReloadAction = $onReloadAction;
    }
    
    public function setTitle($title){
        $this->title = $title;
    }
    
    public function build($submitLabel){
        //Start form elements
        echo PHP_EOL, "<!-- ajaxForm2 $this->id starts -->", PHP_EOL;
        echo "<div id='$this->id' class='form'>", PHP_EOL;

        //Echo title if it is set
        if($this->title){
            echo "<div class='formtitle'>$this->title</div>", PHP_EOL;
        }
        
        //Print each element in the option array
        foreach($this->optionsArray as $optionId => $option){
            /* */
            $fullId = $this->id . '_' . $optionId;
            if($option['type'] == 'button'){
                self::button($optionId,$option['action'],$option['label'],$this->onReloadAction,$this->id);
            }else if($option['type'] == 'index'){
                
            }else if($option['type'] == 'readonly'){
                 $this->printOption($fullId,$option);
            }else{
                 $this->optionsIDs[] = $fullId;
                 $this->printOption($fullId, $option);
            }
            
        }
        
        $fields = "[";
        foreach($this->optionsIDs as $key => $field){
            if($key != 0){ $fields .= ","; }
            $fields .= "'$field'";
        }
        $fields .= "]";
        $idArray = explode("=", $this->action);
        $lastId = end($idArray);
        echo "<div class=\"fieldRow\">";
        echo "<p class=\"response\" id=\"$this->id-response\"></p>";
        echo "<button title=\"Item id: $lastId\"onclick=\"cm_updateForm($fields,'$this->action','POST','$this->id-response','$this->onReloadAction');\">$submitLabel</button>", \PHP_EOL;
        echo "</div>";
        echo "</div>", PHP_EOL;
        echo "<!-- ajaxForm2 $this->id ends -->", PHP_EOL;
    }
    
    public function printOption($id,$option){
        $type = $option['type'];
        $label = $option['label'];
        $value = $option['value'];
        if(!$label){
            $label = $id;
        }
        
        switch($type){
            case 'select':
            case 'datalist':
            case 'multiselect':
            case 'customlist':
                $options = $option['options'];
                //echo count($options);
                self::$type($id, $value, $options, $label);
                break;
            default:
                if(method_exists($this,$type)){
                    self::$type($id,$value,$label);
                    
                } else{
                    self::input($id, $value, $type, $label);
                }
                break;
        }
        
    }
    
    /**
     * Convert an array without form names to a usable form array
     * 
     * @param type $optionsArray
     * @param type $result
     * @return type
     */
    public static function getEditForm($optionsArray,$plainarray){
        //Set value for each element in optionsarray
        foreach($optionsArray as $key=>$option){
            if(isset($plainarray[$key])){ 
                $optionsArray[$key]['value'] = $plainarray[$key];
            }
        }
        
        return $optionsArray;
    }
    /**
     * Convert array of key-pair values (eg. the $_POST array)
     * into a usable optionsArray ensuring compatibility with the
     * corresponding SQL tables
     * 
     * @param array $optionsArray
     * @param array $result
     * @return array
     */
    public static function decodeResult($optionsArray,$result){
        //Pop form ID off each element of the POST request
        $plainIDs = array();
        foreach($result as $key=>$value){
            $array = explode('_', $key);
            unset($array[0]);
            $key = implode('_', $array);
            $plainIDs[$key] = $value;
        }
        //Set value for each element in optionsarray
        foreach($optionsArray as $key=>$option){
            $optionsArray[$key]['value'] = $plainIDs[$key];
        }
        
        return $optionsArray;
    }
    
    public static function simpleArray($optionsArray, $result){
        $results = self::decodeResult($optionsArray,$result);
        $newresult = array();
        foreach($results as $key => $array){
            $newresult[$key] = $array['value'];
        }
        return $newresult;
    }

    public static function insertSQL($optionsArray,$result, $tablename){
        global $connection;
        
        $results = self::decodeResult($optionsArray,$result);
        $query = "INSERT INTO $tablename SET ";
        $first = true;
        foreach($results as $key => $array){
            if(!$first){ $query .= ','; }
            $value = $connection->real_escape_string($array['value']);
            $query .= "$key='$value'";
            $first = false;
        }
        $query .= ';';
        return $query;
    }
    
    public static function updateSQL($optionsArray, $result, $tablename, $condition){
        global $connection;
        
        $results = self::decodeResult($optionsArray,$result);
        $query = "UPDATE $tablename SET ";
        $first = true;
        foreach($results as $key => $array){
            if(!$first){ $query .= ', '; }
            $value = $connection->real_escape_string($array['value']);
            $query .= "$key='$value'";
            $first = false;
        }
        $query .= " WHERE $condition;";
        return $query;
    }
    
    public static function fetchOneResult($optionsArray,$tablename, $id){
        //Fetch one result from the database and put values in an optionsArray
    }
    
    public static function button($id, $action, $label, $onReloadAction, $formID){
        echo "<div class=\"fieldRow\">";
        echo "<button id=\"$id\" onclick=\"if(confirm('$label?')){ cm_updateForm([],'$action','GET','$formID-response','$onReloadAction');};\">$label</button>";
        echo "</div>", PHP_EOL;
    }
    
    public static function text($id, $value, $label){
        echo "<div class=\"fieldRow\"><p>$label</p><input type=\"text\" id=\"$id\" name=\"$id\" value=\"$value\" placeholder=\"$label\"/></div>", \PHP_EOL;
    }
    
    public static function input($id, $value, $type, $label){
        echo "<div class=\"fieldRow\"><p>$label</p><input type=\"$type\" id=\"$id\" name=\"$id\" value=\"$value\" placeholder=\"$label\"/></div>", \PHP_EOL;
    }
    
    public static function hidden($id, $value, $type, $label){
        echo "<div class=\"fieldRow\" style=\"display:none;\"><p>$label</p><input type=\"$type\" id=\"$id\" name=\"$id\" value=\"$value\" placeholder=\"$label\"/></div>", \PHP_EOL;
    }
    
    function file($id, $value, $label){
          //Input with an upload button
        echo "<div class=\"fieldRow\" id=\"filePath\"><p>$label</p>";
        echo "<input id=\"$id\" type=\"text\" value=\"$value\" placeholder=\"$label\">";
        echo "<button onclick=\"choose_file('$id');\">Upload</button>";
        echo "</div>";
    }
    
    public static function richtext($id, $value, $label){
       //textarea with CKEditor - one per page as this takes over the loadScript
        //TODO - upgrade loadScript for multiple elements
        echo "<div class=\"fieldRow\"><p>$label</p></div>", \PHP_EOL;
        echo "<textarea class=\"richtext\" id=\"$id\" id=\"$id\" rows=\"5\">";
        echo $value;
        echo "</textarea>";
        $editor = $id . "editor";
        echo <<<END
        <script id="loadScript">
            var $editor = CKEDITOR.replace('$id');
            $editor.on( 'change', function( evt ) {
                $editor.updateElement();
            });
            $editor.on( 'loaded', function(evt){
                    $('.cke').css('border','none');
                    $('.cke').css('box-shadow','none');
                    $('.cke_bottom').css('background-color','#2196F3');
                });
                //CKFinder Setup (TODO: replace with free alternative)
            CKFinder.setupCKEditor($editor, '/cms/ckfinder/');
        </script>
END;
    }
    
    
    
    public static function select($id, $value, $options, $label){
         //Select field with key-pair elements ("value"=>"label")
        echo "<div class=\"fieldRow\"><p>$label</p>", PHP_EOL;
        echo "<select id=\"$id\" name=\"$id\">", PHP_EOL;
        foreach($options as $key => $choice){
                if($key == $value){
                echo "<option value=\"$key\" selected>$choice</option>", PHP_EOL;
            }else{
                echo "<option value=\"$key\">$choice</option>", PHP_EOL;
            }
        }
        echo "</select></div>", PHP_EOL;
    }
    
    public static function checkBox($id,$value,$label){
       $checked = ($value != false)? "checked":"";
       echo "<div class=\"fieldRow checkbox\"><p>$label</p><input id=\"$id\" name=\"$id\" type=\"checkbox\" value=\"$value\" $checked onclick=\"$(this).val(this.checked ? 1 : 0)\"><label for=\"$id\"><span></span></div>", PHP_EOL;
    }
    
    public static function datalist($id, $value, $options, $label){
        $list_id = $id . '_list';
        echo "<div class=\"fieldRow\"><p>$label</p><input type=\"text\" id=\"$id\" name=\"$id\" value=\"$value\" list=\"$list_id\" placeholder=\"$label\"/>", \PHP_EOL;
        echo "<datalist id=\"$list_id\">", PHP_EOL;
        foreach($options as $option){
            echo "<option value=\"$option\">", PHP_EOL;
        }
        echo "</datalist>", PHP_EOL;
        echo "</div>", PHP_EOL;
    }
    
    public static function customlist($id, $value, $options, $label){
         echo "<div class=\"fieldRow\" style=\"height:auto\"><p>$label</p>", PHP_EOL;
        echo "<select style=\"height:auto;\" id=\"$id\" name=\"$id\" value=\"$value\" >", PHP_EOL;
        if(count($options > 0)){
            foreach($options as $key => $option){
                echo "<option value=\"$key\">$option</option>", PHP_EOL;
            }
        }
        echo "</select>", PHP_EOL;
        echo "</div>";
    }
    
    public static function multiselect($id, $value, $options, $label){
        $values = explode(',',$value);
        echo "<div class=\"fieldRow\" style=\"height:auto\"><p>$label</p>", PHP_EOL;
        echo "<select class=\"multiselect\" multiple id=\"$id\" name=\"$id\" value=\"$value\" >", PHP_EOL;
        if(count($options > 0)){
            foreach($options as $key => $option){
                if(in_array($key, $values)){
                    echo "<option value=\"$key\" selected>$option</option>", PHP_EOL;
                }else{
                    echo "<option value=\"$key\">$option</option>", PHP_EOL;
                }
            }
        }
        echo "</select>", PHP_EOL;
        echo "</div>";
    }
    
    public static function readonly($id,$value,$label){
        echo "<div class=\"fieldRow\" ><p>$label</p><input id=\"$id\" onClick=\"this.select();\" readonly value=\"$value\" ></div>";

    }
    
    
}
