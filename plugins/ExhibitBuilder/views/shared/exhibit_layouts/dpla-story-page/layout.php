<?php if (count(dpla_get_exhibitpage_entries()) > 0): ?>
    <div class="slide-Container">
        <div class="slidegallery flexslider">
            <?php $items = dpla_get_exhibitpage_entries(); ?>
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
                                                <ul>
                                                    <li><h6>Description</h6></li>
                                                    <li>
                                                    <?php if (strlen($value) > 250) { ?>
                                                        <div class="desc-short">
                                                            <?php echo substr($value, 0, 250); ?>...&nbsp; <a class="desc-toggle">more <span class="icon-arrow-down" aria-hidden="true"></span></a>
                                                        </div>
                                                        <div class="desc-long">
                                                            <?=$value?>&nbsp; <a class="desc-toggle">less <span class="icon-arrow-up" aria-hidden="true"></span></a>
                                                        </div>
                                                    <?php } else {  ?>
                                                        <div class="desc-short">
                                                            <?=$value?>
                                                        </div>
                                                    <?php } ?>
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
    <?php echo exhibit_builder_page_text(1); ?>
	<?php echo exhibit_builder_page_text(2); ?>

	<ul class="prevNext">
		<?php // TODO: Define first and last pages ?>
        <? if ($prevLink = dpla_link_to_previous_page('« Prev')): ?>
		    <li class="btn"><?= $prevLink ?></li>
        <? endif; ?>
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