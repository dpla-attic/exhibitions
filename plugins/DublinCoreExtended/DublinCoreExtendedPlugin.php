<?php
/**
 * Dublin Core Extended
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The Dublin Core Extended plugin.
 * 
 * @package Omeka\Plugins\DublinCoreExtended
 */
class DublinCoreExtendedPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'install', 
        'uninstall', 
        'uninstall_message', 
        'upgrade', 
        'initialize', 
    );
    
    protected $_filters = array(
        'response_contexts', 
        'action_contexts', 
    );
    
    private $_elements;
    
    private $_dcElements = array(
        'Title', 'Subject', 'Description', 'Creator', 'Source', 'Publisher', 
        'Date', 'Contributor', 'Rights', 'Relation', 'Format', 'Language', 
        'Type', 'Identifier', 'Coverage', 
    );
    
    public function __construct()
    {
        parent::__construct();
        
        // Set the elements.
        include 'elements.php';
        $this->_elements = $elements;
    }
    
    /**
     * Install the plugin.
     */
    public function hookInstall()
    {
        // Add the new elements to the Dublin Core element set. 
        $elementSet = $this->_db->getTable('ElementSet')->findByName('Dublin Core');
        foreach ($this->_elements as $key => $element) {
            if (!in_array($element['label'], $this->_dcElements)) {
                $sql = "
                INSERT INTO `{$this->_db->Element}` (`element_set_id`, `name`, `description`) 
                VALUES (?, ?, ?)";
                $this->_db->query($sql, array($elementSet->id, $element['label'], $element['description']));
            }
        }
    }
    
    /**
     * Uninstall the plugin.
     */
    public function hookUninstall()
    {
        // Delete all the elements and element texts.
        $elementTable = $this->_db->getTable('Element');
        foreach ($this->_elements as $element) {
            if (!in_array($element['label'], $this->_dcElements)) {
                $elementTable->findByElementSetNameAndElementName('Dublin Core', $element['label'])->delete();
            }
        }
    }
    
    /**
     * Display the uninstall message.
     */
    public function hookUninstallMessage()
    {
        echo __('%sWarning%s: This will remove all the Dublin Core elements added ' 
        . 'by this plugin and permanently delete all element texts entered in those ' 
        . 'fields.%s', '<p><strong>', '</strong>', '</p>');
    }
    
    /**
     * Upgrade this plugin.
     * 
     * @param array $args
     */
    public function hookUpgrade($args)
    {
        // Drop the unused dublin_core_extended_relationships table.
        if (version_compare($args['old_version'], '2.0', '<')) {
            $sql = "DROP TABLE IF EXISTS `{$this->_db->DublinCoreExtendedRelationship}`";
            $this->_db->query($sql);
        }
    }
    
    /**
     * Initialize this plugin.
     */
    public function hookInitialize()
    {
        // Add translation.
        add_translation_source(dirname(__FILE__) . '/languages');
    }
    
    /**
     * Add the dc-rdf response context.
     * 
     * @param array $contexts
     * @return array
     */
    public function filterResponseContexts($contexts)
    {
        $contexts['dc-rdf'] = array('suffix' => 'dc-rdf', 
                                    'headers' => array('Content-Type' => 'text/xml'));
        return $contexts;
    }
    
    /**
     * Add the dc-rdf response context to items/browse and items/show.
     * 
     * @param array $contexts
     * @param array $args
     * @return array
     */
    public function filterActionContexts($contexts, $args)
    {
        if ($args['controller'] instanceof ItemsController) {
            $contexts['browse'][] = 'dc-rdf';
            $contexts['show'][] = 'dc-rdf';
        }
        return $contexts;
    }
    
    /**
     * Get the dublin core extended elements array.
     * 
     * @return array
     */
    public function getElements()
    {
        return $this->_elements;
    }
}
