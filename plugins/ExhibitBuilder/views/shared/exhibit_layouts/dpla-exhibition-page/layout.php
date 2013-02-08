<div class="text-image-right">
    <div class="image-right">
        <?php if ($attachment = exhibit_builder_page_attachment(1)): ?>
        <div class="exhibit-item">
            <?php echo exhibit_builder_attachment_markup($attachment, array('imageSize' => 'fullsize'), array('class' => 'permalink')); ?>
        </div>
        <?php endif; ?>
    </div>
    <h1>Short desc</h1>
    <?php echo exhibit_builder_page_text(1); ?>
    <h1>Long desc</h1>
    <?php echo exhibit_builder_page_text(2); ?>
    
    <div class="primary">
    	<h1>Assets</h1>
        <?php echo exhibit_builder_thumbnail_gallery(1, 7, array('class'=>'permalink')); ?>
    </div>
</div>


