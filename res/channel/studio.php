<link rel="stylesheet" href="res/channel/css/studio.css" />
<script src="res/channel/js/studio.js"></script>
<script id="studio-template" type="application/handlebars"><?php include('res/channel/js/studio.handlebars'); ?></script>
<script id="meta-template" type="application/handlebars"><?php include('res/channel/js/meta.handlebars'); ?></script>

<div id="studio-parent">

    <div id="studio-container">
        <p>Loading content, please wait</p>
    </div>

    <div class="row">
        <div id="meta-preview" class="column small-12 large-6 meta-section">
            Loading metadata...
        </div>
        <div id="meta-program" class="column small-12 large-6 meta-section">
            Loading metadata...
        </div>
    </div>
    
</div>

<script>
    
    var studio = new PamphletStudio('<?= $channel_id ?>');
    
    studio.update();
    
    var interval = setInterval(function(){ studio.update(); }, 1000);
</script>