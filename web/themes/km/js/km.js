/**
 * @file
 * km behaviors.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Behavior description.
   */
  Drupal.behaviors.km = {
    attach: function (context, settings) {
      console.log('It works!');
      console.log(settings);
    }
  };

} (jQuery, Drupal, drupalSettings));
