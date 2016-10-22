<link rel="stylesheet" href="res/channel/css/studio.css" />
<script src="res/channel/js/studio.js"></script>

<div class="row" id="studio-container">
    <div class="column small-12 medium-12 large-6" id="preview-pane">
        <h4 class="studio-title title-preview">Preview</h4>
         <div class="video-preview-container">
            <iframe class="video-preview iframe-preview" src="./generated.php?a=video/<?= $input_data->video_id ?>&autoplay=1"></iframe>
        </div>
        <div class="video-title" id="content-title-preview"></div>
    </div>
    <div class="column small-12 medium-12 large-6" id="program-pane">
        <h4 class="studio-title title-program">Program</h4>
        <div class="video-preview-container">
            <iframe class="video-preview iframe-program" src="./generated.php?a=channel/<?= $input_data->_id ?>&autoplay=1"></iframe>
        </div>
        <div class="video-title" id="content-title-program"></div>
    </div>
</div>
<div class="row column" id="control-form">
    <div class="row">
        
    </div>
</div>


<script>
    var s_el = document.getElementById("studio-container");
    
    //var studio = PamphletStudio(s_el);
</script>