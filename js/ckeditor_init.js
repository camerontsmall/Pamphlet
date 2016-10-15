//Initialize CKEditor and link it to json-editor

var j = 0;
$("textarea[data-schemaformat='html']").each(function(){
    $(this).attr('id','textarea_' + j);
    j++;
    var editor = CKEDITOR.replace($(this).attr('id'));
    editor.on('change', function(event){
        //write ckeditor value to textarea value
        this.updateElement();
        //write value to dom - apparently not necessary
        //this.element.$.innerHTML = this.element.$.value;
        //create new event
        var event = new Event('change');
        //tell json editor the value has changed
        this.element.$.dispatchEvent(event);
    });
});

