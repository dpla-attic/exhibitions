<?php
$unique_id = "zoomit_".hash("md4", $images[0]->getWebPath('original'))
?>

<div class="zoomit" id="<?=$unique_id?>" data-api-url="<?php echo ZoomitPlugin::API_URL; ?>">
    <div class="zoomit_viewer">
    <?php foreach($images as $image): ?>
    	<img src="<?php echo html_escape($image->getWebPath('original'))."?param=".html_escape(Zend_Registry::get('bootstrap')->getResource('Config')->dpla->zoomit->update_cache_param); ?>" data-original="<?php echo html_escape($image->original_filename); ?>" class="zoomit-image tmp-img" alt="">
    <?php endforeach; ?>
    </div>
</div>
