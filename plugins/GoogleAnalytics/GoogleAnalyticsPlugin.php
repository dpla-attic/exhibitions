<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GoogleAnalytics plugin class
 *
 * @package GoogleAnalytics
 */
class GoogleAnalyticsPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'install',
        'uninstall',
        'public_head',
        'config_form',
        'config'
    );

    public function hookInstall()
    {
        set_option('google_analytics_key', '');
    }

    public function hookUninstall()
    {
        delete_option('google_analytics_key');
    }

    public function hookPublicHead()
    {
        $gaKey = get_option('google_analytics_key'); // google analytics key
        $analytics =<<< ANALYTICS
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', '$gaKey', 'auto');
ga('send', 'pageview');
ANALYTICS;

        queue_js_string($analytics);
    }

    public function hookConfig()
    {
        set_option('google_analytics_key', trim($_POST['google_analytics_key']));
    }

    public function hookConfigForm()
    {
        require dirname(__FILE__) . '/config_form.php';
    }
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
