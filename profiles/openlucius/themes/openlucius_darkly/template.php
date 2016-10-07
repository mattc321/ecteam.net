<?php
/**
 * @file
 * Used to unset the base css.
 */

/**
 * Implements hook_css_alter().
 */
function openlucius_darkly_css_alter(&$css) {
  unset($css['profiles/openlucius/themes/openlucius/css/style.css']);
}

/**
 * Implements hook_preprocess_html().
 */
function openlucius_darkly_preprocess_html(&$vars) {

  // Add darkly theme from bootswatch.
  drupal_add_css('//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/darkly/bootstrap.min.css',
    array(
      'type'  => 'external',
    )
  );
}
