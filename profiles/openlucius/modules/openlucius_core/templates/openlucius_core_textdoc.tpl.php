<?php
/**
 * @file
 * File that has the tpl file for the textdoc.
 */
?>
<div class="move-button">
  <?php if (isset($vars['move_button'])): ?>
    <?php print $vars['move_button']; ?>
  <?php endif; ?>
</div>

<?php if (isset($vars['backbutton'])): ?>
<div class="textdoc_back">
  <?php print $vars['backbutton']; ?>
</div>
<?php endif; ?>

<div class="textdoc_infowrapper">
  <div class="textdoc_info">
    <?php if (isset($vars['created_info'])): ?>
    <?php print $vars['created_info']; ?>
    <?php endif; ?>
  </div>
  <div class="textdoc_info">
    <?php if (isset($vars['cancel_edit'])): ?>
    <?php print $vars['cancel_edit']; ?>
    <?php endif; ?>
    <div class="textdoc_button">
      <?php if (isset($vars['created_info'])): ?>
      <?php print $vars['edit_button']; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php if (isset($vars['backbutton'])): ?>
<div class="textdoc_index">
  <?php print $vars['index']; ?>
</div>
<?php endif; ?>
