<? if ($attachment = exhibit_builder_page_attachment(1)): ?>
    <div class="slide-Container">
        <div class="slidegallery">
            <div class="slides">
            <section>
                <? echo dpla_attachment_markup($attachment, array('imageSize' => 'fullsize'), array('class' => 'permalink')); ?>
            </section>
            </div>
            <? if ($gallery = dpla_thumbnail_gallery(1, 7, array('class'=>'permalink'))): ?>
                <div class="thumbs"><?= $gallery ?></div>
            <? endif; ?>
        </div>
    </div>
<? endif; ?>

<?php echo exhibit_builder_page_text(2); ?>
