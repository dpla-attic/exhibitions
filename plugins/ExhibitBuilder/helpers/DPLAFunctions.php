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
    $html = '<ul class="thumbs">';
    for ($i = (int)$start; $i <= (int)$end; $i++) {
        if ($attachment = exhibit_builder_page_attachment($i)) {

            $active = ($i == (int)$start) ? 'active' : '';

            $html .= "\n" . '<li class="' . $active . '">';
            if ($attachment['file']) {
                $thumbnail = file_image($thumbnailType, $props, $attachment['file']);
                $html .= exhibit_builder_link_to_exhibit_item($thumbnail, array('class' => 'thumb'), $attachment['item']);
            }
            $html .= '</li>' . "\n";
        }
    }
    $html .= '</ul>';
    
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

    $html .= dpla_attachment_caption($attachment);

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
    if (!is_string($attachment['caption']) || $attachment['caption'] == '') {
        return '';
    }

    $caption = strip_tags($attachment['caption']) . ' »';
    $item = $attachment['item'];

    $html = '<div class="caption">'
          . exhibit_builder_link_to_exhibit_item($caption,array(), $item)
          . '</div>';

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

    $exhibit = get_db()->getTable('Exhibit')->find($exhibitPage->exhibit_id);
    $pagesTrail = $exhibitPage->getAncestors();
    $pagesTrail[] = $exhibitPage;
    foreach ($pagesTrail as $page) {
        $linkText = $page->title;
        $pageExhibit = $page->getExhibit();
        $pageParent = $page->getParent();
        $pageSiblings =  $pageExhibit->getTopPages(); 
        $html = '<ul>' . "\n";

        foreach ($pageSiblings as $pageSibling) {
            $html .= '<li' . ($pageSibling->id == $page->id ? ' class="current"' : '') . '>';
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

    $html = '<li>'
          . '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
          . metadata($exhibitPage, 'title') .'</a>';

    $html .= '</li>';
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