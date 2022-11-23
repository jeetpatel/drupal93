(function ($, Drupal, once) {

$.fn.myAjaxCallback = function(argument) {
  console.log('Ajax myAjaxCallback is called with value::' + argument);
  $('input#edit-output').attr('value', argument);
};
  
  Drupal.behaviors.drupal_once = {
    attach: function (context, settings) {
//      once('myCustomBehavior', 'input.myCustomBehavior', context).forEach(function () {
//        // Apply the myCustomBehaviour effect to the elements only once.
//      });
    console.log('Drupal once called ');
    }
  };
})(jQuery, Drupal, once);