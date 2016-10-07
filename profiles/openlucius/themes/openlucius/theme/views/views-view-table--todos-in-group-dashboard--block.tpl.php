<?php

/**
 * @file
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $header: An array of header labels keyed by field id.
 * - $caption: The caption for this table. May be empty.
 * - $header_classes: An array of header classes keyed by field id.
 * - $fields: An array of CSS IDs to use for each field id.
 * - $classes: A class or classes to apply to the table, based on settings.
 * - $row_classes: An array of classes to apply to each row, indexed by row
 *   number. This matches the index in $rows.
 * - $rows: An array of row items. Each row is an array of content.
 *   $rows are keyed by row number, fields within rows are keyed by field ID.
 * - $field_classes: An array of classes to apply to each field, indexed by
 *   field id, then row number. This matches the index in $rows.
 * @ingroup views_templates
 */
?>
<table <?php if ($classes) : ?>
  <?php print 'class="' . $classes . '" '; ?>
<?php endif; ?><?php print $attributes; ?>>
  <?php if (!empty($title) || !empty($caption)) : ?>
    <caption><?php print $caption . $title; ?></caption>
  <?php endif; ?>
  <?php if (!empty($header)) : ?>
    <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
        <th <?php if ($header_classes[$field]) : ?>
          <?php print 'class="' . $header_classes[$field] . '" '; ?>
        <?php endif; ?>>
          <?php print $label; ?>
        </th>
      <?php endforeach; ?>
    </tr>
    </thead>
  <?php endif; ?>

  <?php
  $count = count($view->result);

  for ($i = 0; $i < $count; $i++) : ?>
    <?php $list_id = $view->result[$i]->field_data_field_todo_list_reference_field_todo_list_referen; ?>
  <?php endfor; ?>


  <tbody class="todo-list">
  <?php $first = TRUE; ?>
  <?php foreach ($rows as $row_count => $row): ?>
    <?php if ($first) : ?>
      <?php $todo_list_reference = $view->result[$row_count]->field_field_todo_list_reference[0]['raw']['nid']; ?>
      <tr id="quick-add-to-<?php print $todo_list_reference; ?>">
        <td colspan="10">
          <div
            class="todo-list-quick-add todo-list-reference-<?php print $todo_list_reference; ?>">
            <?php if ($inline_todo_form) : ?>
              <?php print $inline_todo_form; ?>
            <?php endif; ?>
          </div>
        </td>
      </tr>
      <?php $first = FALSE; ?>
    <?php endif; ?>
    <?php $list_id = $view->result[$row_count]->field_data_field_todo_list_reference_field_todo_list_referen; ?>
    <?php $todo_id = $view->result[$row_count]->nid_1; ?>

    <tr id="todo-<?php print $todo_id; ?>"<?php if ($row_classes[$row_count]) : ?>
      <?php print 'class="todo-list-' . $list_id . ' ' . implode(' ', $row_classes[$row_count]) . '"'; ?>
    <?php endif; ?>>

      <?php foreach ($row as $field => $content): ?>
        <td <?php if ($field_classes[$field][$row_count]) : ?>
          <?php print 'class="' . $field_classes[$field][$row_count] . '" '; ?>
        <?php endif; ?><?php print drupal_attributes($field_attributes[$field][$row_count]); ?>>
          <?php print $content; ?>
        </td>
      <?php endforeach; ?>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
