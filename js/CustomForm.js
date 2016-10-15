function updateForm(data,action,method,result,onReloadTask){
    if(!onReloadAction){
        var onReloadAction = action;
    }
    var updateRequest = new XMLHttpRequest();
    updateRequest.onreadystatechange = function(){
        if(updateRequest.readyState == 4 && updateRequest.status == 200){
            var response = updateRequest.responseText;
            if(response == "reload"){
                window.location.href = '.?a=' + onReloadAction;
            }else if(response == "refresh"){
                location.reload();
            }
            else{
                var resultBox = document.getElementById(result);
                
                if(resultBox.tagName == "INPUT"){
                    resultBox.value = response;
                }else{
                    //alert(resultBox.tagName);
                    resultBox.innerHTML = response;
                }
            }
        }else if(updateRequest.status == 500){
            document.getElementById(result).innerHTML = "Error 500: Saving failed";
        }
    }
    var postRequest = "data=" + JSON.stringify(data);
            
    console.log(postRequest);
    updateRequest.open(method,"./api_local.php?a=" + action,"true");
    updateRequest.setRequestHeader("Content-type","application/json");
    updateRequest.send(postRequest);
}

function submitForm(data,action,method,result_field,reload_task){
    
    var kvp_data = "data=" + JSON.stringify(data);
    
    $.ajax({
        url : './api_local.php?a=' + action + '&debug',
        method : method,
        data : kvp_data,
        success : function(data){
            console.log(data);
        }
    });
    
}

function expand(id){
    var thing = document.getElementById(id);
    if(thing.style.maxHeight != "500px"){
        thing.style.maxHeight = "500px";
    }else{
        thing.style.maxHeight = "0px";
    }
}

