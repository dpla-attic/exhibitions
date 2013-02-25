<?php echo head(array('title' => metadata('exhibit', 'title'), 'bodyid'=>'exhibit', 'bodyclass'=>'summary', 'layout' => 'layoutTwo')); ?>

<div class="breadCrumbs">
    <ul>
        <li><a href="/">Exhibitions</a></li>
        <li><?php echo metadata('exhibit', 'title'); ?></li>
    </ul>
</div>
<ul class="shareSave">
    <li class="btn"><a href="">Share</a></li>
</ul>

<div class="upper-section">
	<h1><?php echo metadata('exhibit', 'title'); ?></h1>
</div>

<?php echo exhibit_builder_page_nav(); ?>

<?php if ($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true))): ?>
<article>
	<div class="exhibit-description">
	    <?php echo $exhibitDescription; ?>
	</div>
	<?php endif; ?>
	
	<?php if (($exhibitCredits = metadata('exhibit', 'credits'))): ?>
	<div class="exhibit-credits">
	    <h3><?php echo __('Credits'); ?></h3>
	    <p><?php echo $exhibitCredits; ?></p>
	</div>
</article>
<?php endif; ?>

<aside>
	<div class="module">
	<h6>Themes</h6>
	    <ul>
	        <?php set_exhibit_pages_for_loop_by_exhibit(); ?>
	        <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
	        <?php echo dpla_page_summary($exhibitPage); ?>
	        <?php endforeach; ?>
	    </ul>
	</div>
</aside>

<?php echo foot(); ?>
