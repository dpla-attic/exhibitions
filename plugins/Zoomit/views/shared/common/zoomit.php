<?php
$unique_id = "zoomit_".hash("md4", $images[0]->getWebPath('original'))
?>
<script type="text/javascript">
(function ($) {

    var zoomitParent = jQuery('#<?=$unique_id?>')
        

    //zoomitParent
})(jQuery);
</script>

<div class="zoomit" id="<?=$unique_id?>" data-api-url="<?php echo ZoomitPlugin::API_URL; ?>">
    <h2><?php echo __('Image Viewer'); ?></h2>
    <p><?php echo __('Click below to view an image using the %sZoom.it%s viewer.', '<a href="http://zoom.it/">', '</a>'); ?></p>
    <ul>
        <?php foreach($images as $image): ?>
        <li><a href="<?php echo html_escape($image->getWebPath('original')); ?>" class="zoomit_images"><?php echo html_escape($image->original_filename); ?></a></li>
        <?php endforeach; ?>
    </ul>
    <div class="zoomit_viewer"></div>
</div>
