<?php
echo head(array(
    'title' => metadata('exhibit_page', 'title') . ' &middot; ' . metadata('exhibit', 'title'),
    'bodyid' => 'exhibit',
    'bodyclass' => 'show'));
?>

<article>
    <?php fire_plugin_hook('public_content_top'); ?>

    <? $exhibitPage = get_current_record('exhibit_page'); ?>
    <div class="breadCrumbs">
        <ul>
            <li><a href="/">Exhibitions</a></li>
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
        <li class="btn"><a href="">Share</a></li>
    </ul>

    <div class="upper-section">
        <div class="upper-left">
            <h1><?php echo metadata('exhibit', 'title'); ?></h1>
            <h3><span class="exhibit-page"><?php echo metadata('exhibit_page', 'title'); ?></h3>
        </div>
        <div class="sideNav">
            <span class="head">Themes</span>
            <?php echo dpla_theme_nav(); ?>
        </div>  
    </div>

<?php exhibit_builder_render_exhibit_page(); ?>

</article>

<?php echo foot(); ?>
