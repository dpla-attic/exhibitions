<?php

/**
 * Returns HTML for a list of linked thumbnails for the items on a given exhibit page.  Each
 * thumbnail is wrapped with a li. Fisrt thumbnail is wrapped with a li of class="active"
 *
 * @param int $start The range of items on the page to display as thumbnails
 * @param int $end The end of the range
 * @param array $props Properties to apply to the <img> tag for the thumbnails
 * @param string $thumbnailType The type of thumbnail to display
 * @return string HTML output
 **/
function dpla_thumbnail_gallery($start, $end, $props = array(), $thumbnailType = 'square_thumbnail')
{
    $html = '';
    for ($i = (int)$start; $i <= (int)$end; $i++) {
        if ($attachment = exhibit_builder_page_attachment($i)) {

            $active = ($i == (int)$start) ? 'active' : '';

            $html .= "\n" . '<li class="' . $active . '">';
            $html .= '<span class="icon-arrow-up" aria-hidden="true"></span>';
            if ($attachment['file']) {
                $caption = strip_tags($attachment['caption']);
                $caption = $caption ? $caption  . ' »' : ' ';
                $thumbnail = file_image($thumbnailType, $props, $attachment['file']);
                $html .= dpla_builder_link_to_exhibit_image(
                    $thumbnail,
                    array(
                        'class' => 'thumb',
                        'data-title' => $caption,
                    ),
                    $attachment['item']
                );
            }

            $html .= '<span class="caption">'. dpla_attachment_caption($attachment) .'</span>';
            $html .= '</li>' . "\n";
        }
    }
    if ($html) {
        $html = '<ul class="thumbs">' . $html . '</ul>';
    }

    return apply_filters('exhibit_builder_thumbnail_gallery', $html,
        array('start' => $start, 'end' => $end, 'props' => $props, 'thumbnail_type' => $thumbnailType));
}

/**
 * Return HTML for displaying an attached item on an exhibit page.
 *
 * @see exhibit_builder_page_attachment for attachment array contents
 * @param array $attachment The attachment.
 * @param array $fileOptions Options for file_markup when displaying a file
 * @param array $linkProperties Attributes for use when linking to an item
 * @return string
 */
function dpla_attachment_markup($attachment, $fileOptions, $linkProperties)
{
    if (!$attachment) {
        return '';
    }

    $item = $attachment['item'];
    $file = $attachment['file'];

    if (!isset($fileOptions['linkAttributes']['href'])) {
        $fileOptions['linkAttributes']['href'] = exhibit_builder_exhibit_item_uri($item);
    }

    if (!isset($fileOptions['imgAttributes']['alt'])) {
        $fileOptions['imgAttributes']['alt'] = metadata($item, array('Dublin Core', 'Title'));
    }
    
    if ($file) {
        $html = file_markup($file, $fileOptions, null);
    } else if($item) {
        $html = exhibit_builder_link_to_exhibit_item(null, $linkProperties, $item);
    }

//    $html .= dpla_attachment_caption($attachment);

    return apply_filters('exhibit_builder_attachment_markup', $html,
        compact('attachment', 'fileOptions', 'linkProperties')
    );
}

/**
 * Return HTML for displaying an attachment's caption.
 *
 * @see exhibit_builder_page_attachment for attachment array contents
 * @param array $attachment The attachment
 * @return string
 */
function dpla_attachment_caption($attachment)
{

    $caption = strip_tags($attachment['caption']);
    $caption = $caption ? $caption  . ' »' : ' ';
    $item = $attachment['item'];

    $html = '<span class="caption">'
          . exhibit_builder_link_to_exhibit_item($caption,array(), $item)
          . '</span>';

    return apply_filters('exhibit_builder_caption', $html, array(
        'attachment' => $attachment
    ));
}

/**
 * Return the markup for the exhibit page navigation.
 *
 * @param ExhibitPage|null $exhibitPage If null, uses the current exhibit page
 * @return string
 */
function dpla_theme_nav($exhibitPage = null)
{
    if (!$exhibitPage) {
        if (!($exhibitPage = get_current_record('exhibit_page', false))) {
            return;
        }
    }

    $html = "";
    $exhibit = get_db()->getTable('Exhibit')->find($exhibitPage->exhibit_id);
    $pagesTrail = $exhibitPage->getAncestors();
    $pagesTrail[] = $exhibitPage;
    foreach ($pagesTrail as $page) {
        if ($page->layout == dpla_exhibit_homepage_layout_name()) {
            continue;
        }
        $pageExhibit = $page->getExhibit();
        $pageSiblings =  $pageExhibit->getTopPages();
        $html = '<ul>' . "\n";

        foreach ($pageSiblings as $pageSibling) {
            if ($pageSibling->layout == dpla_exhibit_homepage_layout_name()) {
                continue;
            }
            $current = in_array($pageSibling->id, array ($page->id, $exhibitPage->parent_id));
            $html .= '<li' . ($current ? ' class="selected"' : '') . '>';
            $html .= '<a class="exhibit-page-title" href="' . html_escape(exhibit_builder_exhibit_uri($exhibit, $pageSibling)) . '">';
            $html .= html_escape($pageSibling->title) . "</a></li>\n";
        }
        $html .= '</ul>' . "\n";
    }
    $html = apply_filters('exhibit_builder_page_nav', $html);
    return $html;
}

/**
 * Get a list item for a page.
 */
function dpla_page_summary($exhibitPage = null)
{
    if (!$exhibitPage) {
        $exhibitPage = get_current_record('exhibit_page');
    }
    $thum = dpla_exhibit_page_thumbnail_att($exhibitPage);
    $html = '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
          . '<img src="'.$thum['file_uri_square'].'" alt="' . metadata($exhibitPage, 'title') .'" /><br />'
          . metadata($exhibitPage, 'title') .'</a>';

    return $html;
}

/**
 * Return a link to the parent exhibit page
 *
 * @param string $text Link text
 * @param array $props Link attributes
 * @param ExhibitPage $exhibitPage If null, will use the current exhibit page
 * @return string
 */
function dpla_link_to_parent_page($text = null, $props = array(), $exhibitPage = null)
{
    if (!$exhibitPage) {
        $exhibitPage = get_current_record('exhibit_page');
    }
    $exhibit = get_record_by_id('Exhibit', $exhibitPage->exhibit_id);

    if($exhibitPage->parent_id) {
        $parentPage = $exhibitPage->getParent();
        if ($text === null) {
            $text = metadata($parentPage, 'title');
        }
        return exhibit_builder_link_to_exhibit($exhibit, $text, $props, $parentPage);
    }

    return null;
}

function dpla_link_to_current_page() {
    $exhibitPage = get_current_record('exhibit_page');
    echo exhibit_builder_link_to_exhibit(null, $exhibitPage->title, array(), $exhibitPage);
}

function dpla_link_to_previous_page($title = null) {
    $current_page = get_current_record('exhibit_page');
    $previousPage = $current_page->previousOrParent();
    return exhibit_builder_link_to_exhibit(null, $title, array(), $previousPage);
}

function dpla_link_to_next_page($title = null) {
    $current_page = get_current_record('exhibit_page');
    if ($current_page->parent_id) {
        $nextPage = $current_page->next();
    }
    else {
        $nextPage = $current_page->getFirstChildPage();
    }
    if ($nextPage) {
        return exhibit_builder_link_to_exhibit(null, $title, array(), $nextPage);
    }
}

function dpla_page_position() {
    $current_page = get_current_record('exhibit_page');
    $theme_page = $current_page->parent_id ? $current_page->getParent() : $current_page;
    $pages = array_merge(array($theme_page), $theme_page->getChildPages());
    if (1 < count($pages)) {
        $current = 1;
        foreach ($pages as $page) {
            if ($current_page->id == $page->id) break;
            $current++;
        }
        return implode(' ', array ($current, 'of', count($pages)));
    }
}

/**
 * Return a link to an item within an exhibit.
 *
 * @param string $text Link text (by default, the item title is used)
 * @param array $props Link attributes
 * @param Item $item If null, will use the current item.
 * @return string
 */
function dpla_builder_link_to_exhibit_image($text = null, $props = array(), $item = null)
{
    if (!$item) {
        $item = get_current_record('item');
    }

    if (!isset($props['class'])) {
        $props['class'] = 'exhibit-item-link';
    }

    $file = get_db()->getTable('File')->findWithImages($item->id);
    if ($file) {
        $uri = html_escape($file[0]->getWebPath());
    } else {
        // show default image if no image exist, to prevent PHP error
        $uri = img('fallback-file.png');
    }

    $text = (!empty($text) ? $text : strip_formatting(metadata('item', array('Dublin Core', 'Title'))));
    $html = '<a href="' . html_escape($uri) . '" '. tag_attributes($props) . '>' . $text . '</a>';
    $html = apply_filters('exhibit_builder_link_to_exhibit_item', $html, array('text' => $text, 'props' => $props, 'item' => $item));
    return $html;
}

/**
 * Return exhibit Homepage. This page should have "DPLA Exhibit Home Page" layout.
 * If exhibit contains more then one homepages, then first homepage will be returned.
 * If exhibit doesn't contains homepages, then NULL will be returned.
 *
 * @param null $exhibit You may specify exhibit object. Otherwise, current exhibit object will be used.
 * @return first ExhibitPage object with layout "dpla-exhibit-home-page" OR NULL.
 */
function dpla_get_exhibit_homepage($exhibit = null) {
    if (!$exhibit) {
        $exhibit = get_current_record('exhibit');
    }
    $select = $exhibit->getTable('ExhibitPage')->
        getSelect()->
        where('exhibit_id = ?', $exhibit->id)->
        where('layout = ?', dpla_exhibit_homepage_layout_name());
    $homepages = $exhibit->getTable('ExhibitPage')->fetchObjects($select);
    return $homepages && count($homepages) > 0 ? $homepages[0] : null;
}

/**
 * Return exhibit page thumbnail attachment as array of ('item', 'file', 'file_specified', 'caption', 'file_uri_square', 'file_uri_notsquare'')
 *
 * Usage examples include, but not limited to following:
 * thumbnail URI: dpla_exhibit_page_thumbnail_att($page)['file_uri_square']
 * thumbnail caption: dpla_exhibit_page_thumbnail_att($page)['caption']
 * thumbnail item URI: dpla_exhibit_page_thumbnail_att($page)['item_uri']
 *
 * Also 'item' and 'file' available
 */
function dpla_exhibit_page_thumbnail_att($exhibitPage = null) {
    $result = exhibit_builder_page_attachment(1, 0, $exhibitPage);
    $result = buildResult($result);
    return $result;
}

function dpla_exhibit_page_mini_thumbnail_att($exhibitPage = null) {
    $result = exhibit_builder_page_attachment(2, 0, $exhibitPage);
    $result = buildResult($result);
    return $result;
}

function buildResult($result) {
    $result['file_uri_square'] = isset($result['file']) ? $result['file']->getWebPath('square_thumbnail') : img("fallback-file.png");
    $result['file_uri_thumbnail'] = isset($result['file']) ? $result['file']->getWebPath('thumbnail') : img("fallback-file.png");
    $result['file_uri_notsquare'] = isset($result['file']) ? $result['file']->getWebPath('fullsize') : img("fallback-file.png");
    $result['item_uri'] = isset($result['item']) ? exhibit_builder_exhibit_item_uri($result['item']) : "";
    return $result;
}

// FIXME: clean up this code
function dpla_get_exhibitpage_entries($start = 0, $end = 7) {
    $result = array();
    for ($i = $start; $i <= $end; $i++) {
        if ($attachment = exhibit_builder_page_attachment($i)) {
            $attachment['file_uri_square'] = get_attachment_thumbnail($attachment);
            $attachment['item_uri'] = isset($attachment['item']) ? exhibit_builder_exhibit_item_uri($attachment['item']) : "";
            array_push($result, $attachment);
        }
    }
    return $result;
}

/**
 * Return URI of attachment thumbnail ("squary" by default)
 */
function get_attachment_thumbnail($attachment, $type = "square_thumbnail") {
    if (isset($attachment['file'])) {
        $uri = $attachment['file']->getWebPath($type);
        if (!file_exists("files/".$attachment['file']->getStoragePath($type))) {
            // FIXME: clean up this
            $uri = "http://openexhibits.org/wp-content/uploads/icon/large/video-viewer-icon-100x100.png";
        }
    }
    if (!isset($uri) || !$uri) {
        // FIXME: clean up this
        $uri = "http://openexhibits.org/wp-content/uploads/icon/large/video-viewer-icon-100x100.png";
    }
    return $uri;
}

/**
 * Exhibit page item field markup by name, if such field exist.
 *
 * @param $fieldLabel Field label.
 * @param $values Field value.
 * @return array|null
 */
function get_item_field_markup($fieldLabel, $values) {
    if ($values) {
        if (!is_array($values)) {
            $values = array($values);
        }
        return "<ul>".
                 "<li><h6>".$fieldLabel."</h6></li>".
                 "<li>".join($values, "<br/>")."</li>".
               "</ul>";
    }
    return null;
}

/**
 * Retrieve API object by id. API basic URL should be configured in 'config.ini'
 * Object will be returned as
 *
 * @param $apiObjectId
 * @return mixed|string
 */
function get_dpla_api_object($itemId) {

    // if API URL configured ...
    $config = Zend_Registry::get('bootstrap')->getResource('Config');
    $baseUrl = $config->dpla->apiUrl;
    $params = (array('api_key' => $config->dpla->apiKey));
    if ($baseUrl && !empty($itemId)) {

        // ... and API has such item
        $request = $baseUrl."/items/".$itemId."?".http_build_query($params);

        $session = curl_init($request);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($session);
        curl_close($session);

        if ($obj = json_decode($response, true)) {
            // ... then return JSON as array
            return array_key_exists('docs', $obj) ? $obj['docs'][0] : "";
        }
    }

    return "";
}

/**
 * Parse JSON and return value by array-path.
 * Usage: dpla_get_field_value_by_arrayname($json, array('sourceResource', 'date', 'displayDate'))
 * @return string
 */
function dpla_get_field_value_by_arrayname($json, $arr) {
    $name = array_shift($arr);
    $value = array_key_exists($name, $json) ? $json[$name] : null;

    if (is_array($value)) { 
        if (count($arr) > 0) {
            $value = dpla_get_field_value_by_arrayname($value, $arr);
        } else {
            $value = join_array_values($value);
        }
    }

    return $value;
}

/**
 * Return exhibit page item meta information by field name
 */
function dpla_get_field_value_by_name($item, $name) {
    return metadata($item['item'], array('Dublin Core', $name), array(Omeka_View_Helper_Metadata::DELIMITER=>'; '));
}

/**
 * @param array 
 * @return string
 */
function join_array_values($arr) {
    foreach($arr as &$value) {
        if (!preg_match("/[.,?!;:]$/", $value)) {
            $value .= '.';
        }
    }
    return implode(" ", $arr);
}

/**
 * Return layout of exhibit page entry
 * @param $order index of entry in exhibit page
 * @return mixed HTML
 */
function external_exhibit_uri_layout_input_text($order)
{
    $html = '<div class="inputs five columns omega">';
    $html .= get_view()->formText("Text[$order]",
        exhibit_builder_page_text($order));
    $html .= '</div>';
    $html = apply_filters('exhibit_builder_layout_form_text', $html,
        array('order' => $order));
    return $html;
}


// FIXME: I'm sure PHP has better way to define global variables
function dpla_exhibit_homepage_layout_name() {
    return "dpla-exhibit-home-page";
}

function metadata_table($item) {
    $metadata_table = new MetadataTable($item);
    return $metadata_table->table();
}

class MetadataTable {

    private $item_metadata = null;

    function __construct($item) {
        $this->item_metadata = new ItemMetadata($item);
    }

    /**
     * Return a table of metadata for a single item
     * @return HTML
     */
    function table() {
        $html = $this->header()
              . $this->body()
              . '<br/>'
              . $this->link();
        return $html;
    }

    private function header() {
        $html = '';
        if ($title = $this->item_metadata->get_title()) {
            $html = '<h5>' . $title . '</h5>';
        }
        return $html;
    }

    private function body() {

        $html = '<div class="table">'
              . $this->row('Date', $this->item_metadata->get_date())
              . $this->row('Creator', $this->item_metadata->get_creator())
              . $this->row('Description', $this->item_metadata->get_description())
              . $this->row('Rights', $this->item_metadata->get_rights())
              . $this->row('Standardized Rights Statement', $this->item_metadata->get_edm_rights());

        $provider_value = $this->item_metadata->get_provider();
        $contributing_institution_value = $this->item_metadata->get_contributing_institution();

        $html .= $this->row('Partner', $provider_value);

        //Create 'Contributing Insititution' row if 'Contributing Instition' is different than 'Provider'
        $contributors = count($contributing_institution_value);
        if ($contributors > 1 || ($contributors == 1 && 
            $contributing_institution_value[0] != $provider_value)) {
            $html .= $this->row('Contributing Institution', $contributing_institution_value);
        }

        $html .= $this->row('Is Part Of', $this->item_metadata->get_is_part_of())
              . '</div>';

        return $html;
    }

    /**
     * @param string $name 
     * @param string|array $value 
     */
    private function row($name, $value) {
        $html = '';

        if ($value) {

            if (is_array($value)) {
                $value = implode('<br/>', $value);
            }

            $html = '<ul>'
                  . '<li><h6>' . $name . '</h6></li>'
                  . '<li>';

            if (strlen($value) > 250) {
                $html .= $this->long_row($value);
            } else {
                $html .= $value;
            }
                  
            $html .= '</li>'
                  . '</ul>';
        }
        return $html;
    }

    private function long_row($value) {
        $html = '<div class="desc-short">'
              . substr($value, 0, 250) . '... '
              . '<a class="desc-toggle">more <span class="icon-arrow-down" aria-hidden="true"></span></a>'
              . '</div>'

              . '<div class="desc-long">'
              . $value . ' '
              . '<a class="desc-toggle">less <span class="icon-arrow-up" aria-hidden="true"></span></a>'
              . '</div>';

        return $html;
    }

    private function link() {
        $html = '';
        if ($id = $this->item_metadata->get_id()) {
            $html = '<div>'
                  . '<a href="http://dp.la/item/' . $id . '">'
                  . 'View this item in the DPLA'
                  . '</a>'
                  . '</div>';
        }
        return $html;
    }
}

/*
 * ItemMetadata represents the combined metadata for an item.
 * Sources of metadata include curatorial metadata entered through the Omeka
 * admin dashboard, and data from the DPLA API.
 *
 * get_ATTRIBUTE() functions return Omeka metadata if available, and
 * fall back to DPLA API metadata if it is not.
 *
 * Some get_ATTRIBUTE() functions have an optional parameter,
 * $api_preferred, which is false by defualt.
 * If $api_preferred is set to true, it will return DPLA API metadata if 
 * available, and fall back to Omeka metadata if it is not.
 * This option is valuable for tasks that prefer standardized data, such as
 * collecting analytics statistics.
 *
 * Note that some attributes are only available through either Omeka or the
 * DPLA API, but not both.
 */
class ItemMetadata {
    
    private $item;
    private $json;

    function __construct($item) {
        $this->item = $item;
    }

    function get_title($opts = array('api_preferred' => false)) {
        $omeka_field_name = "Title";
        $api_field_name = array('sourceResource', 'title');
        $api_preferred = $opts['api_preferred'];
        return $this->get_field_value($omeka_field_name, $api_field_name, $api_preferred);
    }

    function get_date() {
        $omeka_field_name = "Date";
        $api_field_name = array('sourceResource', 'date', 'displayDate');
        return $this->get_field_value($omeka_field_name, $api_field_name);
    }

    function get_creator() {
        $omeka_field_name = "Creator";
        $api_field_name = array('sourceResource', 'creator');
        return $this->get_field_value($omeka_field_name, $api_field_name);
    }

    function get_description() {
        $omeka_field_name = "Description";
        $api_field_name = array('sourceResource', 'description');
        return $this->get_field_value($omeka_field_name, $api_field_name);
    }

    function get_rights() {
        $omeka_field_name = "Rights";
        $api_field_name = array('sourceResource', 'rights');
        return $this->get_field_value($omeka_field_name, $api_field_name);
    }

    function get_edm_rights() {
        return $this->lookup_api_field(array('rights'));
    }

    function get_provider($opts = array('api_preferred' => false)) {
        $omeka_field_name = "Source";
        $api_field_name = array('provider', 'name');
        $api_preferred = $opts['api_preferred'];
        return $this->get_field_value($omeka_field_name, $api_field_name, $api_preferred);
    }

    function get_data_provider() {
        return $this->lookup_api_field(array('dataProvider'));
    }

    function get_contributing_institution() {
        $data_provider = $this->get_data_provider();
        $intermediate_provider = $this->lookup_api_field(array('intermediateProvider'));
        $contributing_institution = array_filter(array($data_provider, $intermediate_provider));
        return $contributing_institution;
    }

    function get_is_part_of() {
        return dpla_get_field_value_by_name($this->item, "Is Part Of");
    }

    function get_id() {
        return $this->lookup_api_field(array('id'));
    }

    /**
     * Return the Omeka or API field value.
     *
     * @return string|NULL
     * @param string $omekaFieldName 
     * @param array $apiFieldName 
     */
    private function get_field_value($omeka_field_name, $api_field_name, $api_preferred = false) {
        // get field value from omeka
        $field_value = dpla_get_field_value_by_name($this->item, $omeka_field_name);

        if ($api_preferred == true) {
            // get field value from api
            $api_field_value = $this->lookup_api_field($api_field_name);

            if($api_field_value != null) {
                $field_value = $api_field_value;
            }
        }
        
        return $field_value;
    }

    /*
     * Return the API field value.
     *
     * @return string|NULL
     * @param array $apiFieldName 
     */
    private function lookup_api_field($api_field_name) {
        $field_value = null;

        if ($this->get_json() != false) {
            $field_value = dpla_get_field_value_by_arrayname($this->get_json(), $api_field_name);
        }

        return $field_value;
    }

    /* 
     * Lazy load $json.
     * If the inital call to the API does not return a valid record, the value
     * of $json will be set to an empty string (@see get_dpla_api_object), and
     * will subsequently evaluate FALSE for !isset().
     */
    private function get_json() {
        if(!isset($this->json)) {
            $this->json = get_dpla_api_object(dpla_get_field_value_by_name($this->item, 'Has Version'));
        }

        return $this->json;
    }
}
