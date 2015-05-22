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

    <?php
        $config = Zend_Registry::get('bootstrap')->getResource('Config');
        $assetsDir = $config->dpla->assets_dir_name;
        $assetsPath = BASE_DIR . $config->dpla->theme_path . $assetsDir;
    ?>

    <!-- Stylesheets -->
    <?php
    queue_css_file('normalize');
    queue_css_file('main');

    if (is_dir($assetsPath)){
        queue_css_file(
            array('dpla-colors', 'fonts'),
            'all',
            false,
            $assetsDir . '/css'
        );
    }

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
                    $baseUrl = $config->dpla->frontendUrl;
                    $wpUrl = $config->dpla->wordpressURL;
                ?>
                <a href="<?= $baseUrl ?>" class="logo">
                    <?php
                        if (file_exists($assetsPath . '/images/logo.png')): ?>
                            <img src="<?php echo img('logo.png', $assetsDir .'/images/'); ?>" alt="DPLA: Digital Public Library of America" />
                        <?php else: ?>
                            <img src="<?php echo img('logo.png'); ?>" alt="" />
                    <?php endif; ?>
                </a>
                <a class="menu-btn" href=""><span aria-hidden="true" class="icon-arrow-thin-down"></span><span class="visuallyhidden">Navigation</span></a>
                <nav class="topNav" id="top-nav">
                    <ul>
                        <li class="aboutMenu">
                            <a href="<?= $wpUrl ?>/">About<span aria-hidden="true" class="icon-arrow-thin-down"></span></a>
                            <ul>
                                <li><a href="<?= $wpUrl ?>/about/staff/">Staff</a></li>
                                <li><a href="<?= $wpUrl ?>/about/board-committees/">Board &amp; Committees</a></li>
                                <li><a href="<?= $wpUrl ?>/about/funding/">Funding</a></li>
                                <li><a href="<?= $wpUrl ?>/about/history/">History</a></li>
                                <li><a href="<?= $wpUrl ?>/about/policies/">Policies</a></li>
                                <li><a href="<?= $wpUrl ?>/about/projects/">Projects</a></li>
                                <li><a href="<?= $wpUrl ?>/about/awards/">Awards</a></li>
                            </ul>
                        </li>
                        <li class="aboutMenu">
                            <a href="<?= $wpUrl ?>/hubs/">Hubs<span aria-hidden="true" class="icon-arrow-thin-down"></span></a>
                            <ul>
                                <li><a href="<?= $wpUrl ?>/hubs/become-a-hub/">Become a Hub</a></li>
                            </ul>
                        </li>
                        <li class="aboutMenu">
                            <a href="<?= $wpUrl ?>/developers/">For Developers<span aria-hidden="true" class="icon-arrow-thin-down"></span></a>
                            <ul>
                                <li><a href="<?= $wpUrl ?>/developers/codex/">API Codex</a></li>
                                <li><a href="<?= $wpUrl ?>/developers/ideas-and-projects/">Ideas &amp; Projects</a></li>
                                <li><a href="<?= $wpUrl ?>/developers/download/">Bulk Download</a></li>
                                <li><a href="<?= $wpUrl ?>/developers/sample-code-and-libraries/">Sample Code &amp; Libraries</a></li>
                                <li><a href="<?= $wpUrl ?>/developers/map/">Metadata Application Profile</a></li>
                            </ul>
                        </li>
                        <li class="aboutMenu">
                            <a href="<?= $wpUrl ?>/get-involved">Get Involved<span aria-hidden="true" class="icon-arrow-thin-down"></span></a>
                            <ul>
                                <li><a href="<?= $wpUrl ?>/get-involved/reps/">Community Reps</a></li>
                                <li><a href="<?= $wpUrl ?>/get-involved/dpla-ebooks/dpla-collection-curation-corps/">Collection Curation Corps</a></li>
                                <li><a href="<?= $wpUrl ?>/get-involved/open-calls/">Open Calls</a></li>
                                <li><a href="<?= $wpUrl ?>/get-involved/events/">Events</a></li>
                                <li><a href="<?= $wpUrl ?>/get-involved/dplafest/">DPLAfest</a></li>
                                <li><a href="<?= $wpUrl ?>/get-involved/follow/">Follow Us</a></li>
                                <li><a href="<?= $wpUrl ?>/get-involved/shop/">Shop</a></li>
                            </ul>
                        </li>
                        <li class="aboutMenu">
                          <a href="<?= $wpUrl ?>/help">Help<span aria-hidden="true" class="icon-arrow-thin-down"></span></a>
                          <ul>
                            <li><a href="<?= $wpUrl ?>/help/tutorials/">Tutorials</a></li>
                            <li><a href="<?= $wpUrl ?>/help/faq/">FAQ</a></li>
                            <li><a href="<?= $wpUrl ?>/help/accounts/">DPLA Accounts</a></li>
                          </ul>
                        </li>
                        <li class="aboutMenu">
                            <a href="<?= $wpUrl ?>/news/">News<span aria-hidden="true" class="icon-arrow-thin-down"></span></a>
                            <ul>
                                <li><a href="<?= $wpUrl ?>/news/press/">Press</a></li>
                            </ul>
                        </li>
                        <li class="aboutMenu">
                            <a href="<?= $wpUrl ?>/contact/">Contact<span aria-hidden="true" class="icon-arrow-thin-down"></span></a>
                            <ul>
                                <li><a href="<?= $wpUrl ?>/contact/jobs/">Jobs</a></li>
                            </ul>
                        </li>
                        <li class="hilight"><a href="<?= $wpUrl ?>/donate">Donate</a></li>
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
                            <a href="<?= $baseUrl ?>/bookshelf">Bookshelf</a>
                        </li>
                        <li>
                            <a href="<?= $baseUrl ?>/apps">Apps</a>
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
                        <label class="visuallyhidden" for="searchField">Search the Library</label>
                        <input id="searchField" type="text" name="q" placeholder="Search the Library">
                        <input type="submit" class="searchBtn" value="Search">
                    </form>
                </div>
            </section>
