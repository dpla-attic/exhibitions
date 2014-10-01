<?php 
    $config = Zend_Registry::get('bootstrap')->getResource('Config');
    $baseUrl = $config->dpla->frontendUrl;
    $wpUrl = $config->dpla->wordpressURL;
    $exhibitionsUrl = $config->dpla->exhibitionsUrl;
    $assetsDir = $config->dpla->assets_dir_name;
    $assetsPath = BASE_DIR . $config->dpla->theme_path . $assetsDir;
?>
    </div><!-- end wrap -->

    <footer>
        <nav>
            <ul>
                <li><a href="<?= $baseUrl ?>">Home</a></li>
                <li><a href="<?= $exhibitionsUrl ?>">Exhibitions</a></li> 
                <li><a href="<?= $baseUrl ?>/map">Map</a></li> 
                <li><a href="<?= $baseUrl ?>/timeline">Timeline</a></li>
                <li><a href="<?= $baseUrl ?>/bookshelf">Bookshelf</a></li>
                <li><a href="<?= $baseUrl ?>/partners">Partners</a></li>
                <li><a href="<?= $baseUrl ?>/subjects">Subjects</a></li>
                <li><a href="<?= $baseUrl ?>/apps">Apps</a></li>
            </ul>
            <ul class="footer-navTwo">
                <li><a href="<?= $wpUrl ?>/">About</a></li>   
                <li class="news-clear"><a href="<?= $wpUrl ?>/news/">Follow</a></li>
                <li><a href="<?= $wpUrl ?>/contact/">Contact</a></li> 
                <li><a href="<?= $wpUrl ?>/terms/">Terms & Privacy</a></li>   
            </ul>   
        </nav>
        <div class="footerBottom">
            <ul class="social icons" id="social">
                <li class="facebook"><a href="https://www.facebook.com/digitalpubliclibraryofamerica"><span aria-hidden="true" class="icon-facebook"></span><span class="visuallyhidden">Facebook</span></a></li>
                <li class="twitter"><a href="https://twitter.com/dpla"><span aria-hidden="true" class="icon-twitter"></span><span class="visuallyhidden">Twitter</span></a></li>
                <li class="RSS"><a href="http://dp.la/info/feed"><span aria-hidden="true" class="icon-rss"></span><span class="visuallyhidden">RSS</span></a></li>
                <li class="tumblr"><a href="http://digitalpubliclibraryofamerica.tumblr.com/"><span aria-hidden="true" class="icon-tumblr"></span><span class="visuallyhidden">RSS</span></a></li>
            </ul>
            <a href="<?= $baseUrl ?>" class="logo">
                    <?php
                        if (file_exists($assetsPath . '/images/logo.png')): ?>
                            <img src="<?php echo img('logo.png', $assetsDir .'/images/'); ?>" alt="DPLA: Digital Public Library of America" />
                        <?php else: ?>
                            <img src="<?php echo img('logo.png'); ?>" alt="" />
                    <?php endif; ?>
            </a>
        </div>
        <?php if ((get_theme_option('Display Footer Copyright') == 1) && $copyright = option('copyright')): ?>
            <p><?php echo $copyright; ?></p>
        <?php endif; ?>

        <?php fire_plugin_hook('public_footer'); ?>

    </footer><!-- end footer -->
    </div>
        <!-- JavaScripts -->
    <?php queue_js_file('vendor/jquery.mobile-1.2.0.min'); ?>
    <?php queue_js_file('vendor/fastclick'); ?>
    <?php queue_js_file('jquery.jcarousel.min'); ?>
    <?php queue_js_file('vendor/jquery.opacityrollover'); ?>
    <?php queue_js_file('vendor/jquery.colorbox-min'); ?>
    <?php queue_js_file('vendor/selectivizr', 'javascripts', array('conditional' => '(gte IE 6)&(lte IE 8)')); ?>
    <?php queue_js_file('vendor/respond'); ?>
    <?php queue_js_file('plugins'); ?>
    <?php queue_js_file('main'); ?>
    <?php queue_js_file('globals'); ?>
    <?php echo head_js(); ?>

    <ul class="jump-links">
        <li><a href="#top-nav" accesskey="1">Return to top navigation</a></li>
        <li><a href="#main-nav" accesskey="2">Return to main navigation</a></li>
        <li><a href="#searchBox" accesskey="3">Return to search form</a></li>
        <li><a href="#content" accesskey="4">Return to main content</a></li>
        <li><a href="#social" accesskey="5">Return to social media navigation</a></li>
    </ul>
</body>
</html>
