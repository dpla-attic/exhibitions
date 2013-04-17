<?php
echo head(array(
    'title' => metadata('exhibit_page', 'title') . ' &middot; ' . metadata('exhibit', 'title'),
    'bodyid' => 'exhibit',
    'bodyclass' => 'show'));
?>

    <?php fire_plugin_hook('public_content_top'); ?>

    <? $exhibitPage = get_current_record('exhibit_page'); ?>
    <div class="breadCrumbs">
        <ul>
            <li><a href="<?=Zend_Registry::get('bootstrap')->getResource('Config')->dpla->exhibitionsUrl?>">Exhibitions</a></li>
            <li><?php echo link_to_exhibit(); ?></li>
            <?php if($exhibitPage->parent_id): ?>
                <li><?php echo dpla_link_to_parent_page(); ?></li>
                <li><?= $exhibitPage->title ?></li>
            <? else: ?>
                <li><?php echo dpla_link_to_current_page(); ?></li>
                <li>Introduction</li>
            <?php endif; ?>
        </ul>
    </div>

    <ul class="shareSave">
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

    <div class="upper-section">
        <div class="upper-left">
            <h1><?php echo metadata('exhibit', 'title'); ?></h1>
            <h2><?php echo metadata('exhibit_page', 'title'); ?></h2>
        </div>
        <div class="sideNav">
            <span class="head">Themes <span class="icon-arrow-down" aria-hidden="true"></span></span>
            <?php echo dpla_theme_nav(); ?>
        </div>  
    </div>
    
<article id="content" role="main">

<?php exhibit_builder_render_exhibit_page(); ?>

</article>

<?php echo foot(); ?>
