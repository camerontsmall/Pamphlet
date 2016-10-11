var convert = function(convert){
    return $("<span />", { html: convert }).text();
};

        
function list_get_data(dataLocation){
        var string = document.getElementById(dataLocation).innerHTML;
        return JSON.parse(string);
}

function rowCode(row){
    var html = '';
    if(row['action']){
        var action = "?action=" + row['action'];
    }else{
        var action = false;
    }
    if(row['onclick']){
        html += '<tr onclick="' + row['onclick'] + '">';
    }else{
        html += '<tr>';
    }
    for(var key in row){
        if(key != 'onclick' && key != 'action'){
            if(action){
                html += '<td><a href="' + action + '">' + row[key] + "</a></td>";
            }else{    
                html += '<td><a>' + row[key] + "</a></td>";
            }
        }
    }
    html += "</tr>";
    return html;
}
        
function list_change_page(listId,dataLocation,pageNumber){
        var data = list_get_data(dataLocation);
        var html = "";
        
        var numberOfPages = Math.ceil((data.length)/10);
        
        var next = pageNumber + 1;
        var prev = pageNumber - 1;
        
        var offset = 10 * (pageNumber);
        
        for(var i=offset; i < offset + 10; i++){
            var row = data[i];
            if(row){
                html += rowCode(row);
            }   
        }
        
        document.getElementById(listId + '_body').innerHTML = html;
        document.getElementById(listId + '_pagenumber').innerHTML = pageNumber + 1;
        
        //Load buttons
        var backbtn = document.getElementById(listId + '_back');
        var nextbtn = document.getElementById(listId + '_next');
        //Change button targets
        
        
        //Change button visibility depending on list length
        if(pageNumber == 0){
            backbtn.setAttribute('onclick','javascript:void(0);');
            backbtn.style.color = '#888';
        }else{
            backbtn.setAttribute('onclick','list_change_page(\'' + listId + '\',\'' + dataLocation + '\',' + prev + ');' );
            backbtn.style.color = 'inherit';
        }
        if(pageNumber >= (numberOfPages -1) ){
            nextbtn.setAttribute('onclick','javascript:void(0);');
            nextbtn.style.color = '#888';
        }else{
            nextbtn.setAttribute('onclick','list_change_page(\'' + listId + '\',\'' + dataLocation + '\',' + next + ');' );
            nextbtn.style.color = 'inherit';
        }
        
}
        
function list_search(listId,dataLocation,term){
        
        if(term.length == 0){
            list_change_page(listId,dataLocation,0);
            return;
        }
        
        var data = list_get_data(dataLocation);
        
        var html = "";

        for(var i=0; i < data.length; i++){
            var row = data[i];
            var match = false;

            for(var key in row){
                if(key != 'onclick'){
                    if(row[key]){
                        var content = row[key].toLowerCase();
                        if(content.indexOf(term.toLowerCase()) != -1){
                           match = true;
                        }
                    }
                }
            }
            if(match == true){
                var row = data[i];
                if(row){
                    html += rowCode(row);
                }   
                
            }
        }
        
        document.getElementById(listId + '_body').innerHTML = html;
        //Load buttons
        var backbtn = document.getElementById(listId + '_back');
        var nextbtn = document.getElementById(listId + '_next');
        backbtn.style.color = '#888';
        nextbtn.style.color = '#888';
}
        
function list_all(listId, dataLocation){ 

        var data = list_get_data(dataLocation);
        
        console.log(data);
        var html = "";
        
        
        for(var i=0; i < data.length; i++){
            var row = data[i];
            if(row){
                html += rowCode(row);
            }   
        }
        
        document.getElementById(listId + '_body').innerHTML = html;
}

