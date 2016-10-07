<?php
/**
 * @file
 * This file contains code for the profile.
 */

/**
 * Implements hook_form_FORM_ID_alter().
 *
 * Allows the profile to alter the site configuration form.
 */
if (!function_exists("system_form_install_configure_form_alter")) {

  /**
   * Implements hook_form_FORM_ID_alter().
   */
  function system_form_install_configure_form_alter(&$form, $form_state) {
    $form['site_information']['site_name']['#default_value'] = 'OpenLucius';
  }
}

/**
 * Implements hook_form_alter().
 *
 * Select the current install profile by default.
 */
if (!function_exists("system_form_install_select_profile_form_alter")) {

  /**
   * Implements hook_form_FORM_ID_alter().
   */
  function system_form_install_select_profile_form_alter(&$form, $form_state) {
    foreach ($form['profile'] as $key => $element) {
      $form['profile'][$key]['#value'] = 'openlucius';
    }
  }
}


/**
 * Implements hook_install_tasks().
 */
function openlucius_install_tasks(&$install_state) {
  $tasks = array();

  $tasks['_openlucius_features_revert_all'] = array(
    'type' => 'normal',
  );
  $tasks['_openlucius_create_first_group'] = array(
    'type' => 'normal',
  );
  $tasks['_openlucius_node_access_rebuild'] = array(
    'type' => 'normal',
  );

  return $tasks;
}

/**
 * Custom function to revert all features.
 */
function _openlucius_features_revert_all() {
  drupal_set_time_limit(0);
  features_revert(array(
    'openlucius_core' => array('field_base'),
    'openlucius_core2' => array('field_base', 'field_instance'),
  ));
  features_revert();
}

/**
 * Custom function to create first group.
 */
function _openlucius_create_first_group() {

  $node = new stdClass();
  $node->type = "ol_group";
  $node->title = "First Group, Yihaaa!";
  $node->uid = 1;

  node_object_prepare($node);

  $node = node_submit($node);
  node_save($node);
}

/**
 * Custom function to node_access_rebuild.
 */
function _openlucius_node_access_rebuild() {
  node_access_rebuild();
  // Ignore any rebuild messages.
  node_access_needs_rebuild(FALSE);
  // Ignore any other install messages.
  drupal_get_messages();
}
