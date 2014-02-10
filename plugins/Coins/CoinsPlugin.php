<?php
/**
 * COinS
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The COinS plugin.
 * 
 * @package Omeka\Plugins\Coins
 */
class CoinsPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'public_items_show',
        'admin_items_show',
        'public_items_browse_each',
        'admin_items_browse_simple_each',
    );
    
    /**
     * Print out the COinS span on the public items show page.
     */
    public function hookPublicItemsShow()
    {
        echo get_view()->coins(get_current_record('item'));
    }
    
    /**
     * Print out the COinS span on the admin items show page.
     */
    public function hookAdminItemsShow()
    {
        echo get_view()->coins(get_current_record('item'));
    }
    
    /**
     * Print out the COinS span on the public items browse page.
     */
    public function hookPublicItemsBrowseEach()
    {
        echo get_view()->coins(get_current_record('item'));
    }
    
    /**
     * Print out the COinS span on the admin items browse page.
     */
    public function hookAdminItemsBrowseSimpleEach()
    {
        echo get_view()->coins(get_current_record('item'));
    }
}
