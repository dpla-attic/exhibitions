<?php
/**
 * @package OaiPmhRepository
 * @subpackage MetadataFormats
 * @copyright Copyright 2012-2014 John Flatness
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Class implmenting MODS metadata output format.
 *
 * @link http://www.loc.gov/standards/mods/
 * @package OaiPmhRepository
 * @subpackage Metadata Formats
 */
class OaiPmhRepository_Metadata_Mets implements OaiPmhRepository_Metadata_FormatInterface
{
    /** OAI-PMH metadata prefix */
    const METADATA_PREFIX = 'mets';

    /** XML namespace for output format */
    const METADATA_NAMESPACE = 'http://www.loc.gov/METS/';

    /** XML schema for output format */
    const METADATA_SCHEMA = 'http://www.loc.gov/standards/mets/mets.xsd';

    /** XML namespace for unqualified Dublin Core */
    const DC_NAMESPACE_URI = 'http://purl.org/dc/elements/1.1/';

    /**
     * Appends MODS metadata.
     *
     * Appends a metadata element, an child element with the required format,
     * and further children for each of the Dublin Core fields present in the
     * item.
     */
    public function appendMetadata($item, $metadataElement)
    {
        $document = $metadataElement->ownerDocument;
        $mets = $document->createElementNS(
            self::METADATA_NAMESPACE, 'mets');
        $metadataElement->appendChild($mets);

        $mets->declareSchemaLocation(self::METADATA_NAMESPACE, self::METADATA_SCHEMA);

        $mets->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');

        $metadataSection = $mets->appendNewElement('dmdSec');
        $itemDmdId = 'dmd-' . $item->id;
        $metadataSection->setAttribute('ID', $itemDmdId);
        $dcWrap = $metadataSection->appendNewElement('mdWrap');
        $dcWrap->setAttribute('MDTYPE', 'DC');       
        
        
        $dcXml = $dcWrap->appendNewElement('xmlData');
        $dcXml->setAttribute('xmlns:dc', self::DC_NAMESPACE_URI);

        $dcElementNames = array( 'title', 'creator', 'subject', 'description',
                                 'publisher', 'contributor', 'date', 'type',
                                 'format', 'identifier', 'source', 'language',
                                 'relation', 'coverage', 'rights' );

        foreach($dcElementNames as $elementName)
        {
            $upperName = Inflector::camelize($elementName);
            $dcElements = $item->getElementTexts(
               'Dublin Core', $upperName);

            // Prepend the item type, if any.
            if ($elementName == 'type' && get_option('oaipmh_repository_expose_item_type')) {
                if ($dcType = $item->getProperty('item_type_name')) {
                    $dcXml->appendNewElement('dc:type', $dcType);
                }
            }

            foreach($dcElements as $elementText)
            {
                $dcXml->appendNewElement('dc:'.$elementName, $elementText->text);
            }
        }
        $fileIds = array();
        if (get_option('oaipmh_repository_expose_files') && metadata($item, 'has files')) {
            $fileSection = $mets->appendNewElement('fileSec');
            $fileGroup = $fileSection->appendNewElement('fileGrp');
            $fileGroup->setAttribute('USE', 'ORIGINAL');
            
            foreach ($item->getFiles() as $file) {               
                $fileDmdId = "dmd-file-" . $file->id;
                $fileId = 'file-' . $file->id;
                
                $fileElement = $fileGroup->appendNewElement('file');
                $fileElement->setAttribute('xmlns:dc', self::DC_NAMESPACE_URI);
                $fileElement->setAttribute('ID', $fileId);
                $fileElement->setAttribute('MIMETYPE', $file->mime_type);
                $fileElement->setAttribute('CHECKSUM', $file->authentication);
                $fileElement->setAttribute('CHECKSUMTYPE', 'MD5');
                $fileElement->setAttribute('DMDID', $fileDmdId);
                     
                $location = $fileElement->appendNewElement('FLocat');
                
                $location->setAttribute('LOCTYPE', 'URL');
                $location->setAttribute('xlink:type', 'simple');
                $location->setAttribute('xlink:title', $file->original_filename);
                $location->setAttribute('xlink:href',$file->getWebPath('original'));              
               
                $fileContentMetadata = $mets->appendNewElement('dmdSec');
                $fileContentMetadata->setAttribute('ID' , $fileDmdId);
                
                $fileDcWrap = $fileContentMetadata->appendNewElement('mdWrap');
                $fileDcWrap->setAttribute('MDTYPE', 'DC');         
                
                $fileDcXml = $fileDcWrap->appendNewElement('xmlData');
                $fileDcXml->setAttribute('xmlns:dc', self::DC_NAMESPACE_URI);
                
                $fileIds[] = $fileId;
                        
                foreach($dcElementNames as $elementName)
                {
                    $upperName = Inflector::camelize($elementName);
                    $dcElements = metadata($file,array('Dublin Core',$upperName));
                     
                    if(isset($dcElements)){
                        $fileDcXml->appendNewElement('dc:'.$elementName, $dcElements);
                    }
                }

                release_object($file);
            }
        }

        $structMap = $mets->appendNewElement('structMap');
        $topDiv = $structMap->appendNewElement('div');
        $topDiv->setAttribute('DMDID', $itemDmdId);
        foreach($fileIds as $id){
            $fptr = $topDiv->appendNewElement('fptr');
            $fptr->setAttribute('FILEID', $id);
        }
    }
    
    protected function getFileMetadata($file)
    {
        $db = get_db()->getTable('ElementTexts');
        return $db->findBy(array('record_type'=>'file','record_id' => $file->id));
    }
}
