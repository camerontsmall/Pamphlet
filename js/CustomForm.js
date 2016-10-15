function submitForm(data,action,method,result_field,reload_task,optional_function){
    
    var kvp_data = "data=" + JSON.stringify(data);
    console.log("Starting API request");
    
    $.ajax({
        url : './api_local.php?a=' + action,
        method : method,
        data : kvp_data,
        success : function(data){
            console.log("API Request Response Received");
            console.log(data);
            if(data['editor_action'] == 'reload'){
                window.location.href = './?a=' + reload_task;
            }else{
                $('#' + result_field).html(data['editor_status']);
            }
            optional_function();
        }
    });
    
}

