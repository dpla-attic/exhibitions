<?php
/**
 * Library of Congress Suggest
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The lc_suggests table.
 * 
 * @package Omeka\Plugins\LcSuggest
 */
class Table_LcSuggest extends Omeka_Db_Table
{
    /**
     * List of suggest endpoints made available by the Library of Congress 
     * Authorities and Vocabularies service.
     * 
     * The keys are URLs to the authority/vocabulary suggest endpoints. The 
     * values are arrays containing the authority/vocabulary name and the URL to 
     * the authority/vocabulary description page.
     * 
     * These authorities and vocabularies have been selected due to their large 
     * size and suitability to the autosuggest feature. Vocabularies not 
     * explicitly included here may be redundant or better suited as a full list 
     * controlled vocabulary.
     * 
     * @see http://id.loc.gov/
     */
    private $_suggestEndpoints = array(
        'http://id.loc.gov/suggest' => array(
            'name' => 'All Authorities and Vocabularies', 
            'url'  => 'http://id.loc.gov', 
        ), 
        'http://id.loc.gov/authorities/suggest' => array(
            'name' => 'All Authorities', 
            'url'  => 'http://id.loc.gov', 
        ), 
        'http://id.loc.gov/vocabulary/suggest' => array(
            'name' => 'All Vocabularies', 
            'url'  => 'http://id.loc.gov', 
        ), 
        'http://id.loc.gov/authorities/subjects/suggest' => array(
            'name' => 'Library of Congress Subject Headings', 
            'url'  => 'http://id.loc.gov/authorities/subjects.html'
        ), 
        'http://id.loc.gov/authorities/names/suggest' => array(
            'name' => 'Library of Congress Names', 
            'url'  => 'http://id.loc.gov/authorities/names.html', 
        ), 
        'http://id.loc.gov/authorities/childrensSubjects/suggest' => array(
            'name' => 'Library of Congress Children\'s Subject Headings', 
            'url'  => 'http://id.loc.gov/authorities/childrensSubjects.html', 
        ), 
        'http://id.loc.gov/authorities/genreForms/suggest' => array(
            'name' => 'Library of Congress Genre Form Headings', 
            'url'  => 'http://id.loc.gov/authorities/genreForms.html', 
        ), 
        'http://id.loc.gov/vocabulary/graphicMaterials/suggest' => array(
            'name' => 'Thesaurus for Graphic Materials', 
            'url'  => 'http://id.loc.gov/vocabulary/graphicMaterials.html', 
        ), 
        'http://id.loc.gov/vocabulary/relators/suggest' => array(
            'name' => 'MARC Code List for Relators', 
            'url'  => 'http://id.loc.gov/vocabulary/relators.html', 
        ), 
        'http://id.loc.gov/vocabulary/countries/suggest' => array(
            'name' => 'MARC List for Countries', 
            'url'  => 'http://id.loc.gov/vocabulary/countries.html', 
        ), 
        'http://id.loc.gov/vocabulary/geographicAreas/suggest' => array(
            'name' => 'MARC List for Geographic Areas', 
            'url'  => 'http://id.loc.gov/vocabulary/geographicAreas.html', 
        ), 
        'http://id.loc.gov/vocabulary/languages/suggest' => array(
            'name' => 'MARC List for Languages', 
            'url'  => 'http://id.loc.gov/vocabulary/languages.html', 
        ), 
        'http://id.loc.gov/vocabulary/iso639-5/suggest' => array(
            'name' => 'ISO 639-5 Language Families and Groups', 
            'url'  => 'http://id.loc.gov/vocabulary/iso639-5.html', 
        ), 
    );
    
    /**
     * Find a suggest record by element ID.
     * 
     * @param int|string $elementId
     * @return LcSuggest|null
     */
    public function findByElementId($elementId)
    {
        $select = $this->getSelect()->where('element_id = ?', $elementId);
        return $this->fetchObject($select);
    }
    
    /**
     * Get the suggest endpoints.
     * 
     * @return array
     */
    public function getSuggestEndpoints()
    {
        return $this->_suggestEndpoints;
    }
}
