<?php
/**
 * Library of Congress Suggest
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The Library of Congress Suggest controller.
 * 
 * @package Omeka\Plugins\LcSuggest
 */
class LcSuggest_IndexController extends Omeka_Controller_AbstractActionController
{
    public function indexAction()
    {
        $this->view->form_element_options = $this->_getFormElementOptions();
        $this->view->form_suggest_options = $this->_getFormSuggestOptions();
        $this->view->assignments = $this->_getAssignments();
    }
    
    public function editElementSuggestAction()
    {
        $elementId = $this->getRequest()->getParam('element_id');
        $suggestEndpoint = $this->getRequest()->getParam('suggest_endpoint');
        
        // Don't process empty select options.
        if ('' == $elementId) {
            $this->_helper->redirector('index');
        }
        
        $lcSuggest = $this->_helper->db->getTable('LcSuggest')->findByElementId($elementId);
        
        // Handle an existing suggest record.
        if ($lcSuggest) {
            
            // Delete suggest record if there is no endpoint.
            if ('' == $suggestEndpoint) {
                $lcSuggest->delete();
                $this->_helper->flashMessenger(__('Successfully disabled the element\'s suggest feature.'), 'success');
                $this->_helper->redirector('index');
            }
            
            // Don't process an invalid suggest endpoint.
            if (!$this->_suggestEndpointExists($suggestEndpoint)) {
                $this->_helper->flashMessenger(__('Invalid suggest endpoint. No changes have been made.'), 'error');
                $this->_helper->redirector('index');
            }
            
            $lcSuggest->suggest_endpoint = $suggestEndpoint;
            $this->_helper->flashMessenger(__('Successfully edited the element\'s suggest feature.'), 'success');
        
        // Handle a new suggest record.
        } else {
            
            // Don't process an invalid suggest endpoint.
            if (!$this->_suggestEndpointExists($suggestEndpoint)) {
                $this->_helper->flashMessenger(__('Invalid suggest endpoint. No changes have been made.'), 'error');
                $this->_helper->redirector('index');
            }
            
            $lcSuggest = new LcSuggest;
            $lcSuggest->element_id = $elementId;
            $lcSuggest->suggest_endpoint = $suggestEndpoint;
            $this->_helper->flashMessenger(__('Successfully enabled the element\'s suggest feature.'), 'success');
        }
        
        $lcSuggest->save();
        $this->_helper->redirector('index');
    }
    
    /**
     * Outputs the suggest endpoint URL of the specified element or NULL if 
     * there is none.
     */
    public function suggestEndpointAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $elementId = $this->getRequest()->getParam('element_id');
        $lcSuggest = $this->_helper->db->getTable('LcSuggest')->findByElementId($elementId);
        echo $lcSuggest->suggest_endpoint;
    }
    
    /**
     * Proxy for the Library of Congress suggest endpoints, used by the 
     * autosuggest feature.
     */
    public function suggestEndpointProxyAction()
    {
        // Get the suggest record.
        $elementId = $this->getRequest()->getParam('element-id');
        $lcSuggest = $this->_helper->db->getTable('LcSuggest')->findByElementId($elementId);
        
        // Query the specified Library of Congress suggest endpoint, get the 
        // response, and output suggestions in JSON.
        $client = new Zend_Http_Client();
        $client->setUri($lcSuggest->suggest_endpoint);
        $client->setParameterGet('q', $this->getRequest()->getParam('term'));
        $json = json_decode($client->request()->getBody());
        $this->_helper->json($json[1]);
    }
    
    /**
     * Check if the specified suggest endpoint exists.
     * 
     * @param string $suggestEndpoint
     * @return bool
     */
    private function _suggestEndpointExists($suggestEndpoint)
    {
        $suggestEndpoints = $this->_helper->db->getTable('LcSuggest')->getSuggestEndpoints();
        if (!array_key_exists($suggestEndpoint, $suggestEndpoints)) {
            return false;
        }
        return true;
    }
    
    /**
     * Get an array to be used in formSelect() containing all elements.
     * 
     * @return array
     */
    private function _getFormElementOptions()
    {
        $db = $this->_helper->db->getDb();
        $sql = "
        SELECT es.name AS element_set_name, e.id AS element_id, e.name AS element_name, 
        it.name AS item_type_name, ls.id AS lc_suggest_id 
        FROM {$db->ElementSet} es 
        JOIN {$db->Element} e ON es.id = e.element_set_id 
        LEFT JOIN {$db->ItemTypesElements} ite ON e.id = ite.element_id 
        LEFT JOIN {$db->ItemType} it ON ite.item_type_id = it.id 
        LEFT JOIN {$db->LcSuggest} ls ON e.id = ls.element_id 
        WHERE es.record_type IS NULL OR es.record_type = 'Item' 
        ORDER BY es.name, it.name, e.name";
        $elements = $db->fetchAll($sql);
        $options = array('' => __('Select Below'));
        foreach ($elements as $element) {
            $optGroup = $element['item_type_name'] 
                      ? __('Item Type') . ': ' . __($element['item_type_name']) 
                      : __($element['element_set_name']);
            $value = __($element['element_name']);
            if ($element['lc_suggest_id']) {
                $value .= ' *';
            }
            $options[$optGroup][$element['element_id']] = $value;
        }
        return $options;
    }
    
    /**
     * Get an array to be used in formSelect() containing all sugggest endpoints.
     * 
     * @return array
     */
    private function _getFormSuggestOptions()
    {
        $suggests = $this->_helper->db->getTable('LcSuggest')->getSuggestEndpoints();
        $options = array('' => __('Select Below'));
        foreach ($suggests as $suggestEndpoint => $suggest) {
            if ('http://id.loc.gov/suggest' == $suggestEndpoint) {
                $options[$suggestEndpoint] = __($suggest['name']);
            } else {
                $optGroup = preg_match('#^http://id\.loc\.gov/vocabulary#', $suggestEndpoint) ? __('Vocabularies'): __('Authorities');
                $options[$optGroup][$suggestEndpoint] = __($suggest['name']);
            }
        }
        return $options;
    }
    
    /**
     * Get all the authority/vocabulary assignments.
     * 
     * @return array
     */
    private function _getAssignments()
    {
        $lcSuggestTable = $this->_helper->db->getTable('LcSuggest');
        $elementTable = $this->_helper->db->getTable('Element');
        $elementSetTable = $this->_helper->db->getTable('ElementSet');
        
        $suggestEndpoints = $lcSuggestTable->getSuggestEndpoints();
        $assignments = array();
        foreach ($lcSuggestTable->findAll() as $lcSuggest) {
            $element = $elementTable->find($lcSuggest->element_id);
            $elementSet = $elementSetTable->find($element->element_set_id);
            $authorityVocabulary = $suggestEndpoints[$lcSuggest->suggest_endpoint]['name'];
            $assignments[] = array('element_set_name' => __($elementSet->name), 
                                   'element_name' => __($element->name), 
                                   'authority_vocabulary' => __($authorityVocabulary));
        }
        return $assignments;
    }
}
