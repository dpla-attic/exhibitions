<?php
function ve_exhibit_builder_exhibit_display_item($displayFilesOptions = array(), $linkProperties = array(), $titleOnly = false, $withoutTitle = false)
{
	$currentExhibit = get_current_record('exhibit', false);
	return "OK";
    $item = get_current_item();
    $fileIndex = 0; // Always just display the first file (may change this in future).
    $linkProperties['href'] = exhibit_builder_exhibit_item_uri($item);
    $displayFilesOptions['linkToFile'] = false; // Don't link to the file b/c it overrides the link to the item.
    $fileWrapperClass = null; // Pass null as the 3rd arg so that it doesn't output the item-file div.
    $file = $item->Files[$fileIndex];
    $zoomit_enabled = ve_exhibit_builder_zoomit_enabled();
    $html = '';

    if ($file) {
    	
    	
    	$mime = $file->getMimeType();
    	
    	if($titleOnly){ /* responsive change */
    		
    		if (preg_match("/^application/", $mime)){ // pdf don't have the info link, so we make the title a link
    			// pass along page param as this is not available from the item page but is necessary for building seo title and meta fields
    		    $page = exhibit_builder_get_current_page();
    		    $linkProperties['href'] = exhibit_builder_exhibit_item_uri($item) . '?page=' . urlencode($page->title);
    		    $html .= '<a class="return-to" rel="' . uri() . '" id="info-link"' . _tag_attributes($linkProperties) . ' title="' . ve_translate('show-item-details', 'Show item details') . '">';
    		    $html .= '<h6>';
    		    $html .= item('Dublin Core', 'Title');
    		    $html .= '</h6></a>';
    			return '<div id="exhibit-item-title-only">' . $html . '</div>';

   			}
    		else{    			
    			return '<div id="exhibit-item-title-only" class="meta"><h2>' . item('Dublin Core', 'Title') . '</h2></div>';
    		}
    	}
    	 

        if (preg_match("/^image/", $mime)) {
            // IMAGE
			$imgHtml	= display_file($file, $displayFilesOptions, $fileWrapperClass);
			
			$imgHtml	= str_replace('.jpg', '_euresponsive_1.jpg',			$imgHtml);
			$imgHtml	= str_replace('/fullsize/', '/euresponsive/',			$imgHtml);
			$imgHtml	= str_replace('class="full"', 'class="full tmp-img"',	$imgHtml);
			$imgHtml	= str_replace('alt=""', 'alt="' . item('Dublin Core', 'Title') . '"',	$imgHtml);

            $html .= '<div id="in-focus" class="image">';
			$html .= '<div id="media_wrapper">';
			$html .= 	'<div id="zoomit_window" style="width: 100%; height: 100%;">';
			$html .=	'</div>';
			$html .= 	'<script class="euresponsive-script">document.write("<" + "!--")</script>';
			$html .= 		'<noscript>';
			$html .=			$imgHtml;
			$html .=	'</noscript -->';                
			$html .= '</div>';

        }
        elseif (preg_match("/^audio/", $mime)) {
            // AUDIO
            $html .= '<div id="in-focus" class="player">';
            //            $html .= '<audio  controls="controls"  poster="' . file_display_uri($file, $format = 'fullsize') . '" type="audio/mp3" src="' . file_display_uri($file, $format = 'archive') . '" width="460" height="84"></audio>';
            $html .= '<audio  controls="controls"  type="audio/mp3" src="' . file_display_uri($file, $format = 'archive') . '" width="460" height="84" style="width:100%; height:100%;"></audio>';
        }
        elseif (preg_match("/^application/", $mime)) {
        	
        	// ipad fix
        	$html .= '<style>';
        	$html .= '#exhibit-item-infocus-item .theme-center-middle, #exhibit-item-infocus-item .theme-center-inner{width:100%;}';
        	$html .= '</style>';
        	
        	
        	$html .= '<div id="in-focus" class="pdf-viewer">';
       		if (class_exists('DocsViewerPlugin')){       			
       		   $docsViewer = new DocsViewerPlugin;
       			$html .= $docsViewer->getEmbed();
       		}
        }
        else {
            // VIDEO
        	
        	$videoSrc = file_display_uri($file, $format = 'archive'); 
        	
            $html .= '<div id="in-focus" class="player">';
            //$html .= '<a id="video-logo-link" href="http://europeana.eu"><img src="' . img("europeana-logo-en.png") . '"></a>';
            $html .= '<style>.mejs-overlay-loading{width:88px!important;}</style>';
            $html .= '<video  width="460" height="340" style="width:100%; height:100%;">';

            if(endsWith($videoSrc, '.mp4')){
            	$html .= '<source type="video/mp4" src="' . $videoSrc . '" />';
            }
            if(endsWith($videoSrc, '.webm')){
            	$html .= '<source type="video/webm" src="' . $videoSrc . '" />';
            }
            if(endsWith($videoSrc, '.ogv')){
            	$html .= '<source type="video/ogg" src="' . $videoSrc . '" />';
            }
            if(endsWith($videoSrc, '.ogv')){
            	$html .= '<source type="video/ogg" src="' . $videoSrc . '" />';
            }

            $html .=	'<object type="application/x-shockwave-flash" data="'.WEB_ROOT.'/themes/main/javascripts/mediaelement-2.7/build/flashmediaelement.swf">';
           	$html .=	'<param name="movie" value="'.WEB_ROOT.'/themes/main/javascripts/mediaelement-2.7/build/flashmediaelement.swf" />';
       		$html .=	'<param name="flashvars" value="controls=true&amp;file='. $videoSrc .'" />'; 		
   			$html .=	'<img src="'.WEB_ROOT.'/media/echo-hereweare.jpg" width="100%" height="auto;" alt="No video playback capabilities" title="No video playback capabilities" />';
			$html .=	'</object>';
            $html .= '</video>';
            
        }
        $html .= '</div>';
        
        error_log($html);
        
        if(!$withoutTitle){ /* responsive change */
        	$html .= '<div id="exhibit-item-title"><h4>' . item('Dublin Core', 'Title') . '</h4></div>';
        }
        
    } else {
        $html .= '<h1>' . item('Dublin Core', 'Title') . '</h1>';
    }    
    return $html;
}
?>