<?php
$themePage = get_current_record('exhibit_page');
$firstStoryPage = $themePage->getChildPages();
$firstStoryPage = $firstStoryPage[0];
$url = exhibit_builder_exhibit_uri(null, $firstStoryPage);
$r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
$r->gotoUrl($url)->redirectAndExit();
?>