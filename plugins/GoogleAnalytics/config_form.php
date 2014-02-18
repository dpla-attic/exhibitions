<div id="ga_config_form">
  <div class="field">
    <div class="two columns alpha">
      <?php echo get_view()->formLabel('google_analytics_key', 'Google Analytics Key:'); ?>
    </div>
    <div class="inputs five columns omega">
      <?php echo get_view()->formText('google_analytics_key', get_option('google_analytics_key')); ?>
      <p class="explanation">
        <?php echo __('The key provided by <a href="http://www.google.com/analytics">Google Analytics</a>'); ?>
      </p>
    </div>
  </div>
</div>
