<?php
/**
 * Library of Congress Suggest
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * A lc_suggests row.
 * 
 * @package Omeka\Plugins\CollectionTree
 */
class LcSuggest extends Omeka_Record_AbstractRecord
{
    public $id;
    public $element_id;
    public $suggest_endpoint;
}
