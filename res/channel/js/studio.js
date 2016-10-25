/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$('#main-menu').removeClass("reveal-for-large");
$('#launch-button').removeClass("hide-for-large");


function PamphletStudio(channel_id){
    
    this.id = channel_id; 
    
    //Load channel preview element and template
    this.container = document.getElementById('studio-container');
   
    var template_source = $('#studio-template').html()
    this.template = Handlebars.compile(template_source);
    
    //Load channel meta 
    this.pvw_meta_container = document.getElementById('meta-preview');
    this.pgm_meta_container = document.getElementById('meta-program');
   
    var meta_template_source = $('#meta-template').html()
    this.meta_template = Handlebars.compile(meta_template_source);
   
    this.data = null;

    this.update = function(){
        this.updateVideoPreview();
        this.updatePreviewMeta();
        this.updateProgramMeta();
    };
   
   this.updateVideoPreview = function(){
        var url = 'api_local.php?a=channel/' + this.id;
       var self = this;
       
       $.ajax({
           url:url,
           method: 'get',
           accept: 'application/json',
           success: function(data){
               self.data = data;
               
               var html = self.template(data);
               //self.container.innerHTML = html;
               
               var dd = new diffDOM();
               
               var temp = document.createElement('div');
               temp.setAttribute('id','studio-container');
               temp.innerHTML = html;
               
               var diff = dd.diff(self.container, temp);
               dd.apply(self.container,diff);
               
               console.log('Updated data');
           }
       });
   };
   
   this.toggleLiveState = function(){
       
       var new_value = !(this.data.on_air);
       
       var send = { "on_air" : new_value };
       var url = 'api_local.php?a=channel/' +  this.id;
       
       $.ajax({
          url: url,
          data: 'data=' + JSON.stringify(send),
          method: 'PUT',
          success: function(response){
              console.log(response);
          }
       });
       
   };
   
   this.updatePreviewMeta = function(){
        if(this.data){
            var url = 'api_public.php?a=video/' + this.data.video_id;
            var self = this;

            $.ajax({
                url:url,
                method: 'get',
                accept: 'application/json',
                success: function(data){

                    self.pvw_meta_container.innerHTML = self.meta_template(data);
                    //self.container.innerHTML = html;
                }
            });
        }
   };
   
   this.updateProgramMeta = function(){
        var url = 'api_public.php?a=channel/' + this.id;
        var self = this;

        $.ajax({
            url:url,
            method: 'get',
            accept: 'application/json',
            success: function(data){
                data = data.content;
                self.pgm_meta_container.innerHTML = self.meta_template(data);
                //self.container.innerHTML = html;
            }
        });
   };
   
}