/**
 * @file
 * tech behaviors.
 */

(function ($, Drupal, drupalSettings, once) {

  'use strict';

  /**
   * Behavior description.
   */
  Drupal.behaviors.tech = {
    attach: function (context, settings, drupalSettings) {
      $(window).on('load', function() {
        console.log("settings");
        console.log(settings);
      });
      $('#once_click', context).click(function () {
        console.log("Once link clicked");
      });
    }
  };

} (jQuery, Drupal, drupalSettings, once));
