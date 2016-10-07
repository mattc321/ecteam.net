<?php

/**
 * Custom filter handler to pull only assignees in drop down
 */
class ec_app_handler_filter_by_assignee extends views_handler_filter_in_operator {


  function get_value_options() {

  // our field "field_special_status_value" is a single boolean checkbox field in content type project.

    $uids = db_select('users_roles', 'u')
      ->fields('u', array('uid'))
      ->condition('u.rid', 7, '=')
      ->execute()
      ->fetchCol();
    $assignees = user_load_multiple($uids);


    if (!empty($assignees)) {

      foreach ($assignees as $assignee) {
		 if($assignee->status == 1) {
			 $ass_list[$assignee->uid] = $assignee->name;
		 }
      }
      // Sort by username.
      natsort($ass_list);
      $this->value_options = $ass_list;

      return $ass_list;
    }
  }

}