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
<div style="width:100%">
<div style="display: table; width: 100%;" class="divTable">
   <?php if (!empty($title) || !empty($caption)) : ?>
     <caption><?php print $caption . $title; ?></caption>
  <?php endif; ?>
  <?php if (!empty($header)) : ?>
    <div style="display: table-header-group; background-color: #EEE;" class="divTableHeading">
      <div style="display: table-row;" class="divTableRow">
        <?php foreach ($header as $field => $label): ?>
          <div style="border: 1px solid #999999; display: table-cell; padding-left:3px; text-align:left;" class="divTableCell">
            <?php print $label; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
  <div style="display: table-row-group;" class="divTableBody">
    <?php foreach ($rows as $row_count => $row): ?>
      <div style="display: table-row;" class="divTableRow">
		<?php foreach ($row as $field => $content): ?>
          <div style="border: 1px solid #999999; display: table-cell; text-align:left; padding-left:3px;">
            <?php print $content; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</div>
