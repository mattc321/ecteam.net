/**
 * @file
 * This file contains all jQuery for the tables.
 */

(function ($) {
  "use strict";

  Drupal.behaviors.openlucius_todo = {
    attach: function (context, settings) {

      /**
       * Function to get a list of node id's from the input.
       *
       * @param rows
       *   The rows which have to be checked.
       *
       * @returns {Array}
       *   Returns an of node id's.
       */
      function openlucius_todo_get_order(rows) {

        // Initialize array to hold the key values for the rows.
        var order = [];
        var onDashboard = $('.node-type-ol-group').length > 0;

        // Build a list for weight altering.
        rows.each(function() {

          var value;

          // Switch for dashboard as the dashboard has checkboxes.
          if (!onDashboard) {
            value = $(this).find('input[type="hidden"]').val();
          }
          else {
            value = $(this).find('input[type="checkbox"]').val();
          }
          if (value !== undefined) {
            order.push(value);
          }
        });

        return order;
      }

      if (context == document) {
        $('.ajax-close-todo').on('change', function () {

          // Get status and token.
          var input = $(this),
              status = input.attr('checked') == 'checked' ? 0 : 1,
              token = input.attr('data-token'),
              old_status = input.attr('data-status');

          // Post the toggle.
          $.post(Drupal.settings.basePath + 'todo/toggle/' + $(this).val(), {
            'token': token,
            'status': status,
            'old_status': old_status
          }, function (data) {

            // Update the html on success.
            if (data == true) {
              if (status == 0) {
                input.parents('tr').attr('class', 'todo-strike');
              }
              else {
                input.parents('tr').removeClass('todo-strike');
              }
            }
          });
        });

        // Check if todo moving between lists should be enabled.
        if (settings.hasOwnProperty('openlucius_todo_move_enabled')) {

          // Switch item selector as dashboards may not use the first item.
          var itemSelector = $('.node-type-ol-group').length > 0 ? '> tr:nth-child(n+1)' : '> tr';

          $(".views-table tbody")
            .sortable({
              connectWith: ".views-table tbody",
              items: itemSelector,
              appendTo: "parent",
              helper: "clone",
              cursor: "move",
              zIndex: 999990,
              receive: function (event, ui) {
                var todo_nid = $('input.nid', ui.item).val();
                var table = ui.item.parents('table'),
                    list_nid = table.attr('data-nid'),
                    token = table.attr('data-token'),
                    rows = $('tr', table);

                // Send the request for moving to drupal.
                $.post(Drupal.settings.basePath + 'todo/transfer/' + todo_nid, {
                    'token': token,
                    'new_list' : list_nid
                  }, function (data) {

                  // Update the html on success.
                  if (data == true) {

                    // Get the order of the todo's.
                    var order = openlucius_todo_get_order(rows);

                    // Update the weight of all items in this list.
                    $.post(Drupal.settings.basePath + 'todo/update-weights', {
                      'order' : order.join(),
                      'token' : token
                    }, function (data) {

                      // Todo do something with the response.
                    });
                  }
                });
              },
              update: function (event, ui) {
                // Check if the sender was null to prevent double updates.
                if (ui.sender == null) {
                  var table = ui.item.parents('table'),
                      token = table.attr('data-token'),
                      rows = $('tr', table);

                  // Get the order of the todo's.
                  var order = openlucius_todo_get_order(rows);

                  // Update the weight of all items in this list.
                  $.post(Drupal.settings.basePath + 'todo/update-weights', {
                    'order' : order.join(),
                    'token' : token
                  }, function (data) {

                    // Todo do something with the response.
                  });
                }
              }
            });
        }
      }
    }
  };
})(jQuery);
