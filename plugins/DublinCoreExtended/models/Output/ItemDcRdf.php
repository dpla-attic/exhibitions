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
class Output_ItemDcRdf
{
    protected $_elements;
    
    public function itemToDcRdf(Item $item)
    {
        $dces = new DublinCoreExtendedPlugin;
        $xml = '<rdf:Description rdf:about="' . html_escape(record_url($item, 'show', true)) . '">';
        foreach ($dces->getElements() as $element) {
            $elementTexts = metadata($item, array('Dublin Core', $element['label']), array('all' => true));
            if ($elementTexts) {
                foreach ($elementTexts as $elementText) {
                    if (strlen($elementText) != 0) {
                        $xml .= "\n    <dcterms:{$element['name']}><![CDATA[$elementText]]></dcterms:{$element['name']}>" ;
                    }
                }
            }
        }
        $xml .= "\n</rdf:Description>";
        return $xml;
    }
}
