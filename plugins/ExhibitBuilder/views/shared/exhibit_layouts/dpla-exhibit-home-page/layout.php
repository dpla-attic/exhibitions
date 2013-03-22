<? if ($attachment = exhibit_builder_page_attachment(2)): ?>
    <div class="slide-Container">
        <div class="slidegallery">
            <div class="slides">
            <section id="slideshow">
                <? echo dpla_attachment_markup($attachment, array('imageSize' => 'fullsize'), array('class' => 'permalink')); ?>
            </section>
            </div>
            <? if ($gallery = dpla_thumbnail_gallery(2, 7, array('class'=>'permalink'))): ?>
                <div id="thumbs"><?= $gallery ?></div>
            <? endif; ?>
        </div>
    </div>
<? endif; ?>

<?php echo exhibit_builder_page_text(2); ?>

<ul class="prevNext">
    <?php // TODO: Define first and last pages ?>
    <? if ($nextLink = dpla_link_to_next_page('Next »')): ?>
        <li class="btn"><?= $nextLink ?></li>
    <? endif; ?>
    <li><?= dpla_page_position(); ?></li>
</ul>