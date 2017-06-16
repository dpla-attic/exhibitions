<?php
/**
 * @package OaiPmhRepository
 * @subpackage Libraries
 * @copyright Copyright 2009-2014 John Flatness, Yu-Hsun Lin
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Parent class for all XML-generating classes.
 *
 * @package OaiPmhRepository
 * @subpackage Libraries
 */
class OaiPmhRepository_DOMElement extends DOMElement
{
    const XML_SCHEMA_NAMESPACE_URI = 'http://www.w3.org/2001/XMLSchema-instance';
    
    /**
     * Creates a new XML element with the specified children
     *
     * Creates a parent element with the given name, with children with names
     * and values as given.  Adds the resulting element as a child of this
     * element
     *
     * @param string $name Name of the new parent element.
     * @param array $children Child names and values, as name => value.
     * @return OaiPmhRepository_DOMElement The new tree of elements.
     */
    public function appendNewElementWithChildren($name, $children)
    {
        $document = $this->ownerDocument;
        $newElement = $document->createElement($name);
        foreach($children as $tag => $value)
        {
            if (is_array($value)) {
                $newElement->appendNewElementWithChildren($tag, $value);
            } else {
                $newElement->appendNewElement($tag, $value);
            }
        }
        $this->appendChild($newElement);
        return $newElement;
    }
    
    /**
     * Creates a parent element with the given name, with text as given.  
     *
     * Adds the resulting element as a child of this element.
     *
     * @param string $name Name of the new parent element.
     * @param string $text Text of the new element.
     * @return OaiPmhRepository_DOMElement The new element.
     */
    public function appendNewElement($name, $text = null)
    {
        $document = $this->ownerDocument;
        $newElement = $document->createElement($name);
        // Use a TextNode, causes escaping of input text
        if ($text) {
            $text = $document->createTextNode($text);
            $newElement->appendChild($text);
        }
        $this->appendChild($newElement);
        return $newElement;
    }

    /**
     * Add an xsi:schemaLocation to this element.
     *
     * The OAI-PMH spec requires the xmlns attribute reappear even when its
     * redundant, so we can't simply use setAttributeNS.
     *
     * @param string $namespace Namespace URI
     * @param string $schema Schema location URI
     */
    public function declareSchemaLocation($namespace, $schema)
    {
        $this->setAttribute('xmlns:xsi', self::XML_SCHEMA_NAMESPACE_URI);
        $this->setAttribute('xsi:schemaLocation', "$namespace $schema");
    }
}
