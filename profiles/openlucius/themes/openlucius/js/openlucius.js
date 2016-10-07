/**
 * @file
 * This file contains all jQuery for the forms.
 */

(function ($) {
  "use strict";

  Drupal.behaviors.openlucius_theme = {
    attach: function (context, settings) {
      var timer = null;

      function initializeTrigger() {
        var _input = $('input, select'),
            _mce   = $('iframe').contents().find('body'),
            _body = $('body');

        // Fix buggy bootstrap behaviour for focus (happens on ipad and other devices).
        // Bind focus for input.
        _input.on('focus', function(e) {
          _body.addClass('fixfixed');
        });

        // Bind focus for tinymce.
        _mce.on('focus', function(e) {
          _body.addClass('fixfixed');
        });

        // Bind blur for input.
        _input.on('blur', function(e) {
          _body.removeClass('fixfixed');
        });

        // Bind blur for tinymce.
        _mce.on('blur', function(e) {
          _body.removeClass('fixfixed');
        });
      }

      $(document).ready(function() {
        var control_pressed = false;

        // Catch for control and command key.
        $(window).keydown(function(e) {
          if (e.ctrlKey || e.metaKey) {
            control_pressed = true;
          }
          else {
            control_pressed = false;
          }
        });

        // Init pretty selects.
        $('.selectpicker').selectpicker();

        // Attach group switch on change.
        var group_selector = $('#group_selector');
        group_selector.on('change', function() {
          document.location.href = $(this).val();
        });

        // Select all children items.
        var group_select_children = group_selector.parent().find('.dropdown-menu > li');

        // Attach new click event on link in group selector.
        $('option', group_selector).each(function(index) {
          var current = group_select_children[index];
          var link = $(current).find('a');

          // Add url to generated a element.
          link.attr('href', $(this).val());
          link.attr('target', '_blank');

          // Add click for opening in new tab.
          link.on('click', function() {

            if (control_pressed) {
              window.open($(this).attr('href'));

              // Return false for non windows browsers.
              if (navigator.appVersion.indexOf("Win") == -1) {
                return false;
              }
            }
          });
        });

        // Set focus trigger after mce load.
        if ($('textarea').length > 0) {
          window.setTimeout(initializeTrigger, 500);
        }
      });

      // Remove colons from field labels.
      $('.node-ol-todo .ol-content .field-label').each(function () {
        var text = $(this).html();
        $(this).html(text.replace(':', ''));
      });

      $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $(".row").toggleClass("toggled");
      });
    }
  };
})(jQuery);
