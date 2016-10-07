<?php
/**
 * @file
 * This file contains all functions for moving nodes.
 */

/**
 * Function for generating form.
 */
function openlucius_core_move_node_form($form, &$form_state) {

  // Group dropdown list.
  $form['openlucius_core_move_node']['group'] = array(
    '#title'     => t('Group'),
    '#type'      => 'select',
    '#options'   => _load_groups(),
    '#ajax'      => array(
      'event'    => 'change',
      'callback' => 'openlucius_core_move_node_list',
      'wrapper'  => 'move-todo-list-wrapper',
    ),
  );

  // Wrapper for lists dropdown list.
  $form['openlucius_core_move_node']['wrapper'] = array(
    '#prefix' => '<div id="move-todo-list-wrapper">',
    '#suffix' => '</div>',
  );

  // Options for lists dropdown list.
  $options = array('- ' . t('Select a todo list') . ' -');
  if (isset($form_state['values']['group'])) {
    // Pre-populate options for list dropdown list if group id is set.
    $options = openlucius_core_load_todo_list($form_state['values']['group']);
  }

  // Group dropdown list.
  $form['openlucius_core_move_node']['wrapper']['list'] = array(
    '#title' => t('Todo list'),
    '#type' => 'select',
    '#options' => $options,
    '#attributes' => array(
      'class' => array('move-todo-list'),
    ),
  );

  // Form submit button.
  $form['openlucius_core_move_node']['submit'] = array(
    '#value'  => t('Move item'),
    '#type'   => 'submit',
    '#submit' => array('openlucius_core_move_node_submit'),
  );

  return $form;
}

/**
 * Custom function to get all groups the current user has access to.
 *
 * @return mixed
 *   Returns an array of group id | group title values.
 */
function _load_groups() {

  $group = array('- ' . t('Select a group') . ' -');

  // Do a db_select on the node table.
  $query = db_select('node', 'n');

  // Select the nid and title fields.
  $query->fields('n', array('nid', 'title'));

  // Add a condition for the type to be 'ol_group'.
  $query->condition('type', 'ol_group', '=');

  // Check if node is published.
  $query->condition('status', 1, '=');

  // Add the node_access tag.
  $query->addTag('node_access');

  // Execute query.
  $result = $query->execute();

  // Get the key-value pair for dropdown options.
  while ($row = $result->fetchObject()) {
    $group[$row->nid] = $row->title;
  }

  return $group;
}

/**
 * Function for populating list.
 *
 * @param int $group_id
 *   The node id of the group to get the lists from.
 *
 * @return array
 *   Returns a key valued array of node id and title.
 */
function openlucius_core_load_todo_list($group_id) {

  // Do a db_select on the shared group reference table.
  $query = db_select('field_data_field_shared_group_reference', 'g');

  // Get the node id for the todolist.
  $query->fields('g', array('entity_id'));

  // Get the node title.
  $query->fields('n', array('title'));

  // Join on the node table to get the title.
  $query->join('node', 'n', 'n.nid = g.entity_id');

  // Add a condition for the bundle.
  $query->condition('g.bundle', 'ol_todo_list', '=');

  // Add a condition for the group id.
  $query->condition('g.field_shared_group_reference_nid', $group_id, '=');

  // Add a condition for the status to be published.
  $query->condition('n.status', 1, '=');

  // Order the results alphabetically.
  $query->orderBy('n.title', 'ASC');

  // Add the node_access tag.
  $query->addTag('node_access');

  // Execute query.
  $result = $query->execute();

  // Get the key-value pair for dropdown options.
  while ($row = $result->fetchObject()) {
    $list[$row->entity_id] = $row->title;
  }

  return $list;
}

/**
 * Function for handling ajax request.
 */
function openlucius_core_move_node_list($form, $form_state) {

  // Return the dropdown list including the wrapper.
  return $form['openlucius_core_move_node']['wrapper'];
}

/**
 * Function for handling form submission.
 */
function openlucius_core_move_node_submit($form, &$form_state) {

  // Get the menu object.
  $object = menu_get_object();

  // Check for the node id.
  if (isset($object->nid)) {

    // Do a node load.
    $node = node_load($object->nid);

    // Check for the group.
    if (isset($form_state['values']['group'])) {
      // Change the group reference.
      $node->field_shared_group_reference[LANGUAGE_NONE][0]['nid'] = $form_state['values']['group'];
    }

    // Check for $form_state['values'] list.
    if (isset($form_state['values']['list'])) {
      // Change the list.
      $node->field_todo_list_reference[LANGUAGE_NONE][0]['nid'] = $form_state['values']['list'];
    }

    // Save the node.
    node_save($node);

    // Redirect to the node.
    $form_state['redirect'] = url('node/' . $object->nid, array('absolute' => TRUE));

    // Display feedback to the user that the form has been submitted.
    drupal_set_message(t('The node has been moved successfully.'));
  }

  return FALSE;
}
