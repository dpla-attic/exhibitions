<?php

class GoogleAnalytics_AllTests extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        $suite = new GoogleAnalytics_AllTests('GoogleAnalytics Tests');
        $testCollector = new PHPUnit_Runner_IncludePathTestCollector(
          array(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cases');  
        );
        
        $suite->addTestFiles($testCollector->collectTests());
        return $suite;
    }
}