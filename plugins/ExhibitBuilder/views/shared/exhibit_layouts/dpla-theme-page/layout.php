<? if ($attachment = exhibit_builder_page_attachment(2)): ?>
    <div class="slide-Container">
        <div class="slidegallery flexslider">
            <ul class="slides">

                <?php foreach (dpla_get_exhibitpage_entries(2) as $item): ?>
                    <li data-thumb="<?=$item['file_uri_square'] ?>">
                        <div class="plugin-content">
                            <?php
                            if ($media = get_plugin_hook_output('public_items_show', array('view' => get_view(), 'item' => $item['item']))) {
                                echo $media;
                            } else {
                                echo files_for_item(array(), array('class'=>'item-file'), $item['item']);
                            }
                            ?>
                        </div>
                        <div class="caption">
                            <a href="<?=exhibit_builder_exhibit_item_uri($item['item'])?>"><?=$item['caption']?></a>
                        </div>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>
        <a href="#itemDetailsBox" class="show-item-details cboxElement"><span>i</span></a>
    </div>

    <div class="overlay">
        <div id="itemDetailsBox">
            <div id="cboxClose" class="pclose">&times;</div>
            <h1>Ojibwa beaded velvet loincloths</h1>
            <article>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ultricies libero nec velit sollicitudin eget ornare (description)</p>

                <div class="table">
                    <ul>
                        <li><h6>Creator</h6></li>
                        <li>Text</li>
                    </ul>
                    <ul>
                        <li><h6>Created Date</h6></li>
                        <li>Text</li>
                    </ul>
                    <ul>
                        <li><h6>Owning Institution</h6></li>
                        <li>Text</li>
                    </ul>
                    <ul>
                        <li><h6>Provider</h6></li>
                        <li>Text</li>
                    </ul>
                    <ul>
                        <li><h6>Publisher</h6></li>
                        <li>Text</li>
                    </ul>
                </div>

            </article>
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