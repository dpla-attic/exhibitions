<?php
/**
 * @package OaiPmhRepository
 * @subpackage MetadataFormats
 * @author John Flatness
 * @copyright Copyright 2009 John Flatness
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */


/**
 * Class implmenting metadata output CDWA Lite.
 *
 * @link http://www.getty.edu/research/conducting_research/standards/cdwa/cdwalite.html
 * @package OaiPmhRepository
 * @subpackage Metadata Formats
 */
class OaiPmhRepository_Metadata_CdwaLite implements OaiPmhRepository_Metadata_FormatInterface
{
    /** OAI-PMH metadata prefix */
    const METADATA_PREFIX = 'cdwalite';    
    
    /** XML namespace for output format */
    const METADATA_NAMESPACE = 'http://www.getty.edu/CDWA/CDWALite';
    
    /** XML schema for output format */
    const METADATA_SCHEMA = 'http://www.getty.edu/CDWA/CDWALite/CDWALite-xsd-public-v1-1.xsd';
    
    /**
     * Appends CDWALite metadata. 
     *
     * Appends a metadata element, an child element with the required format,
     * and further children for each of the Dublin Core fields present in the
     * item.
     */
    public function appendMetadata($item, $metadataElement)
    {
        $document = $metadataElement->ownerDocument;
        $cdwaliteWrap = $document->createElementNS(
            self::METADATA_NAMESPACE, 'cdwalite:cdwaliteWrap');
        $metadataElement->appendChild($cdwaliteWrap);

        $cdwaliteWrap->setAttribute('xmlns:cdwalite', self::METADATA_NAMESPACE);
        $cdwaliteWrap->declareSchemaLocation(self::METADATA_NAMESPACE, self::METADATA_SCHEMA);
            
        $cdwalite = $cdwaliteWrap->appendNewElement('cdwalite:cdwalite');
        
        /* ====================
         * DESCRIPTIVE METADATA
         * ====================
         */

        $descriptive = $cdwalite->appendNewElement('cdwalite:descriptiveMetadata');
        
        /* Type => objectWorkTypeWrap->objectWorkType 
         * Required.  Fill with 'Unknown' if omitted.
         */
        $objectWorkTypeWrap = $descriptive->appendNewElement('cdwalite:objectWorkTypeWrap');
        if (get_option('oaipmh_repository_expose_item_type') && $dcType = $item->getProperty('item_type_name')) {
            $objectWorkTypeWrap->appendNewElement('cdwalite:objectWorkTypeWrap', $dcType);
        }
        $types = $item->getElementTexts('Dublin Core','Type');
        //print_r($objectWorkTypeWrap);
        if (empty($dcType) && count($types) == 0) $types[] = 'Unknown';
        foreach($types as $type)
        {  
            $objectWorkTypeWrap->appendNewElement('cdwalite:objectWorkTypeWrap', ($type == 'Unknown')? $type: $type->text );

        }
        
        /* Title => titleWrap->titleSet->title
         * Required.  Fill with 'Unknown' if omitted.
         */        
        $titles = $item->getElementTexts('Dublin Core','Title');
        $titleWrap = $descriptive->appendNewElement('cdwalite:titleWrap');
        if(count($types) == 0) $types[] = 'Unknown';
        foreach($titles as $title)
        {
            $titleSet = $titleWrap->appendNewElement('cdwalite:titleSet');
            $titleSet->appendNewElement('cdwalite:title', $title->text);
        }

        /* Creator => displayCreator
         * Required.  Fill with 'Unknown' if omitted.
         * Non-repeatable, implode for inclusion of many creators.
         */
        $creators = $item->getElementTexts('Dublin Core','Creator');

        $creatorTexts = array();
        foreach($creators as $creator) $creatorTexts[] = $creator->text;
        if (count($creatorTexts) == 0) $creatorTexts[] = 'Unknown';
        
        $creatorText = implode(', ', $creatorTexts);
        $descriptive->appendNewElement('cdwalite:displayCreator', $creatorText);
        
        /* Creator => indexingCreatorWrap->indexingCreatorSet->nameCreatorSet->nameCreator
         * Required.  Fill with 'Unknown' if omitted.
         * Also include roleCreator, fill with 'Unknown', required.
         */
        $indexingCreatorWrap = $descriptive->appendNewElement('cdwalite:indexingCreatorWrap');
        foreach($creatorTexts as $creator) 
        {
            $indexingCreatorSet = $indexingCreatorWrap->appendNewElement('cdwalite:indexingCreatorSet');
            $nameCreatorSet = $indexingCreatorSet->appendNewElement('cdwalite:nameCreatorSet');
            $nameCreatorSet->appendNewElement('cdwalite:nameCreator', $creator);
            $indexingCreatorSet->appendNewElement('cdwalite:roleCreator', 'Unknown');
        }
        
        /* displayMaterialsTech
         * Required.  No corresponding metadata, fill with 'not applicable'.
         */
        $descriptive->appendNewElement('cdwalite:displayMaterialsTech', 'not applicable');
        
        /* Date => displayCreationDate
         * Required. Fill with 'Unknown' if omitted.
         * Non-repeatable, include only first date.
         */
        $dates = $item->getElementTexts('Dublin Core','Date');
        $dateText = count($dates) > 0 ? $dates[0]->text : 'Unknown';
        $descriptive->appendNewElement('cdwalite:displayCreationDate', $dateText);
        
        /* Date => indexingDatesWrap->indexingDatesSet
         * Map to both earliest and latest date
         * Required.  Fill with 'Unknown' if omitted.
         */
        $indexingDatesWrap = $descriptive->appendNewElement('cdwalite:indexingDatesWrap');   
        foreach($dates as $date)
        {
            $indexingDatesSet = $indexingDatesWrap->appendNewElement('cdwalite:indexingDatesSet');
            $indexingDatesSet->appendNewElement('cdwalite:earliestDate', $date->text);
            $indexingDatesSet->appendNewElement('cdwalite:latestDate', $date->text);
        }
        
        /* locationWrap->locationSet->locationName
         * Required. No corresponding metadata, fill with 'location unknown'.
         */
        $locationWrap = $descriptive->appendNewElement('cdwalite:locationWrap');
        $locationSet = $locationWrap->appendNewElement('cdwalite:locationSet');
        $locationSet->appendNewElement('cdwalite:locationName', 'location unknown');

        /* Subject => classWrap->classification
         * Not required.
         */
        $subjects = $item->getElementTexts('Dublin Core','Subject');
        $classWrap = $descriptive->appendNewElement('cdwalite:classWrap');
        foreach($subjects as $subject)
        {
            $classWrap->appendNewElement('cdwalite:classification', $subject->text);
        }
        
        /* Description => descriptiveNoteWrap->descriptiveNoteSet->descriptiveNote
         * Not required.
         */
        $descriptions = $item->getElementTexts('Dublin Core','Description');
        if(count($descriptions) > 0)
        {
            $descriptiveNoteWrap = $descriptive->appendNewElement('cdwalite:descriptiveNoteWrap');
            foreach($descriptions as $description)
            {
                $descriptiveNoteSet = $descriptiveNoteWrap->appendNewElement('cdwalite:descriptiveNoteSet');
                $descriptiveNoteSet->appendNewElement('cdwalite:descriptiveNote', $description->text);
            }
        }
        
        /* =======================
         * ADMINISTRATIVE METADATA
         * =======================
         */
         
        $administrative = $cdwalite->appendNewElement('cdwalite:administrativeMetadata');
        
        /* Rights => rightsWork
         * Not required.
         */
        $rights = $item->getElementTexts('Dublin Core','Rights');
        foreach($rights as $right)
        {
            $administrative->appendNewElement('cdwalite:rightsWork', $right->text);
        }
        
        /* id => recordWrap->recordID
         * 'item' => recordWrap-recordType
         * Required.
         */     
        $recordWrap = $administrative->appendNewElement('cdwalite:recordWrap');
        $recordWrap->appendNewElement('cdwalite:recordID', $item->id);
        $recordWrap->appendNewElement('cdwalite:recordType', 'item');
        $recordInfoWrap = $recordWrap->appendNewElement('cdwalite:recordInfoWrap');
        $recordInfoID = $recordInfoWrap->appendNewElement('cdwalite:recordInfoID', OaiPmhRepository_OaiIdentifier::itemToOaiId($item->id));
        $recordInfoID->setAttribute('cdwalite:type', 'oai');
        
        /* file link => resourceWrap->resourceSet->linkResource
         * Not required.
         */
        if(get_option('oaipmh_repository_expose_files')) {
            $files = $item->getFiles();
            if(count($files) > 0) {
                $resourceWrap = $administrative->appendNewElement('cdwalite:resourceWrap');
                foreach($files as $file) 
                {
                    $resourceSet = $resourceWrap->appendNewElement('cdwalite:resourceSet');
                    $resourceSet->appendNewElement('cdwalite:linkResource', $file->getWebPath('original'));
                }
            }
        }
    }
}
