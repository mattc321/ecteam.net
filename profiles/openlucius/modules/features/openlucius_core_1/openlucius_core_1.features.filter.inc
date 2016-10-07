<?php
/**
 * @file
 * openlucius_core_1.features.filter.inc
 */

/**
 * Implements hook_filter_default_formats().
 */
function openlucius_core_1_filter_default_formats() {
  $formats = array();

  // Exported format: OL Full HTML.
  $formats['ol_full_html'] = array(
    'format' => 'ol_full_html',
    'name' => 'OL Full HTML',
    'cache' => 1,
    'status' => 1,
    'weight' => -9,
    'filters' => array(),
  );

  // Exported format: OL Editor.
  $formats['plain_text'] = array(
    'format' => 'plain_text',
    'name' => 'OL Editor',
    'cache' => 1,
    'status' => 1,
    'weight' => -10,
    'filters' => array(
      'filter_autop' => array(
        'weight' => -50,
        'status' => 1,
        'settings' => array(),
      ),
      'filter_html' => array(
        'weight' => -49,
        'status' => 1,
        'settings' => array(
          'allowed_html' => '<a> <em> <strong> <cite> <blockquote> <code> <ul> <ol> <li> <dl> <dt> <dd><h2><h3><h4><h5><h6><br><p><div>',
          'filter_html_help' => 1,
          'filter_html_nofollow' => 0,
        ),
      ),
      'filter_url' => array(
        'weight' => -47,
        'status' => 1,
        'settings' => array(
          'filter_url_length' => 72,
        ),
      ),
      'openlucius_links' => array(
        'weight' => 0,
        'status' => 1,
        'settings' => array(),
      ),
    ),
  );

  return $formats;
}
