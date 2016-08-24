# GoogleAnalytics

Track your Omeka site easily with [Google Analytics](http://www.google.com/analytics/). 

## Installation

+ Unzip or clone the GoogleAnalytics plugin into Omeka's plugin directory. Rename the newly installed folder  `GoogleAnalytics`.
+ Log into Omeka as a [super user](http://omeka.org/codex/User_Roles) and activate the plugin. (see [Installing a Plugin](http://omeka.org/codex/Installing_a_Plugin)).
+ Configure the plugin with your Google Analytics key

## Usage
As long as the theme you are using yields for the 'public_theme_footer' plugin hook, nothing else is needed in order to use this plugin to collect analytics for Google. If not, you will need to add this line to the bottom of your footer script:

    <?php echo plugin_footer(); ?>


