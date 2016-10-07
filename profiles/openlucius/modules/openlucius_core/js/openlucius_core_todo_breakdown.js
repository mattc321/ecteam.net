/**
 * @file
 * This file contains all jQuery for the todo breakdown.
 */
(function ($) {
  Drupal.behaviors.openlucius_core_todo_breakdown = {
    attach: function (context, settings) {

      // Get selected text function.
      function getSelected() {
        if (window.getSelection) {
          return window.getSelection().toString();
        }
        if (document.getSelection) {
          return document.getSelection().toString();
        }
        if (document.selection) {
          return document.selection.createRange().text;
        }
      }

      // Trigger on click of button.
      $('.todo-breakdown').on('click', function () {

        // Get the selected text.
        var text = getSelected();

        // Check if there is selected text.
        if (text != '') {

          // Append throbber.
          $('body').append('<div class="openlucius_throbber"><i id="spinner" class="glyphicon glyphicon glyphicon-cog"></i></div>');

          // Get the nid & token from the data aria.
          var button = $(this),
              id = button.attr('data-id'),
              gid = button.attr('data-gid'),
              type = button.attr('data-type'),
              token = button.attr('data-token'),
              success  = button.attr('data-success');

          // Post to comment or node.
          $.post(Drupal.settings.basePath + type + '/' + id + '/breakdown', {
            'token': token,
            'text': text
          }, function (data) {

            // Add the success message at the top of the page.
            if (type == 'node') {
              $(".page-header").after('<div class="alert alert-block alert-success"><a class="close" data-dismiss="alert" href="#">×</a><h4 class="element-invisible">Statusbericht</h4>' + success + '</div>');
              $('.openlucius_throbber').remove();
            }

            // Add the success message at the top of the comment.
            if (type == 'comment') {
              button.parents('.content').before('<div class="alert alert-block alert-success"><a class="close" data-dismiss="alert" href="#">×</a><h4 class="element-invisible">Statusbericht</h4>' + success + '</div>');
              $('.openlucius_throbber').remove();
            }

            // Reload the page to see the new reference.
            location.reload(true);
          });
        }
      });
    }
  };
})(jQuery);
