<?php echo head(array('title' => metadata('exhibit', 'title'), 'bodyid'=>'exhibit', 'bodyclass'=>'summary', 'layout' => 'layout fullWidth')); ?>

<div class="breadCrumbs">
    <ul>
        <li><a href="/">Exhibitions</a></li>
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
                            $thumbUri = $att['file_uri'];
                            $thumbCaption = $att['caption'] ? $att['caption'] : metadata('exhibit', 'title');
                            $thumbItemUri = $att['item_uri'];
                        }
                    }
                ?>
                <img src="<?=$thumbUri?>" alt="slide">
                <div class="caption">
                    <a href="<?=$thumbItemUri ?>"><?=$thumbCaption ?></a>
                </div>
			</section>

			<?php if (($exhibitCredits = metadata('exhibit', 'credits'))): ?>
				<div class="exhibit-credits">
				    <h5><?php echo __('Credits'); ?></h5>
				    <p><?php echo $exhibitCredits; ?></p>
				</div>
			<?php endif; ?>
		</div>

		<div class="rightSide">

			<?php if ($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true))): ?>
				<div class="exhibit-description">
				    <?php echo $exhibitDescription; ?>
				</div>
			<?php endif; ?>

			<div class="module overview">
				<h2>Choose a theme</h2>
				<ul class="thumbs-list">
			        <?php set_exhibit_pages_for_loop_by_exhibit(); ?>
			        <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
			        <?php echo dpla_page_summary($exhibitPage); ?>
			        <?php endforeach; ?>
			    </ul>
			</div>

		</div>

	</div>
</article>

<?php echo foot(); ?>
