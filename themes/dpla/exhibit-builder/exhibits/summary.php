<?php echo head(array('title' => metadata('exhibit', 'title'), 'bodyid'=>'exhibit', 'bodyclass'=>'summary', 'layout' => 'layout fullWidth')); ?>

<div class="breadCrumbs">
    <ul>
        <li><a href="<?=Zend_Registry::get('bootstrap')->getResource('Config')->dpla->exhibitionsUrl?>">Exhibitions</a></li>
        <li><?php echo metadata('exhibit', 'title'); ?></li>
    </ul>
</div>
<ul class="shareSave">
    <li class="btn">
        <a href="">Share</a>
		<ul>
	        <li><div class="sharebtn"><div id="fb-root"></div><div class="fb-like" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div></div></li>
	        <li><div class="sharebtn"><a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div></li>
	        <li><div class="sharebtn"><div class="g-plusone" data-size="medium"></div></div></li>
        </ul>
    </li>
</ul>

<div class="upper-section">
	<h1><?php echo metadata('exhibit', 'title'); ?></h1>
</div>

<?php echo exhibit_builder_page_nav(); ?>

<article id="content" role="main">
	<div class="exhibition-overview">

		<div class="leftSide">
			<section>
                <?php
                    // get current exhibition thumbnail URI and caption, or display default image and caption
                    if ($homepage = dpla_get_exhibit_homepage()) {
                        if ($att = dpla_exhibit_page_thumbnail_att($homepage)) {
                            $thumbUri = $att['file_uri_notsquare'];
                            $thumbCaption = isset($att['caption']) ? $att['caption'] : metadata('exhibit', 'title');
                            $thumbItemUri = $att['item_uri'];
                        }
                    }
                ?>
                <img src="<?=$thumbUri?>" alt="slide">
                <div class="caption">
                    <?=$thumbCaption ?>
                </div>
			</section>
		</div>

		<div class="rightSide">

            <div class="exhibit-description">
                <?php
                // exhibit description should be taken from exhibit Homepage
                if ($homepage) {
                    if ($text = exhibit_builder_page_text(1, $homepage)) { // prefer Long description to Short
                        echo $text;
                    } else if ($text = exhibit_builder_page_text(2, $homepage)) {
                        echo $text;
                    }
                }
                ?>
            </div>

            <?php
            // we have to support legacy data model. Some exhibit pages in production contains 2 descriptions instead of 1
            $external_uri = exhibit_builder_page_text(2, $homepage);
            $external_uri = strip_tags($external_uri);
            ?>

            <?php if ($external_uri && strpos($external_uri, 'http') !== FALSE):
                //    just to make sure it's actually a link
            ?>
                <ul class="prevNext">
                    <li class="btn"><a href="<?=$external_uri?>">View Exhibition</a></li>
                </ul>
            <?php endif; ?>

        </div>

        <div class="lowerSection">
            <?php set_exhibit_pages_for_loop_by_exhibit(); ?>
            <?php
                $pagesCount = 0; 
                $thumbsList = '<ul class="thumbs-list">';
                
                foreach (loop('exhibit_page') as $exhibitPage) {
                    if ($exhibitPage->layout != dpla_exhibit_homepage_layout_name()) {
                        $pagesCount++;
                        $thumbsList .= '<li class="thumbs-item thumbs-item-'. $pagesCount . '">'
                                  . dpla_page_summary($exhibitPage)
                                  .'</li>';
                    }
                }
                $thumbsList .= '</ul>';
            ?>

            <?php if ($pagesCount > 0): ?>
                <div class="module overview overview-<?php echo $pagesCount; ?>">
                    <?php echo $thumbsList; ?>
                </div>
            <?php endif; ?>

            <?php if (($exhibitCredits = metadata('exhibit', 'credits'))): ?>
                <div class="exhibit-credits">
                    <h5><?php echo __('Credits'); ?></h5>
                    <p><?php echo $exhibitCredits; ?></p>
                </div>
            <?php endif; ?>
            <?php if (($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true)))): ?>
                <div class="exhibit-description">
                    <h5>Citation</h5>
                    <?php echo $exhibitDescription; ?>
                </div>
            <?php endif; ?>
        </div>

	</div>
</article>

<?php echo foot(); ?>
