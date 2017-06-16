<?php
/**
 * @package OaiPmhRepository
 * @subpackage Libraries
 * @copyright Copyright 2009-2014 John Flatness, Yu-Hsun Lin
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * OaiPmhRepository_ResponseGenerator generates the XML responses to OAI-PMH
 * requests recieved by the repository.  The DOM extension is used to generate
 * all the XML output on-the-fly.
 *
 * @package OaiPmhRepository
 * @subpackage Libraries
 */
class OaiPmhRepository_ResponseGenerator extends OaiPmhRepository_OaiXmlGeneratorAbstract
{
    /**
     * HTTP query string or POST vars formatted as an associative array.
     * @var array
     */
    private $query;
    
    /**
     * Array of all supported metadata formats.
     * $metdataFormats['metadataPrefix'] = ImplementingClassName
     * @var array
     */
    private $metadataFormats;

    private $_listLimit;

    private $_tokenExpirationTime;

    /**
     * Constructor
     *
     * Creates the DomDocument object, and adds XML elements common to all
     * OAI-PMH responses.  Dispatches control to appropriate verb, if any.
     *
     * @param array $query HTTP POST/GET query key-value pair array.
     * @uses dispatchRequest()
     */
    public function __construct($query)
    {
        $this->_loadConfig();

        $this->error = false;
        $this->query = $query;
        $this->document = new DomDocument('1.0', 'UTF-8');
        $this->document->registerNodeClass('DOMElement',
            'OaiPmhRepository_DOMElement');
        
        OaiPmhRepository_OaiIdentifier::initializeNamespace(get_option('oaipmh_repository_namespace_id'));
        
        //formatOutput makes DOM output "pretty" XML.  Good for debugging, but
        //adds some overhead, especially on large outputs.
        $this->document->formatOutput = true;
        $this->document->xmlStandalone = true;
        
        $root = $this->document->createElementNS(self::OAI_PMH_NAMESPACE_URI,
            'OAI-PMH');
        $this->document->appendChild($root);
        
        $root->declareSchemaLocation(self::OAI_PMH_NAMESPACE_URI, self::OAI_PMH_SCHEMA_URI);
    
        $responseDate = $this->document->createElement('responseDate', 
            OaiPmhRepository_Date::unixToUtc(time()));
        $root->appendChild($responseDate);
        
        $this->metadataFormats = $this->getFormats();
        
        $this->dispatchRequest();
    }

    private function _loadConfig()
    {
        $iniFile = OAI_PMH_REPOSITORY_PLUGIN_DIRECTORY
                 . '/config.ini';

        $ini = new Zend_Config_Ini($iniFile, 'oai-pmh-repository');

        $this->_listLimit = $ini->list_limit;
        $this->_tokenExpirationTime = $ini->token_expiration_time;
    }
    
    /**
     * Parses the HTTP query and dispatches to the correct verb handler.
     *
     * Checks arguments for each verb type, and sets XML request tag.
     *
     * @uses checkArguments()
     */
    private function dispatchRequest()
    {
        $request = $this->document->createElement('request',
            OAI_PMH_BASE_URL);
        $this->document->documentElement->appendChild($request);
        
        $requiredArgs = array();
        $optionalArgs = array();
        if (!($verb = $this->_getParam('verb'))) {
            $this->throwError(self::OAI_ERR_BAD_VERB, 'No verb specified.');
            return;
        }
        $resumptionToken = $this->_getParam('resumptionToken');
        
        if($resumptionToken)
            $requiredArgs = array('resumptionToken');
        else
            switch($this->query['verb'])
            {
                case 'Identify':
                    break;
                case 'GetRecord':
                    $requiredArgs = array('identifier', 'metadataPrefix');
                    break;
                case 'ListRecords':
                    $requiredArgs = array('metadataPrefix');
                    $optionalArgs = array('from', 'until', 'set');
                    break;
                case 'ListIdentifiers':
                    $requiredArgs = array('metadataPrefix');
                    $optionalArgs = array('from', 'until', 'set');
                    break;                
                case 'ListSets':
                    break;
                case 'ListMetadataFormats':
                    $optionalArgs = array('identifier');
                    break;
                default:
                    $this->throwError(self::OAI_ERR_BAD_VERB);
            }
        
        $this->checkArguments($requiredArgs, $optionalArgs);
        
        if(!$this->error) {
            foreach($this->query as $key => $value)
                $request->setAttribute($key, $value);
                
            if($resumptionToken)
                $this->resumeListResponse($resumptionToken);
            /* ListRecords and ListIdentifiers use a common code base and share
               all possible arguments, and are handled by one function. */
            else if($verb == 'ListRecords' || $verb == 'ListIdentifiers')
                $this->initListResponse();
            else {
                /* This Inflector use means verb-implementing functions must be
                   the lowerCamelCased version of the verb name. */
                $functionName = Inflector::variablize($verb);
                $this->$functionName();
            }
        }
    }
    
    /**
     * Checks the argument list from the POST/GET query.
     *
     * Checks if the required arguments are present, and no invalid extra
     * arguments are present.  All valid arguments must be in either the
     * required or optional array.
     *
     * @param array requiredArgs Array of required argument names.
     * @param array optionalArgs Array of optional, but valid argument names.
     */
    private function checkArguments($requiredArgs = array(), $optionalArgs = array())
    {
        $requiredArgs[] = 'verb';
        
        /* Checks (essentially), if there are more arguments in the query string
           than in PHP's returned array, if so there were duplicate arguments,
           which is not allowed. */
        if($_SERVER['REQUEST_METHOD'] == 'GET' && (urldecode($_SERVER['QUERY_STRING']) != urldecode(http_build_query($this->query))))
            $this->throwError(self::OAI_ERR_BAD_ARGUMENT, "Duplicate arguments in request.");
        
        $keys = array_keys($this->query);
        
        foreach(array_diff($requiredArgs, $keys) as $arg)
            $this->throwError(self::OAI_ERR_BAD_ARGUMENT, "Missing required argument $arg.");
        foreach(array_diff($keys, $requiredArgs, $optionalArgs) as $arg)
            $this->throwError(self::OAI_ERR_BAD_ARGUMENT, "Unknown argument $arg.");
                
        $from = $this->_getParam('from');
        $until = $this->_getParam('until');
        
        $fromGran = OaiPmhRepository_Date::getGranularity($from);
        $untilGran = OaiPmhRepository_Date::getGranularity($until);
        
        if($from && !$fromGran)
            $this->throwError(self::OAI_ERR_BAD_ARGUMENT, "Invalid date/time argument.");
        if($until && !$untilGran)
            $this->throwError(self::OAI_ERR_BAD_ARGUMENT, "Invalid date/time argument.");
        if($from && $until && $fromGran != $untilGran)
            $this->throwError(self::OAI_ERR_BAD_ARGUMENT, "Date/time arguments of differing granularity.");
                
        $metadataPrefix = $this->_getParam('metadataPrefix');
        
        if($metadataPrefix && !array_key_exists($metadataPrefix, $this->metadataFormats))
            $this->throwError(self::OAI_ERR_CANNOT_DISSEMINATE_FORMAT);
    }
    
    
    /**
     * Responds to the Identify verb.
     *
     * Appends the Identify element for the repository to the response.
     */
    public function identify()
    {
        if($this->error)
            return;
        
        /* according to the schema, this order of elements is required for the
           response to validate */
        $elements = array( 
            'repositoryName'    => get_option('oaipmh_repository_name'),
            'baseURL'           => OAI_PMH_BASE_URL,
            'protocolVersion'   => self::OAI_PMH_PROTOCOL_VERSION,
            'adminEmail'        => get_option('administrator_email'),
            'earliestDatestamp' => $this->_getEarliestDatestamp(),
            'deletedRecord'     => 'no',
            'granularity'       => OaiPmhRepository_Date::OAI_GRANULARITY_STRING
        );
        $identify = $this->document->documentElement->appendNewElementWithChildren(
            'Identify', $elements);

        // Publish support for compression, if appropriate
        // This defers to compression set in Omeka's paths.php
        if(extension_loaded('zlib') && ini_get('zlib.output_compression')) {
            $gzip = $this->document->createElement('compression', 'gzip');
            $deflate = $this->document->createElement('compression', 'deflate');
            $identify->appendChild($gzip);
            $identify->appendChild($deflate);
        }

        $description = $this->document->createElement('description');
        $identify->appendChild($description);
        OaiPmhRepository_OaiIdentifier::describeIdentifier($description);
    }

    /**
     * Responds to the GetRecord verb.
     *
     * Outputs the header and metadata in the specified format for the specified
     * identifier.
     */
    private function getRecord()
    {
        $identifier = $this->_getParam('identifier');
        $metadataPrefix = $this->_getParam('metadataPrefix');
        
        $itemId = OaiPmhRepository_OaiIdentifier::oaiIdToItem($identifier);
        
        if(!$itemId) {
            $this->throwError(self::OAI_ERR_ID_DOES_NOT_EXIST);
            return;
        }
        
        $item = get_db()->getTable('Item')->find($itemId);

        if(!$item) {
            $this->throwError(self::OAI_ERR_ID_DOES_NOT_EXIST);
        }

        if(!$this->error) {
            $verbElement = $this->document->createElement('GetRecord');
            $this->document->documentElement->appendChild($verbElement);
            $this->appendRecord($verbElement, $item, $metadataPrefix);
        }
    }
    
    /**
     * Responds to the ListMetadataFormats verb.
     *
     * Outputs records for all of the items in the database in the specified
     * metadata format.
     *
     * @todo extend for additional metadata formats
     */
    private function listMetadataFormats()
    {
        $identifier = $this->_getParam('identifier');
        /* Items are not used for lookup, simply checks for an invalid id */
        if($identifier) {
            $itemId = OaiPmhRepository_OaiIdentifier::oaiIdToItem($identifier);
        
            if(!$itemId) {
                $this->throwError(self::OAI_ERR_ID_DOES_NOT_EXIST);
                return;
            }
        }
        if(!$this->error) {
            $listMetadataFormats = $this->document->createElement('ListMetadataFormats');
            $this->document->documentElement->appendChild($listMetadataFormats);
            foreach($this->metadataFormats as $prefix => $format) {
                $elements = array(
                    'metadataPrefix' => $prefix,
                    'schema' => $format['schema'],
                    'metadataNamespace' => $format['namespace']
                );
                $listMetadataFormats->appendNewElementWithChildren('metadataFormat', $elements);
            }
        }
    }

    /**
     * Responds to the ListSets verb.
     *
     * Outputs setSpec and setName for all OAI-PMH sets (Omeka collections).
     *
     * @todo replace with Zend_Db_Select to allow use of limit or pageLimit
     */
    private function listSets()
    {
        $db = get_db();
        if ((boolean) get_option('oaipmh_repository_expose_empty_collections')) {
            $collections = get_db()->getTable('Collection')
                ->findBy(array('public' => '1'));
        }
        else {
            $select = new Omeka_Db_Select();
            $select
                ->from(array('collections' => $db->Collection))
                ->joinInner(array('items' => $db->Item), 'collections.id = items.collection_id', array())
                ->where('collections.public = 1')
                ->where('items.public = 1')
                ->group('collections.id');
            $collections = get_db()->getTable('Collection')->fetchObjects($select);
        }

        if(count($collections) == 0)
            $this->throwError(self::OAI_ERR_NO_SET_HIERARCHY);
            
        $listSets = $this->document->createElement('ListSets');     

        if(!$this->error) {
            $this->document->documentElement->appendChild($listSets); 
            foreach ($collections as $collection) {
                $name = metadata($collection, array('Dublin Core', 'Title')) ?: __('[Untitled]');
                $elements = array(
                    'setSpec' => $collection->id,
                    'setName' => $name,
                );
                $set = $listSets->appendNewElementWithChildren('set', $elements);
                $this->_addSetDescription($set, $collection);
            }
        }
    }

    /**
     * Prepare the set description for the collection / set, if any.
     *
     * @see OaiPmhRepository_Metadata_OaiDc::appendMetadata()
     *
     * @param Dom $set
     * @param Collection $collection
     * @return Dom
     */
    protected function _addSetDescription($set, $collection)
    {
        // Prepare the list of Dublin Core element texts, except the first title.
        $elementTexts = array();

        // List of the Dublin Core terms, needed to removed qualified ones.
        $dcTerms = array(
            'title' => 'Title',
            'creator' => 'Creator',
            'subject' => 'Subject',
            'description' => 'Description',
            'publisher' => 'Publisher',
            'contributor' => 'Contributor',
            'date' => 'Date',
            'type' => 'Type',
            'format' => 'Format',
            'identifier' => 'Identifier',
            'source' => 'Source',
            'language' => 'Language',
            'relation' => 'Relation',
            'coverage' => 'Coverage',
            'rights' => 'Rights',
        );

        foreach ($dcTerms as $name => $elementName) {
            $elTexts = $collection->getElementTexts('Dublin Core', $elementName);
            // Remove the first title.
            if ($elementName == 'Title' && isset($elTexts[0])) {
                unset($elTexts[0]);
            }
            if ($elTexts) {
                $elementTexts[$name] = $elTexts;
            }
        }

        if (empty($elementTexts)) {
            return $set;
        }

        $setDescription = $this->document->createElement('setDescription');
        $set->appendChild($setDescription);
        $oai_dc = $this->document->createElementNS(
            OaiPmhRepository_Metadata_OaiDc::METADATA_NAMESPACE, 'oai_dc:dc');
        $setDescription->appendChild($oai_dc);

        $oai_dc->setAttribute('xmlns:dc', OaiPmhRepository_Metadata_OaiDc::DC_NAMESPACE_URI);
        $oai_dc->declareSchemaLocation(
            OaiPmhRepository_Metadata_OaiDc::METADATA_NAMESPACE,
            OaiPmhRepository_Metadata_OaiDc::METADATA_SCHEMA);

        foreach ($elementTexts as $name => $elTexts) {
            foreach ($elTexts as $elementText) {
                $oai_dc->appendNewElement('dc:'.$name, $elementText->text);
            }
        }
    }
    
    /**
     * Responds to the ListIdentifiers and ListRecords verbs.
     *
     * Only called for the initial request in the case of multiple incomplete
     * list responses
     *
     * @uses listResponse()
     */
    private function initListResponse()
    {
        $fromDate = null;
        $untilDate = null;
        
        if(($from = $this->_getParam('from')))
            $fromDate = OaiPmhRepository_Date::utcToDb($from);
        if(($until= $this->_getParam('until')))
            $untilDate = OaiPmhRepository_Date::utcToDb($until, true);
        
        $this->listResponse($this->query['verb'], 
                            $this->query['metadataPrefix'],
                            0,
                            $this->_getParam('set'),
                            $fromDate,
                            $untilDate);
    }
    
    /**
     * Returns the next incomplete list response based on the given resumption
     * token.
     *
     * @param string $token Resumption token
     * @uses listResponse()
     */
    private function resumeListResponse($token)
    {
        $tokenTable = get_db()->getTable('OaiPmhRepositoryToken');
        $tokenTable->purgeExpiredTokens();
        
        $tokenObject = $tokenTable->find($token);
        
        if(!$tokenObject || ($tokenObject->verb != $this->query['verb']))
            $this->throwError(self::OAI_ERR_BAD_RESUMPTION_TOKEN);
        else
            $this->listResponse($tokenObject->verb,
                                $tokenObject->metadata_prefix,
                                $tokenObject->cursor,
                                $tokenObject->set,
                                $tokenObject->from,
                                $tokenObject->until);
    }
    
    /**
     * Responds to the two main List verbs, includes resumption and limiting.
     *
     * @param string $verb OAI-PMH verb for the request
     * @param string $metadataPrefix Metadata prefix
     * @param int $cursor Offset in response to begin output at
     * @param mixed $set Optional set argument
     * @param string $from Optional from date argument
     * @param string $until Optional until date argument
     * @uses createResumptionToken()
     */
    private function listResponse($verb, $metadataPrefix, $cursor, $set, $from, $until) {
        $listLimit = $this->_listLimit;
        
        $db = get_db();
        $itemTable = $db->getTable('Item');
        $select = $itemTable->getSelect();
        $alias = $itemTable->getTableAlias();
        $itemTable->filterByPublic($select, true);
        if($set)
            $itemTable->filterByCollection($select, $set);

        $modifiedClause = $addedClause = '';
        if($from) {
            $quotedFromDate = $db->quote($from);
            $modifiedClause = "$alias.modified >= $quotedFromDate";
            $addedClause = "$alias.added >= $quotedFromDate";
        }
        if($until) {
            if ($from) {
                $modifiedClause .= ' AND ';
                $addedClause .= ' AND ';
            }
            $quotedUntilDate = $db->quote($until);
            $modifiedClause .= "$alias.modified < $quotedUntilDate";
            $addedClause .= "$alias.added < $quotedUntilDate";
        }


        if ($from || $until) {
            $select->where("($modifiedClause) OR ($addedClause)");
        }
        
        // Total number of rows that would be returned
        $rows = $select->query()->rowCount();
        // This limit call will form the basis of the flow control
        $select->limit($listLimit, $cursor);
        
        $items = $itemTable->fetchObjects($select);  
        
        if(count($items) == 0)
            $this->throwError(self::OAI_ERR_NO_RECORDS_MATCH, 'No records match the given criteria.');

        else {

            
            $verbElement = $this->document->createElement($verb);
            $this->document->documentElement->appendChild($verbElement);
            foreach($items as $item) {
                if($verb == 'ListIdentifiers')
                    $this->appendHeader($verbElement, $item);
                else if($verb == 'ListRecords')
                    $this->appendRecord($verbElement, $item, $metadataPrefix);
                
                // Drop Item from memory explicitly
                release_object($item);
            }
            // No token for a full list.
            if (empty($listLimit)) {
            }
            // Token.
            elseif ($rows > ($cursor + $listLimit)) {
                $token = $this->createResumptionToken($verb,
                                                      $metadataPrefix,
                                                      $cursor + $listLimit,
                                                      $set,
                                                      $from,
                                                      $until);

                $tokenElement = $this->document->createElement('resumptionToken', $token->id);
                $tokenElement->setAttribute('expirationDate',
                    OaiPmhRepository_Date::dbToUtc($token->expiration));
                $tokenElement->setAttribute('completeListSize', $rows);
                $tokenElement->setAttribute('cursor', $cursor);
                $verbElement->appendChild($tokenElement);
            }
            // Last token.
            elseif ($cursor != 0) {
                $tokenElement = $this->document->createElement('resumptionToken');
                $verbElement->appendChild($tokenElement);
            }
        }
    }
    
    /**
     * Appends the record's header to the XML response.
     *
     * Adds the identifier, datestamp and setSpec to a header element, and
     * appends in to the document.
     *
     * @param DOMElement $parentElement
     * @param Item $item
     */
    public function appendHeader($parentElement, $item)
    {
        $headerData['identifier'] = 
            OaiPmhRepository_OaiIdentifier::itemToOaiId($item->id);
        $headerData['datestamp'] = OaiPmhRepository_Date::dbToUtc($item->modified);

        $collection = $item->getCollection();
        if ($collection && $collection->public)
            $headerData['setSpec'] = $collection->id;

        $parentElement->appendNewElementWithChildren('header', $headerData);
    }
    
    /**
     * Appends the record to the XML response.
     *
     * Adds both the header and metadata elements as children of a record
     * element, which is appended to the document.
     *
     * @uses appendHeader
     * @uses OaiPmhRepository_Metadata_Interface::appendMetadata
     * @param DOMElement $parentElement
     * @param Item $item
     * @param string $metdataPrefix
     */
    public function appendRecord($parentElement, $item, $metadataPrefix)
    {
        $record = $this->document->createElement('record');
        $parentElement->appendChild($record);
        $this->appendHeader($record, $item);
        
        $metadata = $this->document->createElement('metadata');
        $record->appendChild($metadata);
        
        $formatClass = $this->metadataFormats[$metadataPrefix]['class'];
        $format = new $formatClass;
        $format->appendMetadata($item, $metadata);
    }
        
    /**
     * Stores a new resumption token record in the database
     *
     * @param string $verb OAI-PMH verb for the request
     * @param string $metadataPrefix Metadata prefix
     * @param int $cursor Offset in response to begin output at
     * @param mixed $set Optional set argument
     * @param string $from Optional from date argument
     * @param string $until Optional until date argument
     * @return OaiPmhRepositoryToken Token model object
     */
    private function createResumptionToken($verb, $metadataPrefix, $cursor, $set, $from, $until)
    {
        $tokenTable = get_db()->getTable('OaiPmhRepositoryToken');
        
        $resumptionToken = new OaiPmhRepositoryToken();
        $resumptionToken->verb = $verb;
        $resumptionToken->metadata_prefix = $metadataPrefix;
        $resumptionToken->cursor = $cursor;
        if($set)
            $resumptionToken->set = $set;
        if($from)
            $resumptionToken->from = $from;
        if($until)
            $resumptionToken->until = $until;
        $resumptionToken->expiration = OaiPmhRepository_Date::unixToDb(
            time() + ($this->_tokenExpirationTime * 60 ) );
        $resumptionToken->save();
        
        return $resumptionToken;
    }
    
    
    /**
     * Builds an array of entries for all included metadata mapping classes.
     * Derived heavily from OaipmhHarvester's getMaps().
     *
     * @return array An array, with metadataPrefix => class.
     */
    private function getFormats()
    {
        $formats = array(
            'oai_dc' => array(
                'class' => 'OaiPmhRepository_Metadata_OaiDc',
                'namespace' => OaiPmhRepository_Metadata_OaiDc::METADATA_NAMESPACE,
                'schema' => OaiPmhRepository_Metadata_OaiDc::METADATA_SCHEMA
            ),
            'cdwalite' => array(
                'class' => 'OaiPmhRepository_Metadata_CdwaLite',
                'namespace' => OaiPmhRepository_Metadata_CdwaLite::METADATA_NAMESPACE,
                'schema' => OaiPmhRepository_Metadata_CdwaLite::METADATA_SCHEMA
            ),
            'mets' => array(
                'class' => 'OaiPmhRepository_Metadata_Mets',
                'namespace' => OaiPmhRepository_Metadata_Mets::METADATA_NAMESPACE,
                'schema' => OaiPmhRepository_Metadata_Mets::METADATA_SCHEMA
            ),
            'mods' => array(
                'class' => 'OaiPmhRepository_Metadata_Mods',
                'namespace' => OaiPmhRepository_Metadata_Mods::METADATA_NAMESPACE,
                'schema' => OaiPmhRepository_Metadata_Mods::METADATA_SCHEMA
            ),
            'omeka-xml' => array(
                'class' => 'OaiPmhRepository_Metadata_OmekaXml',
                'namespace' => Omeka_Output_OmekaXml_AbstractOmekaXml::XMLNS,
                'schema' => Omeka_Output_OmekaXml_AbstractOmekaXml::XMLNS_SCHEMALOCATION
            ),
            'rdf' => array(
                'class' => 'OaiPmhRepository_Metadata_Rdf',
                'namespace' => OaiPmhRepository_Metadata_Rdf::METADATA_NAMESPACE,
                'schema' => OaiPmhRepository_Metadata_Rdf::METADATA_SCHEMA
            ),
        );
        return apply_filters('oai_pmh_repository_metadata_formats', $formats);
    }
    
    private function _getParam($param) {
        if (array_key_exists($param, $this->query)) {
            return $this->query[$param];
        }
        return null;
    }
    
    /**
     * Outputs the XML response as a string
     *
     * Called once processing is complete to return the XML to the client.
     *
     * @return string the response XML
     */
    public function __toString()
    {
        return $this->document->saveXML();
    }

    /**
     * Helper to get the earlieast datestamp of the repository.
     *
     * @return string OAI-PMH date stamp.
     */
    private function _getEarliestDatestamp()
    {
        $earliestItem = get_record('Item', array(
            'public' => 1,
            'sort_field' => 'added',
            'sort_dir' => 'a',
        ));
        return $earliestItem
            ? OaiPmhRepository_Date::dbToUtc($earliestItem->added)
            : OaiPmhRepository_Date::unixToUtc(0);
    }
}
