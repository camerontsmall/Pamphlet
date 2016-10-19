/* auto_refresh.js 
 * 
 * Script to automatically reload channel content
 * when a change is detected from the API
 *
 */

var api_url = "./api_public.php?a=channel/" + channel_id + '&fast';

var timer = window.setInterval(function(){
    
    $.ajax({
            url : api_url,
            method : 'GET',
            success : function(data){
                if(data['content_id'] != content_id){
                    console.log("Content changed");
                    window.location.reload();
                }
            }
    });

},5000);