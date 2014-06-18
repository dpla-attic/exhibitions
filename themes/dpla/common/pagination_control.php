<?php if ($this->pageCount > 1): ?>
<div class="pagination">
        <!-- Numbered page links -->
        <?php foreach ($this->pagesInRange as $page): ?> 
        <?php if ($page != $this->current): ?>
        <a href="<?php echo html_escape($this->url(array('page' => $page), null, $_GET)); ?>"
           data-page="<?php echo $page; ?>"
           title="Go to page <?php echo $page; ?>"><?php echo $page; ?></a>
        <?php else: ?>
        <span class="current"><?php echo $page; ?></span>
        <?php endif; ?>
        <?php endforeach; ?>
        
</div>
<?php endif; ?>
