<?php
$title = __('Exhibitions');
echo head(array('title' => $title, 'bodyid' => 'exhibit', 'bodyclass' => 'browse'));
?>
<article>
    <?php fire_plugin_hook('public_content_top'); ?>

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
	<h1 class="border"><?php echo $title; ?></h1>
	<h4><?php echo option('description');?></h4>
	<?php if (count($exhibits) > 0): ?>

	<?php echo pagination_links(); ?>

	<?php $exhibitCount = 0; ?>
	<div class="moduleContainer threeCol">
	<?php foreach (loop('exhibit') as $exhibit): ?>
	    <?php $exhibitCount++; ?>

	    

	    <section class="module blue <?php if ($exhibitCount%2==1) echo ' even'; else echo ' odd'; ?>">
	        <h5><?php echo link_to_exhibit(); ?></h5>
	        <?php if ($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true))): ?>
	        	<p><?php echo strip_tags($exhibitDescription); ?></p>
	        <?php endif; ?>
	        <?php echo link_to_exhibit('View Exhibit »'); ?>
	        <?php if ($exhibitTags = tag_string('exhibit', 'exhibits')): ?>
	        	<p class="tags"><?php echo $exhibitTags; ?></p>
	        <?php endif; ?>
	    </section>

		<?php // TODO: Find a better way to make rows of 3 items ?>
		<?php if (($exhibitCount%3==0) && $exhibitCount < count($exhibits)): ?>
		</div>
		<div class="moduleContainer threeCol">
		<?php endif; ?>

	<?php endforeach; ?>
	</div>
	<?php echo pagination_links(); ?>

	<?php else: ?>
	<p><?php echo __('There are no exhibits available yet.'); ?></p>
	<?php endif; ?>
</article>
<?php echo foot(); ?>
