<?php echo head(array('title' => metadata('exhibit', 'title'), 'bodyid'=>'exhibit', 'bodyclass'=>'summary', 'layout' => 'layout fullWidth')); ?>

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

<article id="content" role="main">
	<div class="exhibition-overview">

		<div class="leftSide">
			
			<section>
				<img src="/themes/dpla/images/detail-img.jpg" alt="slide">
				<div class="caption">
					<a href="">Excluded Men and Women, Paupers, Convicts, Etc., Regulation of Immigration at the Port of Entry," United States Immigrant Station, New York City »</a>
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
