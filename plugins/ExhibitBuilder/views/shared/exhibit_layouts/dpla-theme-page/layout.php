<!-- TODO: make refactoring - reuse of code within "story" and "theme" layout -->
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

                        <?php $unique_id = "itemDetailsBox_".hash("md4", exhibit_builder_exhibit_item_uri($item['item'])) ?>
                        <a href="#<?=$unique_id?>" class="show-item-details cboxElement"><span>i</span></a>
                        <div class="overlay">
                            <div id="<?=$unique_id?>">
                                <div class="cboxClose" class="pclose">&times;</div>

                                <!-- #23169: Exhibition Item-level Metadata: call API or display Omeka meta data -->
                                <h1><?=$item['caption']?></h1>
                                <article>
                                    <p>
                                        <?php
                                        $desc = metadata($item['item'], array('Dublin Core', 'Description'));
                                        echo strlen($desc) >= 250 ? substr($desc, 0, 250)."..." : $desc;
                                        // TODO: maybe we will have to display expandable version of full description
                                        ?>
                                    </p>

                                    <div class="table">
                                        <?php $json = get_dpla_api_object(dpla_get_field_value_by_name($item, 'Has Version')); ?>

                                        <?php if ($value = $json ? dpla_get_field_value_by_arrayname($json, array('sourceResource', 'creator'))
                                            : dpla_get_field_value_by_name($item, "Creator")): ?>
                                            <ul>
                                                <li><h6>Creator</h6></li>
                                                <li><?=$value?></li>
                                            </ul>
                                        <?php endif; ?>

                                        <?php if ($value = $json ? dpla_get_field_value_by_arrayname($json, array('sourceResource', 'date', 'displayDate'))
                                            : dpla_get_field_value_by_name($item, "Date")): ?>
                                            <ul>
                                                <li><h6>Created Date</h6></li>
                                                <li><?=$value?></li>
                                            </ul>
                                        <?php endif; ?>

                                        <?php if ($value = $json ? dpla_get_field_value_by_arrayname($json, array('originalRecord', 'rights'))
                                            : dpla_get_field_value_by_name($item, "Rights")): ?>
                                            <ul>
                                                <li><h6>Rights</h6></li>
                                                <li><?=$value?></li>
                                            </ul>
                                        <?php endif; ?>

                                        <?php if ($provider = $json ? dpla_get_field_value_by_arrayname($json, array('provider', 'name'))
                                            : dpla_get_field_value_by_name($item, "Source")): ?>
                                            <ul>
                                                <li><h6>Provider</h6></li>
                                                <li><?=$provider?></li>
                                            </ul>
                                        <?php endif; ?>

                                        <?php if ($value = $json ? dpla_get_field_value_by_arrayname($json, array('dataProvider')) : null) : ?>
                                            <?php if ($provider != $value): ?>
                                                <ul>
                                                    <li><h6>Data Provider</h6></li>
                                                    <li><?=$value?></li>
                                                </ul>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if ($value = dpla_get_field_value_by_name($item, "References")): ?>
                                            <ul>
                                                <li><h6>References</h6></li>
                                                <li><?=$value?></li>
                                            </ul>
                                        <?php endif; ?>
                                    </div>

                                </article>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>

            </ul>
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