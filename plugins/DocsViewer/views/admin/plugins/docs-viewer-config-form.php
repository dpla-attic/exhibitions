<h2>Admin Theme</h2>

<div class="field">
    <div id="docsviewer_embed_admin_label" class="two columns alpha">
        <label for="docsviewer_embed_admin"><?php echo __('Embed viewer in admin item show pages?'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <?php echo $this->formCheckbox('docsviewer_embed_admin', null, 
        array('checked' => (bool) get_option('docsviewer_embed_admin'))); ?>
    </div>
</div>
<div class="field">
    <div id="docsviewer_width_admin_label" class="two columns alpha">
        <label for="docsviewer_width_admin"><?php echo __('Viewer width, in pixels'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <?php echo $this->formText('docsviewer_width_admin', get_option('docsviewer_width_admin')); ?>
    </div>
</div>
<div class="field">
    <div id="docsviewer_height_admin_label" class="two columns alpha">
        <label for="docsviewer_height_admin"><?php echo __('Viewer height, in pixels'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <?php echo $this->formText('docsviewer_height_admin', get_option('docsviewer_height_admin')); ?>
    </div>
</div>

<h2>Public Theme</h2>

<div class="field">
    <div id="docsviewer_embed_public_label" class="two columns alpha">
        <label for="docsviewer_embed_public"><?php echo __('Embed viewer in public item show pages?'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <?php echo $this->formCheckbox('docsviewer_embed_public', null, 
        array('checked' => (bool) get_option('docsviewer_embed_public'))); ?>
    </div>
</div>
<div class="field">
    <div id="docsviewer_width_public_label" class="two columns alpha">
        <label for="docsviewer_width_public"><?php echo __('Viewer width, in pixels'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <?php echo $this->formText('docsviewer_width_public', get_option('docsviewer_width_public')); ?>
    </div>
</div>
<div class="field">
    <div id="docsviewer_height_public_label" class="two columns alpha">
        <label for="docsviewer_height_public"><?php echo __('Viewer height, in pixels'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <?php echo $this->formText('docsviewer_height_public', get_option('docsviewer_height_public')); ?>
    </div>
</div>
<p><?php echo __('By using this service you acknowledge that you have read and agreed to the %sGoogle Docs Viewer Terms of Service%s.', '<a href="http://www.google.com/intl/en/policies/terms/">', '</a>'); ?></p>
