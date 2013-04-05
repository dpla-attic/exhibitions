<?php
$unique_id = "docsviewer_".hash("md4", $docs[0]->getWebPath('original'))
?>
<script type="text/javascript">
jQuery(document).ready(function () {

    var docsviewerParent = jQuery('#<?=$unique_id?>');

    var docviewer = docsviewerParent.find('.docsviewer_viewer');

    // Set the default docviewer.
    console.log("DocsViewer: display document");
    docviewer.empty();
    docviewer.append(
        '<iframe src="' + <?php echo js_escape(DocsViewerPlugin::API_URL . '?' . http_build_query(array('url' => $docs[0]->getWebPath('original'), 'embedded' => 'true'))); ?>
            + '" width="100%" height="420" style="border: none;"></iframe>');

});
</script>
<div class="docsviewer" id="<?=$unique_id?>">
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
