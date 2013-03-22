<fieldset class="<?php echo html_escape($layout); ?>">

<div class="primary">
	<h3>Desc</h3>
	<?php 
	    echo exhibit_builder_layout_form_text(1); 
	?>
</div>
<div class="secondary gallery">
	<h3>Assets</h3>
	<?php 
	    for($i=1;$i<=4;$i++):
	        echo exhibit_builder_layout_form_item($i);
	    endfor;
	?>
</div>	
</fieldset>
