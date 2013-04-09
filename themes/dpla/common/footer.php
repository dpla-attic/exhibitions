    </div><!-- end wrap -->

    <footer>
        <nav>
            <ul>
                <li><a href="">Home</a></li>
                <li><a href="">Subjects</a></li>
                <li><a href="">Collections</a></li>
                <li><a href="">Exhibitions</a></li> 
                <li><a href="">Map</a></li> 
                <li><a href="">Timeline</a></li>
                <li><a href="">App Library</a></li>
            </ul>
            <ul class="footer-navTwo">
                <li><a href="">Help</a></li>
                <li><a href="">About</a></li>   
                <li class="news-clear"><a href="">News</a></li>
                <li><a href="">Contact</a></li> 
                <li><a href="">Accessibility</a></li>   
            </ul>   
        </nav>
        <div class="footerBottom">
            <ul class="social icons" id="social">
                <li class="facebook"><a href=""><span aria-hidden="true" class="icon-facebook"></span><span class="visuallyhidden">Facebook</span></a></li>
                <li class="twitter"><a href=""><span aria-hidden="true" class="icon-twitter"></span><span class="visuallyhidden">Twitter</span></a></li>
                <li class="RSS"><a href=""><span aria-hidden="true" class="icon-rss"></span><span class="visuallyhidden">RSS</span></a></li>
            </ul>
            <a href="/"><img src="<?php echo img('footer-logo.png'); ?>" class="logo" /></a>
        </div>
        <?php if ((get_theme_option('Display Footer Copyright') == 1) && $copyright = option('copyright')): ?>
            <p><?php echo $copyright; ?></p>
        <?php endif; ?>

        <?php fire_plugin_hook('public_footer'); ?>

    </footer><!-- end footer -->
    </div>
        <!-- JavaScripts -->
    <?php queue_js_file('vendor/jquery.mobile-1.2.0.min'); ?>
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
