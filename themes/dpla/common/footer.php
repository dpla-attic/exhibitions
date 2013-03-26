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
            <ul class="social icons">
                <li class="facebook"><a href=""><span aria-hidden="true" class="icon-facebook"></span></a></li>
                <li class="twitter"><a href=""><span aria-hidden="true" class="icon-twitter"></span></a></li>
                <li class="RSS"><a href=""><span aria-hidden="true" class="icon-rss"></span></a></li>
            </ul>
            <img src="<?php echo img('footer-logo.png'); ?>" class="logo" />
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
    <script type="text/javascript">
    jQuery(document).ready(function () {
        Omeka.showAdvancedForm();

        var players = jQuery('[id^="html5-media"]');

        jQuery('.flexslider')
            .fitVids()
            .flexslider({
                animation: 'fade',
                controlNav: "thumbnails",
                animationLoop: false,
                before: function(){
                    jQuery.each(players, function(){
                        if (players.length) {
                            jQuery(this)[0].pause();
                        }

                    });                  
                }
            })
            .flexslider('pause');
    });
    </script>

</body>
</html>
