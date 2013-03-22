<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')), 'bodyid'=>'item','bodyclass' => 'show', 'layout' => 'layout item-detail')); ?>
    <?php fire_plugin_hook('public_content_top'); ?>
    
    <ul class="shareSave">
        <li class="btn"><a href="">Save to...</a>
          <ul>
            <li><a href="">Unlisted</a></li>
            <li><a href="">List #1</a></li>
            <li><a href="">List #2</a></li>
            <li><a href="">List #3</a></li>
            <li><a href="">List #4</a></li>
          </ul>
        </li>
        <li class="btn">
            <a href="">Share</a>
            <ul>
                <li><div class="sharebtn"><div id="fb-root"></div><div class="fb-like" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div></div></li>
                <li><div class="sharebtn"><a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div></li>
                <li><div class="sharebtn"><div class="g-plusone" data-size="medium"></div></div></li>
            </ul>
        </li>
    </ul>

<h1><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h1>

<article>

    <div class="FeatureContent">
        <div class="contentBox">
            <img src="img/image.jpg" alt="img">
            <a class="ViewObject" href="">View Object <span class="icon-view-object" aria-hidden="true"></span></a>
        </div>

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

        <div class="detail-bottom">
            <h6>Description</h6>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ultricies libero nec velit sollicitudin eget ornare</p>
        </div>
    </div>

    <?php echo all_element_texts('item'); ?>

</article>

<aside>

    <!-- The following returns all of the files associated with an item. -->
    <?php if (metadata('item', 'has files')): ?>
    <div id="itemfiles" class="element">
        <h3><?php echo __('Files'); ?></h3>
        <div class="element-text"><?php echo files_for_item(); ?></div>
    </div>
    <?php endif; ?>

    <!-- If the item belongs to a collection, the following creates a link to that collection. -->
    <?php if (metadata('item', 'Collection Name')): ?>
    <div id="collection" class="element">
        <h3><?php echo __('Collection'); ?></h3>
        <div class="element-text"><p><?php echo link_to_collection_for_item(); ?></p></div>
    </div>
    <?php endif; ?>

    <!-- The following prints a list of all tags associated with the item -->
    <?php if (metadata('item', 'has tags')): ?>
    <div id="item-tags" class="element">
        <h3><?php echo __('Tags'); ?></h3>
        <div class="element-text"><?php echo tag_string('item'); ?></div>
    </div>
    <?php endif;?>

    <!-- The following prints a citation for this item. -->
    <div id="item-citation" class="element">
        <h3><?php echo __('Citation'); ?></h3>
        <div class="element-text"><?php echo metadata('item', 'citation', array('no_escape' => true)); ?></div>
    </div>

    <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>

</aside>

<?php echo foot(); ?>
