<?php
/**
 * @package OaiPmhRepository
 * @subpackage Libraries
 * @copyright Copyright 2009-2014 John Flatness, Yu-Hsun Lin
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Abstract class containing functions for tasks common to all OAI-PMH
 * responses.
 *
 * @package OaiPmhRepository
 * @subpackage Libraries
 */
class OaiPmhRepository_OaiXmlGeneratorAbstract
{
    // =========================
    // General OAI-PMH constants
    // =========================
    
    const OAI_PMH_NAMESPACE_URI    = 'http://www.openarchives.org/OAI/2.0/';
    const OAI_PMH_SCHEMA_URI       = 'http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd';
    const OAI_PMH_PROTOCOL_VERSION = '2.0';
    
    // =========================
    // Error codes
    // =========================
    
    const OAI_ERR_BAD_ARGUMENT              = 'badArgument';
    const OAI_ERR_BAD_RESUMPTION_TOKEN      = 'badResumptionToken';
    const OAI_ERR_BAD_VERB                  = 'badVerb';
    const OAI_ERR_CANNOT_DISSEMINATE_FORMAT = 'cannotDisseminateFormat';
    const OAI_ERR_ID_DOES_NOT_EXIST         = 'idDoesNotExist';
    const OAI_ERR_NO_RECORDS_MATCH          = 'noRecordsMatch';
    const OAI_ERR_NO_METADATA_FORMATS       = 'noMetadataFormats';
    const OAI_ERR_NO_SET_HIERARCHY          = 'noSetHierarchy';

    // =========================
    // Date/time constants
    // =========================

    protected $_oaiErrorMessages = array(
        self::OAI_ERR_BAD_ARGUMENT => 'The request includes illegal arguments, is missing required arguments, includes a repeated argument, or values for arguments have an illegal syntax.',
        self::OAI_ERR_BAD_RESUMPTION_TOKEN => 'The value of the resumptionToken argument is invalid or expired.',
        self::OAI_ERR_BAD_VERB => "Value of the verb argument is not a legal OAI-PMH verb, the verb argument is missing, or the verb argument is repeated.",
        self::OAI_ERR_CANNOT_DISSEMINATE_FORMAT => 'The metadata format identified by the value given for the metadataPrefix argument is not supported by the item or by the repository.',
        self::OAI_ERR_ID_DOES_NOT_EXIST => 'The value of the identifier argument is unknown or illegal in this repository.',
        self::OAI_ERR_NO_RECORDS_MATCH => 'The combination of the values of the from, until, set and metadataPrefix arguments results in an empty list.',
        self::OAI_ERR_NO_METADATA_FORMATS => 'There are no metadata formats available for the specified item.',
        self::OAI_ERR_NO_SET_HIERARCHY => 'The repository does not support sets.',
    );

    /**
     * Flags if an error has occurred during the response.
     * @var bool
     */
    protected $error;

    /**
     * The XML document being generated.
     * @var DOMDocument
     */
    protected $document;
    
    /**
     * Throws an OAI-PMH error on the given response.
     *
     * @param string $error OAI-PMH error code.
     * @param string $message Optional human-readable error message.
     */
    public function throwError($error, $message = null)
    {
        $this->error = true;
        // Set the default message.
        if (is_null($message)) {
            $message = $this->_oaiErrorMessages[$error];
        }
        $errorElement = $this->document->createElement('error', $message);
        $this->document->documentElement->appendChild($errorElement);
        $errorElement->setAttribute('code', $error);
    }
    
    /**
     * Get the DOMDocument for this generator.
     *
     * @return DOMDocument
     */
    public function getDocument()
    {
        return $this->document;
    }
}
