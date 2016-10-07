/**
 * @file
 * This file contains all JQuery based functionality for files.
 */
(function ($) {
  "use strict";

  // Initialize timeout and active.
  var timeOut = null, active = null;

  /**
   * function to display tooltip on success
   * @param {Object} target
   * @param {Object} message
   */
  function displayTooltip(target, message) {
    // Apply position.
    active.parent().css('position', 'relative');

    // Apply tooltip.
    active.parent().append('\
    <div class="tooltip right" style="top: -2px;">\
      <div class="tooltip-inner">\
        ' + message + '\
      </div>\
      <div class="tooltip-arrow"></div>\
    </div>');

    // Apply animation.
    active.parent().find('.tooltip').css({
      'opacity' : '1',
      'left'    : active.width()
    }).animate({
      'opacity' : 0
    }, 1500, function() {
      $(this).remove();
    });
  }

  /**
   * custom function to prettify selects
   */
  function prettify_folder_selects() {
    // Intiate vars.
    var folders, option, current;

    // Check if we have folders.
    if ($('.swapfolder').length > 0) {

      $('.swapfolder').each(function() {
        // Get option.
        option = $(this).val();

        // Select corrent li.
        $('li[data-nid=' + option + ']', $(this).parents('tr')).addClass('selected');
      });

      // Close on mouseout.
      $('.custom-select').hover(
        function(e){
          // Do the mouse enter things here.
        },
        function(e) {
          $(this).removeClass('open');
        }
      );

      // Bind click for real select.
      $('.custom-select li').bind('click', function() {
        if (!$(this).parent().hasClass('open')) {
          // Close other.
          $('.custom-select.open').removeClass('open');

          // Open this one.
          $(this).parent().addClass('open');
        }
        else {
          // Remove other selected from parent.
          $(this).parent().children('li').removeClass('selected');

          // Add selected to this one.
          $(this).addClass('selected');

          // Select correct option.
          current = $('option[value=' + $(this).attr('data-nid') + ']', $(this).parents('tr'));

          // Select.
          current.attr('selected', true);

          // Trigger change.
          current.parent().trigger('change');

          // Close select.
          $(this).parent().removeClass('open');
        }
      });
    }
  }

  Drupal.behaviors.ol_files = {
    attach: function (context, settings) {

      // Bind to ready state.
      $(document).ready(function() {

        // Bind confirm to delete links.
        $('.remove-file-link').bind('click', function() {
          var res = confirm($(this).attr('data-confirm'));

          // Return false if canceled.
          if (!res) {
            return false;
          }
        });

        // Make pretty.
        prettify_folder_selects();

        // Bind folderswap on click.
        $('select.swapfolder').bind('change', function() {
          // Set active.
          active = $(this);

          // Get nid.
          var folder = active.val(),
              token  = active.attr('data-token'),
              fid    = active.parents('tr').find('.fid').val();

          $.post(Drupal.settings.basePath + 'folder-swap', {'fid' : fid, 'folder' : folder, 'token' : token}, function(data) {
            // Create tooltip on success.
            if (data == true) {
              // Display tooltip for 3 seconds.
              displayTooltip(active, $('#updated-message').val());
            }
          });
        });
      });
    }
  };
})(jQuery);
