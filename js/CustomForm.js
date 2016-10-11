function cm_updateForm(fields,action,method,result,onReloadAction){
    if(!onReloadAction){
        var onReloadAction = action;
    }
    var updateRequest = new XMLHttpRequest();
    updateRequest.onreadystatechange = function(){
        if(updateRequest.readyState == 4 && updateRequest.status == 200){
            var response = updateRequest.responseText;
            if(response == "reload"){
                window.location.href = '.?action=' + onReloadAction;
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
    var postRequest = "";
            
    for(var i = 0; i < fields.length; i++){
        value = $('#' + fields[i]).val();
        if(i == 0){
            postRequest = fields[i] + "=" + encodeURIComponent(value);
        }else{
            postRequest = postRequest + "&" + fields[i] + "=" + encodeURIComponent(value);
        }
    }   
            
    console.log(postRequest);
    updateRequest.open(method,"request.php?update&action=" + action,"true");
    updateRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    updateRequest.send(postRequest);
}
function expand(id){
    var thing = document.getElementById(id);
    if(thing.style.maxHeight != "500px"){
        thing.style.maxHeight = "500px";
    }else{
        thing.style.maxHeight = "0px";
    }
}

