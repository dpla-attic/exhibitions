/*
 * Tracks custom events with Google Analytics.
 * This application uses the GoogleAnalytics plugin to track general usage
 * data. This file defines additional user interations to be tracked with Google
 * Analtyics.
 * See https://developers.google.com/analytics/devguides/collection/analyticsjs/events
 */

jQuery(function($) {

  $(document).ready(function(){
    trackViewExhibitionItemEvent(trackableItems());
  });

  /*
   * Get all DOM elements representing trackable items.
   *
   * This assumes that all relevant object have a 'data-item-id' attribute,
   * whether or not the value of that attribute is a valid id.
   * Objects with 'data-item-id=""' or 'data-item-id=null' will be returned.
   *
   * @return [jQuery Object] an enumerable containing zero to many DOM objects.
   */
  var trackableItems = function(){
    return $("[data-item-id]");
  };

  /* 
   * Track view of an item within an exhibition.
   * Relies on data-* attributes embedded in the HTML of the page.
   *
   * @param [jQuery Object] an enumerable containing zero to many DOM objects.
   */
  var trackViewExhibitionItemEvent = function(items){
    /* 
     * 'ga' must be defined for a signal to be sent to Google Analtyics.
     * It is set by the GoogleAnaltyics plugin.
     */
    if (typeof ga !== 'undefined') {

      items.each(function(){
        // 'this' represents a single DOM object with a data-item-id attribute
        var provider = $(this).attr("data-provider") || "undefined";
        var item_id = $(this).attr("data-item-id") || "undefined";
        var title = $(this).attr("data-title") || "undefined";
        var category = "View Exhibition Item : " + provider;
        var action = $(this).attr("data-data-provider") || "undefined";
        var label = item_id + " : " + title;

        ga('send', 'event', category, action, label);
      });
    }
  };
});
