/**
 * @file
 * This file contains all jQuery for the notifications.
 */
(function ($) {
  Drupal.behaviors.openlucius_notification = {
    attach: function (context, settings) {
      // Trigger on click for both show-more and show-less links.
      $(".notified .show-more, .notified .show-less").click(function(e) {
        // Toggle the class 'shown' on the wrapper div (notified).
        $(this).parent().toggleClass("shown");
      });
    }
  };
})(jQuery);
