<fieldset class="<?php echo html_escape($layout); ?>">

<div class="primary">
	<h3>Thumbnail</h3>
	<?php echo exhibit_builder_layout_form_item(1);?>
    <h3>mini-Thumbnail</h3>
    <?php echo exhibit_builder_layout_form_item(2);?>
    <h3>Description</h3>
    <?php echo exhibit_builder_layout_form_text(1);?>

    <h3>External Exhibition URL</h3>

    <p><b>Fill this field only if this is external exhibition</b></p>
    <p>This should be full URL, with protocol, host and path, e.g. http://exhibitions.europeana.eu/exhibits/show/europe-america-en</p>
    <?php echo external_exhibit_uri_layout_input_text(2);?>
</div>






</fieldset>
