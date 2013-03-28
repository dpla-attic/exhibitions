<?php
$unique_id = "zoomit_".hash("md4", $images[0]->getWebPath('original'))
?>
<script type="text/javascript">
jQuery(document).ready(function () {

    var zoomitParent = jQuery('#<?=$unique_id?>');

    var zoomit = zoomitParent.find('.zoomit_viewer').first();

    var find = zoomitParent.find('.zoomit_images');
    jQuery(find[0]).click(function(event) {
        event.preventDefault();
        zoomit.empty();
        zoomit.append(
            '<h2><?php echo __('Viewing'); ?>: ' + zoomitParent.text() + '</h2>'
                + '<iframe class="zoomit_iframe" '
                + 'src="about:blank" '
                + 'style="border:none;"'
                + 'width="100%" '
                + 'height="420"></iframe>'
        );
        console.log("ZoomIt: Before call GET service: " + this.href);
        jQuery.get('<?php echo ZoomitPlugin::API_URL; ?>?url=' + encodeURIComponent(this.href), function(data) {
            console.log("ZoomIt: After call GET service");
            var iframe = jQuery(zoomit).find('.zoomit_iframe')[0].contentWindow.document;

            // this may be already loaded
            if (iframe.innerHTML != '') {
                iframe.open();
                iframe.write(data.content.embedHtml);
                iframe.close();
            }
        }, 'jsonp');
    });

});
</script>
<div class="zoomit" id="<?=$unique_id?>">
    <h2><?php echo __('Image Viewer'); ?></h2>
    <p><?php echo __('Click below to view an image using the %sZoom.it%s viewer.', '<a href="http://zoom.it/">', '</a>'); ?></p>
    <ul>
        <?php foreach($images as $image): ?>
        <li><a href="<?php echo html_escape($image->getWebPath('original')); ?>" class="zoomit_images"><?php echo html_escape($image->original_filename); ?></a></li>
        <?php endforeach; ?>
    </ul>
    <div class="zoomit_viewer"></div>
</div>
