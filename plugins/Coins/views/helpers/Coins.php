<?php
/**
 * COinS
 *
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * @package Coins\View\Helper
 */
class Coins_View_Helper_Coins extends Zend_View_Helper_Abstract
{
    /**
     * Return a COinS span tag for every passed item.
     *
     * @param array|Item An array of item records or one item record.
     * @return string
     */
    public function coins($items)
    {
        if (!is_array($items)) {
            return $this->_getCoins($items);
        }

        $coins = '';
        foreach ($items as $item) {
            $coins .= $this->_getCoins($item);
            release_object($item);
        }
        return $coins;
    }

    /**
     * Build and return the COinS span tag for the specified item.
     *
     * @param Item $item
     * @return string
     */
    protected function _getCoins(Item $item)
    {
        $coins = array();

        $coins['ctx_ver'] = 'Z39.88-2004';
        $coins['rft_val_fmt'] = 'info:ofi/fmt:kev:mtx:dc';
        $coins['rfr_id'] = 'info:sid/omeka.org:generator';

        // Set the Dublin Core elements that don't need special processing.
        $elementNames = array('Creator', 'Subject', 'Publisher', 'Contributor',
                              'Date', 'Format', 'Source', 'Language', 'Coverage',
                              'Rights', 'Relation');
        foreach ($elementNames as $elementName) {
            $elementText = $this->_getElementText($item, $elementName);
            if (false === $elementText) {
                continue;
            }

            $elementName = strtolower($elementName);
            $coins["rft.$elementName"] = $elementText;
        }

        // Set the title key from Dublin Core:title.
        $title = $this->_getElementText($item, 'Title');
        if (false === $title || '' == trim($title)) {
            $title = '[unknown title]';
        }
        $coins['rft.title'] = $title;

        // Set the description key from Dublin Core:description.
        $description = $this->_getElementText($item, 'Description');
        if (false === $description) {
            return;
        }
        $coins['rft.description'] = $description;

        // Set the type key from item type, map to Zotero item types.
        $itemTypeName = metadata($item, 'item type name');
        switch ($itemTypeName) {
            case 'Oral History':
                $type = 'interview';
                break;
            case 'Moving Image':
                $type = 'videoRecording';
                break;
            case 'Sound':
                $type = 'audioRecording';
                break;
            case 'Email':
                $type = 'email';
                break;
            case 'Website':
                $type = 'webpage';
                break;
            case 'Text':
            case 'Document':
                $type = 'document';
                break;
            default:
                if ($itemTypeName) {
                    $type = $itemTypeName;
                } else {
                    $type = $this->_getElementText($item, 'Type');
                }
        }
        $coins['rft.type'] = $type;

        // Set the identifier key as the absolute URL of the current page.
        $coins['rft.identifier'] = absolute_url();

        // Build and return the COinS span tag.
        $coinsSpan = '<span class="Z3988" title="';
        $coinsSpan .= html_escape(http_build_query($coins));
        $coinsSpan .= '"></span>';
        return $coinsSpan;
    }

    /**
     * Get the unfiltered element text for the specified item.
     *
     * @param Item $item
     * @param string $elementName
     * @return string|bool
     */
    protected function _getElementText(Item $item, $elementName)
    {
        $elementText = metadata(
            $item,
            array('Dublin Core', $elementName),
            array('no_filter' => true, 'no_escape' => true, 'snippet' => 500)
        );
        return $elementText;
    }
}
