<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <?php if ( $description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>
    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <!-- Plugin Stuff -->

    <?php fire_plugin_hook('public_head', array('view'=>$this)); ?>

    <!-- Stylesheets -->
    <?php
    queue_css_file('normalize');
    queue_css_file('style');
    queue_css_file('main');
    echo head_css();
    ?>
</head>
<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
        <div class="container">

            <header>
                <?php fire_plugin_hook('public_header'); ?>
                <div id="site-title" class="logo"><?php echo link_to_home_page('<img src="' . img('logo.png') . '" />'); ?></div>
                <nav class="topNav">
                    <ul>
                        <li class="aboutMenu"><a href="">About <span aria-hidden="true" class="icon-arrow-thin-down"></span></a>
                          <ul>
                            <li><a href="">Overview</a></li>
                            <li><a href="">Leadership</a></li>
                            <li><a href="">Workstreams</a></li>
                            <li><a href="">For Developers</a></li>
                            <li><a href="">Get Involved</a></li>
                          </ul>
                        </li>
                        <li><a href="">News</a></li>
                        <li><a href="">Contact</a></li>
                        <li class="bg"><a href="">Login</a></li>    
                        <li class="bg last"><a href="">Sign Up</a></li> 
                    </ul>   
                </nav>
                <nav class="MainNav">
                    <ul class="navigation">
                        <li>
                            <a href="http://54.245.27.141">Home</a>
                        </li>
                        <li>
                            <a href="/subjects">Subjects</a>
                        </li>
                        <li>
                            <a href="/collections">Collections</a>
                        </li>
                        <li class="active">
                            <a href="/">Exhibitions</a>
                        </li>
                        <li>
                            <a href="/map">Map</a>
                        </li>
                        <li>
                            <a href="/timeline">Timeline</a>
                        </li>
                        <li>
                            <a href="/apps">App Library</a>
                        </li>
                        <li>
                            <a href="/help">Help</a>
                        </li>
                    </ul>
                </nav>
            </header>

        <div id="wrap" class="layoutTwo"> 
            <section class="searchRow">
                <div class="searchRowLeft"></div>
                <div class="searchRowRight">
                    <a class="search-btn" href=""><span aria-hidden="true" class="icon-mag-glass"></span></a>
                    
                    <?php echo search_form(
                            array(
                                'show_advanced' => false,
                                'form_attributes' => array(
                                    'class' => 'search-form '
                                )
                            )
                          );
                     ?>
                </div>
            </section>

            <div id="content">
                <?php fire_plugin_hook('public_content_top'); ?>
