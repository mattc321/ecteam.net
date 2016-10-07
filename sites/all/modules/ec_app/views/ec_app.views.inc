<?php

/**
 * Implements hook_views_data_alter().
 */
function ec_app_views_data_alter(&$data) {
  $data['node']['field_unit_types_count'] = array(
    'title' => t('Unit Types Count'),
    'help' => t('Count nodes by selected term.'),
    'field' => array(
      'handler' => 'ec_app_handler_field_unit_types_count',
	  'click sortable' => TRUE,
    ),
  );
  $data['node']['field_next_sv'] = array(
    'title' => t('Next SV'),
    'help' => t('Next open task title.'),
    'field' => array(
      'handler' => 'ec_app_handler_field_next_sv',
    ),
  );
  $data['node']['field_next_sv_date'] = array(
    'title' => t('Next SV Date'),
    'help' => t('Next due date for open task.'),
    'field' => array(
      'handler' => 'ec_app_handler_field_next_sv_date',
    ),
  );
   $data['node']['field_display_all_services'] = array(
    'title' => t('Display All Services'),
    'help' => t('Return all services for a project onto one record.'),
    'field' => array(
      'handler' => 'ec_app_handler_field_display_all_services',
    ),
  );
     $data['field_data_field_todo_user_reference']['assignee_select'] = array(
    'title' => t('Filter by Assignee'),
    'group' => t('Content'),
    'help' => t('Add drop down feature for field_assign_to to only show users in the role assignee'),
    'real field' => 'field_todo_user_reference_uid',
    'filter' => array(
      'handler' => 'ec_app_handler_filter_by_assignee',
    ),
  );
}
