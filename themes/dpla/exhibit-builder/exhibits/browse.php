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
            <?php
            if ($homepage = dpla_get_exhibit_homepage($exhibit)) {
                if ($att = dpla_exhibit_page_thumbnail_att($homepage)) {
                    $thumbUri = $att['file_uri_notsquare'];
                }
            }
            ?>
            <div class="exibition-image">
            	<img alt="img" src="<?=$thumbUri?>"></img>
            </div>
	        <h5><?php echo link_to_exhibit(); ?></h5>
            <?php
            // exhibit description should be taken from exhibit Homepage
            if ($homepage) {
                if ($text = exhibit_builder_page_text(2, $homepage)) {
                    if(strlen($text) > 120) $text = substr($text, 0, 120).'...';
                } else if ($text = exhibit_builder_page_text(1, $homepage)) { // prefer Long description to Short
                    if(strlen($text) > 120) $text = substr($text, 0, 120).'...';
                }
                echo $text ? "<p>".$text."</p>" : "";
            }
            ?>
	        <?php echo link_to_exhibit('View Exhibit »'); ?>
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
