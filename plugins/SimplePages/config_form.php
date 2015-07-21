<div class="field">
    <div id="simple_pages_filter_page_content_label" class="two columns alpha">
        <label for="simple_pages_filter_page_content"><?php echo __('Filter User Input For Page Content?'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __(
            'If checked, this plugin will filter all simple pages using the HTML ' 
          . 'filters set on the security settings page.'
        ); ?></p>
        <?php echo get_view()->formCheckbox('simple_pages_filter_page_content', true, 
        array('checked'=>(boolean)get_option('simple_pages_filter_page_content'))); ?>
    </div>
</div>
