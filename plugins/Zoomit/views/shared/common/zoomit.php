<script type="text/javascript">
jQuery(document).ready(function () {
    var zoomit = jQuery('#zoomit_viewer');
    jQuery('.zoomit_images').click(function(event) {
        event.preventDefault();
        zoomit.empty();
        zoomit.append(
        '<h2><?php echo __('Viewing'); ?>: ' + jQuery(this).text() + '</h2>'
      + '<iframe id="zoomit_iframe" ' 
      + 'src="about:blank" ' 
      + 'style="border:none;"' 
      + 'width="<?php echo $width; ?>" ' 
      + 'height="<?php echo $height; ?>"></iframe>'
        );
        jQuery.get('<?php echo ZoomitPlugin::API_URL; ?>?url=' + encodeURIComponent(this.href), function(data) {
            var iframe = jQuery('#zoomit_iframe')[0].contentWindow.document;
            iframe.open();
            iframe.write(data.content.embedHtml);
            iframe.close();
        }, 'jsonp');
    });
});
</script>
<div id="zoomit">
    <h2><?php echo __('Image Viewer'); ?></h2>
    <p><?php echo __('Click below to view an image using the %sZoom.it%s viewer.', '<a href="http://zoom.it/">', '</a>'); ?></p>
    <ul>
        <?php foreach($images as $image): ?>
        <li><a href="<?php echo html_escape($image->getWebPath('original')); ?>" class="zoomit_images"><?php echo html_escape($image->original_filename); ?></a></li>
        <?php endforeach; ?>
    </ul>
    <div id="zoomit_viewer"></div>
</div>
