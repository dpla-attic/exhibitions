<div class="slide-Container">
    <div class="slidegallery">
        <div class="slides">
            <?php if ($attachment = exhibit_builder_page_attachment(1)): ?>
            <section>
                    <?php echo dpla_attachment_markup($attachment, array('imageSize' => 'fullsize'), array('class' => 'permalink')); ?>
            </section>
            <?php endif; ?>
        </div>
        <div class="thumbs">
            <?php echo dpla_thumbnail_gallery(1, 7, array('class'=>'permalink')); ?>
        </div>
    </div>
</div>

<?php echo exhibit_builder_page_text(2); ?>
