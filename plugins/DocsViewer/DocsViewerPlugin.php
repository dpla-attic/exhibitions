<?php
/**
 * Docs Viewer
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The Docs Viewer plugin.
 * 
 * @package Omeka\Plugins\DocsViewer
 */
class DocsViewerPlugin extends Omeka_Plugin_AbstractPlugin
{
    const API_URL = 'http://docs.google.com/viewer';
    
    const DEFAULT_VIEWER_EMBED = 1;
    
    const DEFAULT_VIEWER_WIDTH = 500;
    
    const DEFAULT_VIEWER_HEIGHT = 600;
    
    protected $_hooks = array(
        'install',
        'uninstall',
        'initialize',
        'config_form',
        'config',
        'admin_items_show',
        'public_items_show',
    );
    
    protected $_options = array(
        'docsviewer_embed_admin' => self::DEFAULT_VIEWER_EMBED,
        'docsviewer_width_admin' => self::DEFAULT_VIEWER_WIDTH,
        'docsviewer_height_admin' => self::DEFAULT_VIEWER_HEIGHT,
        'docsviewer_embed_public' => self::DEFAULT_VIEWER_EMBED,
        'docsviewer_width_public' => self::DEFAULT_VIEWER_WIDTH,
        'docsviewer_height_public' => self::DEFAULT_VIEWER_HEIGHT,
    );
    
    /**
     * Install the plugin.
     */
    public function hookInstall()
    {
        $this->_installOptions();
    }
    
    /**
     * Uninstall the plugin.
     */
    public function hookUninstall()
    {
        $this->_uninstallOptions();
    }
    
    /**
     * Initialize the plugin.
     */
    public function hookInitialize()
    {
        // Add translation.
        add_translation_source(dirname(__FILE__) . '/languages');
    }
    
    /**
     * Display the config form.
     */
    public function hookConfigForm()
    {
        echo get_view()->partial('plugins/docs-viewer-config-form.php');
    }
    
    /**
     * Handle the config form.
     */
    public function hookConfig()
    {
        if (!is_numeric($_POST['docsviewer_width_admin']) || 
            !is_numeric($_POST['docsviewer_height_admin']) || 
            !is_numeric($_POST['docsviewer_width_public']) || 
            !is_numeric($_POST['docsviewer_height_public'])) {
            throw new Omeka_Validate_Exception('The width and height must be numeric.');
        }
        set_option('docsviewer_embed_admin', (int) (boolean) $_POST['docsviewer_embed_admin']);
        set_option('docsviewer_width_admin', $_POST['docsviewer_width_admin']);
        set_option('docsviewer_height_admin', $_POST['docsviewer_height_admin']);
        set_option('docsviewer_embed_public', (int) (boolean) $_POST['docsviewer_embed_public']);
        set_option('docsviewer_width_public', $_POST['docsviewer_width_public']);
        set_option('docsviewer_height_public', $_POST['docsviewer_height_public']);
    }
    
    /**
     * Display the document viewer in admin items/show.
     */
    public function hookAdminItemsShow($args)
    {
        // Embed viewer only if configured to do so.
        if (!get_option('docsviewer_embed_admin')) {
            return;
        }
        echo $args['view']->docsViewer($args['item']->Files, 
                                       get_option('docsviewer_width_admin'), 
                                       get_option('docsviewer_height_admin'));
    }
    
    /**
     * Display the document viewer in public items/show.
     */
    public function hookPublicItemsShow($args)
    {
        // Embed viewer only if configured to do so.
        if (!get_option('docsviewer_embed_public')) {
            return;
        }
        echo $args['view']->docsViewer($args['item']->Files, 
                                       get_option('docsviewer_width_public'), 
                                       get_option('docsviewer_height_public'));
    }
}
