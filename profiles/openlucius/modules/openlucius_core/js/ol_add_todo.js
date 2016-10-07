/**
 * @file
 * This file contains all jQuery for the todos.
 */
(function ($) {
  Drupal.behaviors.ol_add_todo = {
    attach: function (context, settings) {

      var extra_fields = [];
      // Load any fields supplied by other modules.
      if(settings.ol_add_todo) {
        for (index in settings.ol_add_todo) {
          var value = settings.ol_add_todo[index];
          var index = index.split('data-')[1];
          extra_fields[index] = value;
        }
      }

      $('.todo_submit').mousedown(function() {
        $('.todo_input_field').val('');
      });

      function toggleFields(id, start) {
        // Display the form for the current 'id'.
        if (start !== true) {
          $('.show_form-' + id).toggle();
        }
        $('.todo_input_field-' + id).toggle();
        $('.todo_assign_to-' + id).toggle();
        $('.todo_due_date-' + id).toggle();
        $('.todo_submit-' + id).toggle();
        $('.hide_form-' + id).toggle();
        // Also toggle the extra fields.
        if (extra_fields.length) {
          $.each(extra_fields[id], function (index, value) {
            $('.' + value + id).toggle();
          });
        }
      }

      $('.show_form').each(function (index, value) {
        var string = $(this).attr('class').split(' ')[1];
        // Split this class on a '-' to get the id.
        toggleFields(string.split('-')[1], true);
      });

      $('.todo_input_field').keyup(function(e) {
        if (e.keyCode == 13) {
          var string = $(this).attr('class').split(' ')[1];
          $('.todo_submit-' + string.split('-')[1]).trigger('mousedown');
        }
      });

      // When clicked on the add todo here button, show the form.
      $('.show_form, .hide_form').click(function(e) {
        e.preventDefault();
        // Get the class that has the id of the element.
        var string = $(this).attr('class').split(' ')[1];

        // Split this class on a '-' to get the id.
        var id = string.split('-')[1];

        // Get the part before the id to determine hide or show.
        var which = string.split('-')[0];

        if (which == 'show_form') {

          // Show this form.
          toggleFields(id);

          // Next six lines will hide the other elements since they're not needed.
          $('.show_form').not($('.show_form-' + id)).show();
          $('.todo_input_field').not($('.todo_input_field-' + id)).hide();
          $('.todo_assign_to').not($('.todo_assign_to-' + id)).hide();
          $('.todo_due_date').not($('.todo_due_date-' + id)).hide();
          $('.todo_submit').not($('.todo_submit-' + id)).hide();
          $('.hide_form').not($('.hide_form-' + id)).hide();
          // Hide any extra fields from unneeded forms.
          if (extra_fields.length) {
            for (index in extra_fields) {
              if (index !== id) {
                $.each(extra_fields[index], function (index2, value) {
                  $('.' + value + index).hide();
                });
              }
            }
          }

          // Set the input field for the current form 'id' to be active.
          $('.todo_input_field-' + id).focus();
        }
        else if (which == 'hide_form') {
          // Hide the form for the current 'id'.
          toggleFields(id);
        }
      });
    }
  };
})(jQuery);
