<?php
add_plugin_hook('admin_theme_header', 'LcshPlugin::adminThemeHeader');
add_filter(array('Form', 'Item', 'Dublin Core', 'Subject'), 'LcshPlugin::filterDcSubject');

class LcshPlugin
{
    public static function adminThemeHeader()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        // Only add the JS and style to item form pages.
        if (!($controller == 'items' && ($action == 'edit' || $action == 'add'))) {
            return;
        }

        $db = get_db();
        $dcSubject = $db->getTable('Element')->findByElementSetNameAndElementName('Dublin Core', 'Subject');
?>
<style type="text/css">
.ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
}
</style>
<?php __v()->headScript()->captureStart(); ?>
// Add an autocompleter to all Dublin Core:Subject form inputs.
jQuery(document).bind('omeka:elementformload', function(event) {
    jQuery('#element-<?php echo $dcSubject->id; ?> input[type="text"]').autocomplete({
        minLength: 3,
        source: <?php echo js_escape(uri('lcsh/index/lcsh-proxy/')); ?>
    });
});
<?php __v()->headScript()->captureEnd(); ?>
<?php
    }

    /**
     * Replaces an input with a simple text input.
     *
     * Used to replace Dublin Core Subject textareas with single-line inputs.
     */
    public static function filterDcSubject($html, $inputNameStem, $value, 
                                           $options, $record, $element)
    {
        return __v()->formText($inputNameStem . '[text]',
                               $value, 
                               array('size' => '50', 'class' => 'textinput'));
    }
}
