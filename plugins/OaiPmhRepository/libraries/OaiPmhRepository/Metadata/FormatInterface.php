<?php
/**
 * @package OaiPmhRepository
 * @subpackage MetadataFormats
 * @author John Flatness, Yu-Hsun Lin
 * @copyright Copyright 2009-2014 John Flatness, Yu-Hsun Lin
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Interface on which all other metadata format handlers are based.
 *
 * @package OaiPmhRepository
 * @subpackage Metadata Formats
 */
interface OaiPmhRepository_Metadata_FormatInterface
{    
    /**
     * Appends the metadata for one Omeka item to the XML document.
     *
     * @param Item $item
     * @param DOMElement $parentElement
     */
    public function appendMetadata($item, $parentElement);
}
