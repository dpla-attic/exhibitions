<fieldset class="<?php echo html_escape($layout); ?>">

<div class="primary">
	<h3>Thumbnail</h3>
	<?php echo exhibit_builder_layout_form_item(1);?>
<!--    <h3>Short description</h3>-->
<!--    --><?php //echo exhibit_builder_layout_form_text(1);?>
    <h3>Long description</h3>
    <?php echo exhibit_builder_layout_form_text(2);?>
</div>





<div class="secondary">
	<h3>Assets!</h3>
	<?php echo exhibit_builder_layout_form_item(2);?>
	<?php echo exhibit_builder_layout_form_item(3);?>
	<?php echo exhibit_builder_layout_form_item(4);?>
	<?php echo exhibit_builder_layout_form_item(5);?>
    <!-- <?php echo exhibit_builder_layout_form_item(6);?>
	<?php echo exhibit_builder_layout_form_item(7);?>
	<?php echo exhibit_builder_layout_form_item(8);?>
	<?php echo exhibit_builder_layout_form_item(9);?>	 -->
</div>

</fieldset>
