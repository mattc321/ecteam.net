<?php
/**
 * @file
 * File that has the tpl file for the textdoc.
 */
?>

<a href="<?php print $vars['group_url']; ?>" class="list-group-item  group-<?php print $vars['gid']; ?>">
  <span class="mygroups-title"><?php print $vars['group_title']; ?></span>
  <span class="badge"><span class="glyphicon glyphicon-user my_groups_group_usercount"></span>
    <?php print $vars['group_users_count']; ?>
  </span>
</a>
