<?php
/**
 * @file
 * Used to unset the base css.
 */

/**
 * Implements hook_css_alter().
 */
function openlucius_amelia_css_alter(&$css) {
  unset($css['profiles/openlucius/themes/openlucius/css/style.css']);
}

/**
 * Implements hook_preprocess_html().
 */
function openlucius_amelia_preprocess_html(&$vars) {

  // Add amelia theme from bootstrap css.
  drupal_add_css('//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/amelia/bootstrap.min.css',
    array(
      'type'  => 'external',
    )
  );
}
