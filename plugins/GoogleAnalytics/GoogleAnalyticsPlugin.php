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
var _gaq=[['_setAccount','$gaKey'],['_trackPageview']];(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=true;g.src=('https:'==location.protocol?'https://ssl':'http://www')+'.google-analytics.com/ga.js';s.parentNode.insertBefore(g,s);})(document,'script');
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
