<script type="text/javascript">
jQuery(document).ready(function () {

    var docsviewerParent = jQuery('.docsviewer');
    docsviewerParent.each(function () {

        var docviewer = jQuery(this).find('.docsviewer_viewer');

        // Set the default docviewer.
        console.log("DocsViewer: display document");
        docviewer.empty();
        docviewer.append(
            '<h2><?php echo __('Viewing'); ?>: ' + <?php echo js_escape($docs[0]->original_filename); ?> + '</h2>'
                + '<iframe src="' + <?php echo js_escape(DocsViewerPlugin::API_URL . '?' . http_build_query(array('url' => $docs[0]->getWebPath('original'), 'embedded' => 'true'))); ?>
                + '" width="100%" height="600" style="border: none;"></iframe>');

    });

});
</script>
<div class="docsviewer">
    <?php if (1 < count($docs)): ?>
    <ul>
        <?php foreach($docs as $doc): ?>
        <li>
            <a href="<?php echo html_escape(DocsViewerPlugin::API_URL . '?' . http_build_query(array('url' => $doc->getWebPath('original'), 'embedded' => 'true'))); ?>"
               class="docsviewer_docs">
                <?php echo html_escape($doc->original_filename); ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <div class="docsviewer_viewer"></div>
</div>
