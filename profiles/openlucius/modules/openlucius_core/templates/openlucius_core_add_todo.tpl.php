<?php
/**
 * @file
 * File that describes a row of the todo table.
 */
?>
<tr id="todo-<?php print $vars['nid']; ?>" class="todo-list-<?php print $vars['nid']; ?>">
  <td class="views-field views-field-nid">
    <input type="checkbox" value="<?php print $vars['nid']; ?>" data-token="<?php print $vars['token']; ?>" data-status="<?php print $vars['label-tid']; ?>" class="ajax-close-todo nid">
  </td>
  <td class="views-field views-field-last-updated">
    <span class="glyphicon glyphicon-transfer"></span>
    <em class="placeholder"><?php print $vars['time_ago']; ?></em> <?php print t('ago'); ?>
  </td>
  <td class="views-field views-field-title">
    <?php print $vars['node_link']; ?>
  </td>
  <td class="views-field views-field-delta">
    <span class="glyphicon glyphicon-paperclip"></span>
    0
  </td>
  <td class="views-field views-field-comment-count">
    <span class="glyphicon glyphicon-comment"></span>
    0
  </td>
  <td class="views-field views-field-field-todo-user-reference">
    <span class="glyphicon glyphicon-user"></span>
    <?php print $vars['user_link']; ?>
  </td>
  <td class="views-field views-field-field-todo-label">
    <?php print $vars['status']; ?></td>
  <td class="views-field views-field-field-todo-due-date-singledate">
    <span class="glyphicon glyphicon-calendar"></span>
    <span class="date-display-single"><?php print $vars['date']; ?></span>
  </td>
  <td class="views-field views-field-edit-node">
    <a href="<?php print $vars['node_edit_url']; ?>">
      <span class="glyphicon glyphicon-edit"></span>
    </a>
  </td>
  <td class="views-field views-field-delete-node">
    <?php if (isset($vars['node_delete_url'])) : ?>
    <a href="<?php print $vars['node_delete_url']; ?>">
        <span class="glyphicon glyphicon-remove-circle"></span>
    </a>
    <?php endif; ?>
  </td>
  <td class="views-field views-field-timestamp">
    <span class="glyphicon glyphicon-certificate"></span>
  </td>
</tr>
