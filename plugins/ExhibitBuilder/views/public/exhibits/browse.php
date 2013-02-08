<?php
$title = __('Browse Exhibits');
echo head(array('title' => $title, 'bodyid' => 'exhibit', 'bodyclass' => 'browse'));
?>
<h1><?php echo $title; ?> <?php echo __('(%s total)', $total_results); ?></h1>
<?php if (count($exhibits) > 0): ?>

<nav class="navigation" id="secondary-nav">
    <?php echo nav(array(
        array(
            'label' => __('Browse All'),
            'uri' => url('exhibits')
        ),
        array(
            'label' => __('Browse by Tag'),
            'uri' => url('exhibits/tags')
        )
    )); ?>
</nav>

<div class="pagination"><?php echo pagination_links(); ?></div>

<?php $exhibitCount = 0; ?>
<?php foreach (loop('exhibit') as $exhibit): ?>
    <?php $exhibitCount++; ?>
    
<?php        	
//include_once 'utils.php';
//$exhibitPage = get_current_record('exhibit_page');

$currentExhibit = get_current_record('exhibit', false);
$topPages = $currentExhibit -> getTopPages();
$topPage = $topPages[0];
$attachment = exhibit_builder_page_attachment(1,0,$topPage);
$html = ""; // TODO what value?
$html .= "\n" . '<div class="exhibit-item">';
if ($attachment['file']) {
    $thumbnailType = "square_thumbnail";
    $props = null;
    $thumbnail = file_image($thumbnailType, $props, $attachment['file']);
    $html .= exhibit_builder_link_to_exhibit_item($thumbnail, array(), $attachment['item']);
}
$html .= exhibit_builder_attachment_caption($attachment);
$html .= '</div>' . "\n";
echo $html;

echo exhibit_builder_page_text(1, $topPage);
?>    
    
    
    <div class="exhibit <?php if ($exhibitCount%2==1) echo ' even'; else echo ' odd'; ?>">
        <h2><?php echo link_to_exhibit(); ?></h2>
        <?php if ($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true))): ?>



// $attachment = exhibit_builder_page_attachment(2,0,$topPage);

//echo exhibit_builder_thumbnail_gallery(1, 1, null, "original");

//$url0 = $topPage -> getRecordUrl();        	
?>        	
        	
        <div class="description"><?php echo $exhibitDescription; ?></div>
        <?php endif; ?>
        <?php if ($exhibitTags = tag_string('exhibit', 'exhibits')): ?>
        <p class="tags"><?php echo $exhibitTags; ?></p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<div class="pagination"><?php echo pagination_links(); ?></div>

<?php else: ?>
<p><?php echo __('There are no exhibits available yet.'); ?></p>
<?php endif; ?>

<?php echo foot(); ?>
