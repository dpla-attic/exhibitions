<?php if ($attachment = exhibit_builder_page_attachment(1)): ?>
	<div class="slide-Container">
	    <div class="slidegallery slideshow-container">
	    	<div id="loading"></div>
	        <div id="slideshow" class="slides slideshow">
	        </div>
        	<div id="caption" class="caption"></div>
	        <div id="thumbs">
	            <?php echo dpla_thumbnail_gallery(1, 5, array('class'=>'permalink')); ?>
	        </div>
	    </div>
	</div>
<?php endif; ?>

<div class="slide_bottom">
	<?php echo exhibit_builder_page_text(2); ?>

	<ul class="prevNext">
		<?php // TODO: Define first and last pages ?>
        <? if ($prevLink = dpla_link_to_previous_page('« Prev')): ?>
		    <li class="btn"><?= $prevLink ?></li>
        <? endif; ?>
        <? if ($nextLink = dpla_link_to_next_page('Next »')): ?>
		    <li class="btn"><?= $nextLink ?></li>
        <? endif; ?>
		<li><?= dpla_page_position(); ?></li>
	</ul>


</div>

<?php
return;
$view = get_view();

echo exhibit_builder_thumbnail_gallery(1, 4, null, "original");

//include_once 'utils.php';
$exhibitPage = get_current_record('exhibit_page');
$currentExhibit = get_current_record('exhibit', false);
$topPages = $currentExhibit -> getTopPages();
$topPage = $topPages[0];
$url0 = $topPage -> getRecordUrl();

$url = exhibit_builder_exhibit_uri(null, $topPage);

//$url = "http://omeka.local/exhibits/show/exib1/themes";

// $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
// $r->gotoUrl($url)->redirectAndExit();

// $view->redirect($url);
$text = exhibit_builder_page_text();
// $parentPages = $exhibitPage->findPotentialParentPages($exhibitPage->id);
// $parentPages = get_db()->getTable('ExhibitPage')->findPotentialParentPages($exhibitPage->id);
$parentPage = $exhibitPage -> getParent();
//$parentPage = $parentPages[0];
$theme = $parentPage -> title;
$story = $exhibitPage -> title;
//$item = get_current_record('item');

//echo exhibit_builder_page_nav();
echo exhibit_builder_child_page_nav($parentPage);

$sss = exhibit_builder_page_attachment(4);
$item = $sss["item"];
$view = get_view();
echo flash();
echo output_format_list();
echo fire_plugin_hook('public_items_show', array('view' => $view, 'item' => $item));
echo fire_plugin_hook('admin_items_show_sidebar', array('item' => $item, 'view' => $view));

exit ;
?>
<div id="item-list">
<?php if (!has_loop_records('items')): ?>
    <p><?php echo __('There are no items to choose from.  Please refine your search or %s.', '<a href="' . html_escape(url('items/add')) .'">' . __('add some items') .'</a>') ?></p>
<?php endif; ?>
<?php foreach (loop('items') as $item): ?>
    <?php echo exhibit_builder_form_attachment($item, null, false); ?>
<?php endforeach; ?>
</div>
<?php
echo files_for_item();
?>

    <div id="exhibit-section-title">
        <h1 class="styled-text">
           <?php echo $theme . ' - ' . $story; ?>
        </h1>
    </div>
</div> <!-- end row -->


<!--[if IE 8]>

<style type="text/css">
	#exhibit-item-thumbnails .exhibit-item {
		float:none !important;	
	}
	#exhibit-item-thumbnails{
		white-space:nowrap;
	}
</style>

<![endif]-->

<!--[if lte IE 7]>
<style type="text/css">
	body{
		/* disable responsive behaviour (limit size to stop layout breaking) */
		min-width:	768px;
	}		
	
	#story{
		max-width:47%;
	}

</style>
<![endif]-->



<div class="row">
	<div class="six columns push-six" id="story">
		<?php 
		// if (exhibit_builder_use_exhibit_page_item(1)): 
	    if (true):
		?>
		<div id="exhibit-item-infocus" class="exhibit-item">
			<table id="tbl-exhibit-item"> <!-- yes, a table! -->
				<tr>
					<td class="navigate">
						<?php
						// echo ve_exhibit_builder_link_to_previous_exhibit_page("&larr;", array('class' => 'exhibit-text-nav'));
						?>
						<?php echo exhibit_builder_link_to_previous_page("&larr;", array('class' => 'exhibit-text-nav')); ?>
						
					</td>
					<td class="content">
						<div id="exhibit-item-infocus-item">
							<div class="theme-center-outer">		
								<div class="theme-center-middle">		
									<div class="theme-center-inner">		
										<div id="exhibit-item-infocus-header">
											<?php
											// echo ve_exhibit_builder_exhibit_display_item_info_link(array('imageSize' => 'fullsize'));
											?>
										</div>
										<?php
											// echo ve_exhibit_builder_exhibit_display_item(array('imageSize' => 'fullsize'), array('class' => 'box', 'id' => 'img-large', 'name' => 'exhibit-item-metadata-1'), false, true);
										?>
									</div>
								</div>
							</div>
			
						</div>
					</td>
					<td class="navigate">
						<?php
						// echo ve_exhibit_builder_link_to_next_exhibit_page("&rarr;", array('class' => 'exhibit-text-nav'));
						echo exhibit_builder_link_to_next_page("&rarr;", array('class' => 'exhibit-text-nav'));
						?>
					</td>
				</tr>
				
				<tr>
					<td class="navigate">
					</td>
					<td class="content">
						<br>
						<div class="theme-center-outer">		
							<div class="theme-center-middle">		
								<div class="theme-center-inner"  style="text-align:center;">		
									<?php echo ve_exhibit_builder_exhibit_display_item(array('imageSize' => 'fullsize'), array('class' => 'box', 'id' => 'img-large', 'name' => 'exhibit-item-metadata-1'), true, false); ?>
								</div>
							</div>
						</div>
			
					</td>
					<td class="navigate">
					</td>
				</tr>
				
			</table>
		</div> <!--  end exhibit-item-infocus -->
		
		<div class="row"> <!-- embedded row -->
			<div id="mobile_shares" class="twelve columns">
				<div class="theme-center-outer">
					<div class="theme-center-middle">
	    	    		<div class="theme-center-inner">
							<?php echo getAddThisMobile(); ?>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- embedded row -->
		
		<?php endif; ?>
    
		<div class="clear"></div>
		
		
		<?php echo ve_exhibit_builder_display_exhibit_thumbnail_gallery(1, 5, array('class' => 'thumb')); ?>
		
	    <script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("#exhibit-item-thumbnails").css("max-width", galleryItemCount * 100 + "px");
				//jQuery("#exhibit-item-thumbnails").css("width", 	galleryItemCount * 100 + "px");
			});
	    </script>

	</div> <!-- end six columns -->

    
	<div class="six columns pull-six" id="items">
		<div class="exhibit-text">
			<div id="exhibit-section-title-small">
				<h3>
					<?php echo $theme . ' - ' . $story; ?>
				</h3>
			</div>

			<?php
	if ($text = exhibit_builder_page_text(1)) {
		echo exhibit_builder_page_text(1);
	}
 ?>
		</div>

		
		<div class="theme-center-outer">
			<div class="theme-center-middle">
				<div class="theme-center-inner">
					<div class="exhibit-page-nav">
						<div class="story-nav-wrapper">

							<?php echo ve_exhibit_builder_responsive_link_to_previous_exhibit_page("&larr;", array('class' => 'exhibit-text-nav')); ?>
							<?php echo ve_exhibit_builder_responsive_page_nav(); ?>
							<?php echo ve_exhibit_builder_responsive_link_to_next_exhibit_page("&rarr;", array('class' => 'exhibit-text-nav')); ?>
							
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="comments-full">
			<?php
			if (exhibit_builder_use_exhibit_page_item(1)) {
				if (ve_get_comments_allowed()) {
					try {
						commenting_echo_comments();
						commenting_echo_comment_form();
					} catch (Exception $e) {
						echo('Error: ' . $e -> getMessage());
					}
				}
			}
			?>		
		</div>
	</div> <!-- end six columns -->
	
</div>	
<?php echo js('seadragon-min'); ?>
<?php echo js('story'); ?>

	


<div class="row">
	<div class="twelve columns">
		<div class="comments-collapsed">
			<?php
			if (exhibit_builder_use_exhibit_page_item(1)) {
				if (ve_get_comments_allowed()) {
					try {
						commenting_echo_comments();
						commenting_echo_comment_form();
					} catch (Exception $e) {
						echo('Error: ' . $e -> getMessage());
					}
				}
			}
			?>
		</div>
	</div>
</div>



<script type="text/javascript">
		jQuery(document).ready(function(){
		var zoomitEnabled = <?php echo ve_exhibit_builder_zoomit_enabled() ?>;
		story.initStory("<?php echo file_display_uri(get_current_item() -> Files[0]); ?>
			", zoomitEnabled);
			});

</script>

<script type="text/javascript">
			jQuery(document).ready(function($) {
				// We only want these styles applied when javascript is enabled
				$('div.navigation').css({'width' : '300px', 'float' : 'left'});
				$('div.content').css('display', 'block');

				// Initially set opacity on thumbs and add
				// additional styling for hover effect on thumbs
				var onMouseOutOpacity = 0.67;
				console.log($('ul.thumbs li'));
				$('ul.thumbs li').opacityrollover({
					mouseOutOpacity:   onMouseOutOpacity,
					mouseOverOpacity:  1.0,
					fadeSpeed:         'fast',
					exemptionSelector: '.selected'
				});
				
				// Initialize Advanced Galleriffic Gallery
				var gallery = $('#thumbs').galleriffic({
					delay:                     2500,
					numThumbs:                 15,
					preloadAhead:              10,
					enableTopPager:            true,
					enableBottomPager:         true,
					maxPagesToShow:            7,
					imageContainerSel:         '#slideshow',
					controlsContainerSel:      '#controls',
					captionContainerSel:       '#caption',
					loadingContainerSel:       '#loading',
					renderSSControls:          true,
					renderNavControls:         true,
					enableHistory:             false,
					autoStart:                 false,
					syncTransitions:           true,
					defaultTransitionDuration: 900,
					onSlideChange:             function(prevIndex, nextIndex) {
						// 'this' refers to the gallery, which is an extension of $('#thumbs')
						this.find('ul.thumbs').children()
							.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
							.eq(nextIndex).fadeTo('fast', 1.0);
					},
					onPageTransitionOut:       function(callback) {
						this.fadeTo('fast', 0.0, callback);
					},
					onPageTransitionIn:        function() {
						this.fadeTo('fast', 1.0);
					}
				});
			});
		</script>