<?php
$unique_id = "zoomit_".hash("md4", $images[0]->getWebPath('original'))
?>

<div class="zoomit" id="<?=$unique_id?>" data-api-url="<?php echo ZoomitPlugin::API_URL; ?>">
    <?php foreach($images as $image): ?>
    <a href="<?php echo html_escape($image->getWebPath('original'))."?param=".html_escape(Zend_Registry::get('bootstrap')->getResource('Config')->dpla->zoomit->update_cache_param); ?>" class="zoomit_images"><?php echo html_escape($image->original_filename); ?></a>
    <?php endforeach; ?>
    <div class="zoomit_viewer"></div>
</div>
