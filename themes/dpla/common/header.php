<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta content="width=device-width" name="viewport">
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
    queue_css_file('main');
    queue_css_file('dpla-colors');
    queue_css_file('galleriffic');
    echo head_css();
    ?>
    <?php queue_js_file('vendor/modernizr-2.6.2.min'); ?>
    <?php echo head_js(); ?>
</head>
<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <?php # fire_plugin_hook('public_body', array('view'=>$this)); ?>

        <ul class="jump-links">
            <li><a href="#top-nav" accesskey="1">Go to top navigation</a></li>
            <li><a href="#main-nav" accesskey="2">Go to main navigation</a></li>
            <li><a href="#searchBox" accesskey="3">Go to search form</a></li>
            <li><a href="#content" accesskey="4">Go to main content</a></li>
            <li><a href="#social" accesskey="5">Go to social media navigation</a></li>
        </ul>
        <div class="container">

            <header>
                <?php fire_plugin_hook('public_header'); ?>
                <?php
                    $config = Zend_Registry::get('bootstrap')->getResource('Config');
                    $baseUrl = $config->dpla->frontendUrl;
                    $wpUrl = $config->dpla->wordpressURL;
                ?>
                <a href="/" class="logo"><img src="<?php echo img('logo.png'); ?>" alt="DPLA: Digital Public Library of America" /></a>
                <a class="menu-btn" href=""><span aria-hidden="true" class="icon-arrow-thin-down"></span><span class="visuallyhidden">Navigation</span></a>
                <nav class="topNav" id="top-nav">
                    <ul>
                        <li class="aboutMenu">
                            <a href="<?= $wpUrl ?>/about">About<span aria-hidden="true" class="icon-arrow-thin-down"></span></a>
                            <ul>
                                <li><a href="<?= $wpUrl ?>/about/history">History</a></li>
                                <li><a href="<?= $wpUrl ?>/about/who">Who We Are</a></li>
                                <li><a href="<?= $wpUrl ?>/about/org">Organization</a></li>
                                <li><a href="<?= $wpUrl ?>/about/funding">Funding</a></li>
                                <li><a href="<?= $wpUrl ?>/about/faq">FAQ</a></li>
                            </ul>
                        </li>
                        <li class="aboutMenu">
                            <a href="<?= $wpUrl ?>/get-involved">Get Involved<span aria-hidden="true" class="icon-arrow-thin-down"></span></a>
                            <ul>
                                <li><a href="<?= $wpUrl ?>/get-involved/partnerships">Partnerships</a></li>
                                <li><a href="<?= $wpUrl ?>/get-involved/events">Events</a></li>
                                <li><a href="<?= $wpUrl ?>/forums">Forums</a></li>
                            </ul>
                        </li>
                        <li class="aboutMenu">
                            <a href="<?= $wpUrl ?>/developers">For Developers<span aria-hidden="true" class="icon-arrow-thin-down"></span></a>
                            <ul>
                                <li><a href="<?= $wpUrl ?>/developers/codex">API Codex</a></li>
                            </ul>
                        </li>
                        <li><a href="<?= $wpUrl ?>/help">Help</a></li>
                        <li class="aboutMenu">
                            <a href="<?= $wpUrl ?>/news">Follow<span aria-hidden="true" class="icon-arrow-thin-down"></span></a>
                            <ul>
                                <li><a href="<?= $wpUrl ?>/news/blog">Blog</a></li>
                                <li><a href="<?= $wpUrl ?>/news/press">Press</a></li>
                            </ul>
                        </li>
                        <li><a href="<?= $wpUrl ?>/contact">Contact</a></li>
                    </ul>
                </nav>
                <nav class="MainNav" id="main-nav">
                    <ul class="navigation">
                        <li>
                            <a href="<?= $baseUrl ?>">Home</a>
                        </li>
                        <li class="active">
                            <a href="<?= $config->dpla->exhibitionsUrl ?>">Exhibitions</a>
                        </li>
                        <li>
                            <a href="<?= $baseUrl ?>/map">Map</a>
                        </li>
                        <li>
                            <a href="<?= $baseUrl ?>/timeline">Timeline</a>
                        </li>
                        <li>
                            <a href="<?= $baseUrl ?>/apps">Apps</a>
                        </li>
                        <li>
                            <a href="<?= $wpUrl ?>/help">Help</a>
                        </li>
                    </ul>
                </nav>
            </header>
        
        <?php 
            $layout = (isset($layout)) ? $layout : 'layout fullWidth';
        ?>

        <div id="wrap" class="<?php echo $layout ?>"> 
            <section class="searchRow">
                <div class="searchRowLeft"></div>
                <div class="searchRowRight">
                    <a class="search-btn" href=""><span aria-hidden="true" class="icon-mag-glass"></span><span class="visuallyhidden">Search</span></a>
                    <form class="search-form" id="searchBox" action="<?= $baseUrl . '/search' ?>">
                        <input type="text" name="q" placeholder="Search the Library">
                        <input type="submit" class="searchBtn" value="Search">
                    </form>
                </div>
            </section>
