<?php
/**
 * @package OaiPmhRepository
 * @subpackage MetadataFormats
 * @copyright Copyright 2009-2014 John Flatness
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Class implmenting MODS metadata output format.
 *
 * @link http://www.loc.gov/standards/mods/
 * @package OaiPmhRepository
 * @subpackage Metadata Formats
 */
class OaiPmhRepository_Metadata_Mods implements OaiPmhRepository_Metadata_FormatInterface
{
    /** OAI-PMH metadata prefix */
    const METADATA_PREFIX = 'mods';    
    
    /** XML namespace for output format */
    const METADATA_NAMESPACE = 'http://www.loc.gov/mods/v3';
    
    /** XML schema for output format */
    const METADATA_SCHEMA = 'http://www.loc.gov/standards/mods/v3/mods-3-3.xsd';
    
    /**
     * Appends MODS metadata. 
     *
     * Appends a metadata element, an child element with the required format,
     * and further children for each of the Dublin Core fields present in the
     * item.
     *
     * @link http://www.loc.gov/standards/mods/dcsimple-mods.html
     */
    public function appendMetadata($item, $metadataElement)
    {
        $document = $metadataElement->ownerDocument;
        $mods = $document->createElementNS(
            self::METADATA_NAMESPACE, 'mods');
        $metadataElement->appendChild($mods);

        $mods->declareSchemaLocation(self::METADATA_NAMESPACE, self::METADATA_SCHEMA);
            
        $titles = $item->getElementTexts( 'Dublin Core','Title');
        foreach($titles as $title)
        {
            $titleInfo = $mods->appendNewElement('titleInfo');
            $titleInfo->appendNewElement('title', $title->text);
        }
        
        $creators = $item->getElementTexts('Dublin Core','Creator');
        foreach($creators as $creator)
        {
            $name = $mods->appendNewElement('name');
            $name->appendNewElement('namePart', $creator->text);
            $role = $name->appendNewElement('role');
            $roleTerm = $role->appendNewElement('roleTerm', 'creator');
            $roleTerm->setAttribute('type', 'text');
        }
        
        $contributors = $item->getElementTexts('Dublin Core','Contributor');
        foreach($contributors as $contributor)
        {
            $name = $mods->appendNewElement('name');
            $name->appendNewElement('namePart', $contributor->text);
            $role = $name->appendNewElement('role');
            $roleTerm = $role->appendNewElement('roleTerm', 'contributor');
            $roleTerm->setAttribute('type', 'text');
        }
        
        $subjects = $item->getElementTexts('Dublin Core','Subject');
        foreach($subjects as $subject)
        {
            $subjectTag = $mods->appendNewElement('subject');
            $subjectTag->appendNewElement('topic', $subject->text);
        }
        
        $descriptions = $item->getElementTexts('Dublin Core','Description');
        foreach($descriptions as $description)
        {
            $mods->appendNewElement('note', $description->text);
        }
        
        $formats = $item->getElementTexts('Dublin Core','Format');
        foreach($formats as $format)
        {
            $physicalDescription = $mods->appendNewElement('physicalDescription');
            $physicalDescription->appendNewElement('form', $format->text);
        }
        
        $languages = $item->getElementTexts('Dublin Core','Language');
        foreach($languages as $language)
        {
            $languageElement = $mods->appendNewElement('language');
            $languageTerm = $languageElement->appendNewElement('languageTerm', $language->text);
            $languageTerm->setAttribute('type', 'text');
        }
        
        $rights = $item->getElementTexts('Dublin Core','Rights');
        foreach($rights as $right)
        {
            $mods->appendNewElement('accessCondition', $right->text);
        }

        // Prepend the item type, if any.
        if (get_option('oaipmh_repository_expose_item_type') && $dcType = $item->getProperty('item_type_name')) {
            $mods->appendNewElement('genre', $dcType);
        }

        $types = $item->getElementTexts('Dublin Core','Type');
        foreach ($types as $type)
        {
            $mods->appendNewElement('genre', $type->text);
        }


        $identifiers = $item->getElementTexts( 'Dublin Core','Identifier');
        foreach ($identifiers as $identifier)
        {
            $text = $identifier->text;
            $idElement = $mods->appendNewElement('identifier', $text);
            if ($this->_isUrl($text)) {
                $idElement->setAttribute('type', 'uri');
            } else {
                $idElement->setAttribute('type', 'local');
            }
        }

        $sources = $item->getElementTexts('Dublin Core','Source');
        foreach ($sources as $source)
        {
            $this->_addRelatedItem($mods, $source->text, true);
        }

        $relations = $item->getElementTexts('Dublin Core','Relation');
        foreach ($relations as $relation)
        {
            $this->_addRelatedItem($mods, $relation->text);
        }

        $location = $mods->appendNewElement('location');
        $url = $location->appendNewElement('url', record_url($item, 'show', true));
        $url->setAttribute('usage', 'primary display');

        $publishers = $item->getElementTexts('Dublin Core','Publisher');
        $dates = $item->getElementTexts('Dublin Core','Date');

        // Empty originInfo sections are illegal
        if(count($publishers) + count($dates) > 0) 
        {
            $originInfo = $mods->appendNewElement('originInfo');
        
            foreach($publishers as $publisher)
            {
                $originInfo->appendNewElement('publisher', $publisher->text);
            }

            foreach($dates as $date)
            {
                $originInfo->appendNewElement('dateOther', $date->text);
            }
        }
        
        $recordInfo = $mods->appendNewElement('recordInfo');
        $recordInfo->appendNewElement('recordIdentifier', $item->id);
    }

    /**
     * Add a relatedItem element.
     *
     * Checks the $text to see if it looks like a URL, and creates a
     * location subelement if so. Otherwise, a titleInfo is used.
     *
     * @param DomElement $mods
     * @param string $text
     * @param bool $original
     */
    private function _addRelatedItem($mods, $text, $original = false)
    {
        $relatedItem = $mods->appendNewElement('relatedItem');
        if ($this->_isUrl($text)) {
            $titleInfo = $relatedItem->appendNewElement('titleInfo');
            $titleInfo->appendNewElement('title', $text);
        } else {
            $location = $relatedItem->appendNewElement('location');
            $location->appendNewElement('url', $text);
        }
        if ($original) {
            $relatedItem->setAttribute('type', 'original');
        }
    }

    /**
     * Returns whether the given test is (looks like) a URL.
     *
     * @param string $text
     * @return bool
     */
    private function _isUrl($text)
    {
        return strncmp($text, 'http://', 7) || strncmp($text, 'https://', 8);
    }
}
