<?php if (count(dpla_get_exhibitpage_entries()) > 0): ?>
    <div class="slide-Container">
        <div class="slidegallery flexslider">
            <?php $items = dpla_get_exhibitpage_entries(2); ?>
            <ul class="slides <?= count($items) == 1 ? "single-slide" : ""?>">

                <?php foreach ($items as $item): ?>
                    <li data-thumb="<?=$item['file_uri_square'] ?>" class="flexslider-slide">
                        <div class="plugin-content">
                            <?php
                            if ($media = get_plugin_hook_output('public_items_show', array('view' => get_view(), 'item' => $item['item']))) {
                                echo $media;
                            } else {
                                echo files_for_item(array(), array('class'=>'item-file'), $item['item']);
                            }
                            ?>
                        </div>
                        
                         <?php $json = get_dpla_api_object(dpla_get_field_value_by_name($item, 'Has Version')); ?>
                         <?php $imageTitle = $value = $json ? dpla_get_field_value_by_arrayname($json, array('sourceResource', 'title'))
                             : dpla_get_field_value_by_name($item, "Title"); ?>                        
                        <div class="caption">
                            <?=$item['caption']?>
                        </div>

                        <?php $unique_id = "itemDetailsBox_".hash("md4", exhibit_builder_exhibit_item_uri($item['item'])) ?>
                        <span data-id="<?=$unique_id?>" class="show-item-details cboxElement"><span>i</span></span>
                        <div class="overlay">
                            <div id="<?=$unique_id?>">

                                <!-- #23169: Exhibition Item-level Metadata: call API or display Omeka meta data -->
                                <div class="inline_content">
                                    <article>
                                        <h5>
                                        	<?php if ($imageTitle): ?>
                                            <?php
                                            //$desc = metadata($item['item'], array('Dublin Core', 'Description'));
                                            // echo strlen($desc) >= 250 ? substr($desc, 0, 250)."..." : $desc;
                                            //echo strlen($value) >= 250 ? substr($value, 0, 250)."..." : $value;
                                            echo $imageTitle; 
                                            // TODO: maybe we will have to display expandable version of full description
                                            ?>
                                            <?php endif; ?>
                                        </h5>
                                    
                                        <div class="table">
                                    
                                            <?php if ($value = $json ? dpla_get_field_value_by_arrayname($json, array('sourceResource', 'date', 'displayDate'))
                                                : dpla_get_field_value_by_name($item, "Date")): ?>
                                                <ul>
                                                    <li><h6>Date</h6></li>
                                                    <li><?=$value?></li>
                                                </ul>
                                            <?php endif; ?>

                                            <?php if ($value = $json ? dpla_get_field_value_by_arrayname($json, array('sourceResource', 'creator'))
                                                : dpla_get_field_value_by_name($item, "Creator")): ?>
                                                <ul>
                                                    <li><h6>Creator</h6></li>
                                                    <li><?=$value?></li>
                                                </ul>
                                            <?php endif; ?>                                            
                                    
                                            <?php if ($value = $json ? dpla_get_field_value_by_arrayname($json, array('sourceResource', 'description'))
                                                : dpla_get_field_value_by_name($item, "Description")): ?>
                                            <?php $value = strlen($value) >= 250 ? substr($value, 0, 250)."..." : $value;?>
                                                <ul>
                                                    <li><h6>Description</h6></li>
                                                    <li>
                                                     <div class="desc-short">
                                                            <p><?=$value?>...&nbsp; <a class="desc-toggle">more <span class="icon-arrow-down" aria-hidden="true"></span></a></p>
                                                        </div>

                                                        <div class="desc-long">
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque in ante ante. Aliquam ultrices laoreet est nec rhoncus. Donec consectetur sem ut nisi facilisis scelerisque. Sed laoreet, velit cursus ullamcorper pulvinar, justo tellus tempor enim, quis dapibus nisi nisi ac felis. Fusce lacus tellus, tempus et tincidunt a, volutpat vitae dolor. Vestibulum egestas pellentesque neque a laoreet. Curabitur leo velit, molestie ac porta nec, blandit a augue. Praesent vel scelerisque eros, purus dolor venenatis metus, vel aliquet purus nisi vitae elit. Donec commodo urna aliquam lorem dignissim vehicula. Aenean eleifend massa in ipsum auctor in tempus augue porta.</p>

                                                            <p>Fusce quis purus eu nisi fringilla varius at a nibh. Aliquam erat volutpat. Morbi metus sapien, fringilla nec pretium sit amet, tempor vitae orci. Nullam molestie vehicula tincidunt. Nulla sit amet orci eu diam consectetur congue sit amet eget libero. Aenean eu sem et ligula semper sollicitudin at eu odio. Mauris sem nulla, scelerisque quis semper sit amet, consectetur nec mi. Nulla ligula mi, gravida nec viverra id, tempus suscipit sapien. Vivamus mi sem, luctus at mollis vel, sollicitudin eget nunc. Nulla suscipit magna a dui accumsan dapibus congue lectus elementum.</p>

                                                            <p>Quisque porttitor hendrerit metus nec vulputate. Quisque dolor dolor, sagittis vel porttitor id, porta in massa. Donec eget mauris in nisi eleifend consectetur vitae eget nunc. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Duis vel est eu elit lobortis auctor sit amet at velit. Fusce ac ante ut tellus accumsan congue a a metus. Nulla facilisi. In blandit, massa eget hendrerit sodales, elit mi lobortis purus, vitae volutpat eros lectus sit amet nibh. Aliquam scelerisque vestibulum tincidunt. Cras sollicitudin viverra pretium. Ut dictum elit tempus leo eleifend fermentum. Sed dui sapien, scelerisque sit amet fringilla vitae, tincidunt eget lorem. In sapien mauris, imperdiet quis scelerisque at, tincidunt quis dolor.</p>

                                                            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam erat volutpat. Cras non nisl et dolor fringilla vulputate. Etiam malesuada ullamcorper tristique. Maecenas justo ligula, consectetur id ornare a, congue et augue. Nulla facilisi. Morbi in malesuada quam. Duis cursus volutpat odio, vitae commodo quam luctus hendrerit. Fusce convallis ultricies rutrum. Nullam commodo, dolor et tincidunt ultricies, enim leo iaculis ligula, eu consequat enim est id turpis. Aliquam pharetra adipiscing sapien, ut rutrum nibh vulputate ac. Phasellus sem augue, sodales sollicitudin vulputate id, aliquet vitae diam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur feugiat, risus a venenatis placerat, elit neque facilisis tortor, sed laoreet quam quam ut risus.&nbsp; <a class="desc-toggle">less <span class="icon-arrow-up" aria-hidden="true"></span></a></p>
                                                        </div>
                                                    </li>
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
                                    
                                            <?php if ($value = dpla_get_field_value_by_name($item, "Is Part Of")): ?>
                                                <ul>
                                                    <li><h6>Is Part Of</h6></li>
                                                    <li><?=$value?></li>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    
                                    </article>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>
    </div>

<?php endif; ?>


<div class="slide_bottom">
    <?php echo exhibit_builder_page_text(2); ?>
    <ul class="prevNext">
        <?php // TODO: Define first and last pages ?>
        <? if ($nextLink = dpla_link_to_next_page('Next »')): ?>
            <li class="btn"><?= $nextLink ?></li>
        <? endif; ?>
        <li><?= dpla_page_position(); ?></li>
    </ul>
</div>

<!--[if IE 8]>

<style type="text/css">
    #exhibit-item-thumbnails .exhibit-item {
        float:none !important;
    }
    #exhibit-item-thumbnails{
        white-space:nowrap;
    }
</style>

<![endif]-->

<!--[if lte IE 7]>
<style type="text/css">
    body{
        /* disable responsive behaviour (limit size to stop layout breaking) */
        min-width:	768px;
    }

    #story{
        max-width:47%;
    }

</style>
<![endif]-->