<?php
/**
 * @file
 * Used to unset the base css.
 */

/**
 * Implements hook_css_alter().
 */
function openlucius_lumen_css_alter(&$css) {
  unset($css['profiles/openlucius/themes/openlucius/css/style.css']);
}

/**
 * Implements hook_preprocess_html().
 */
function openlucius_lumen_preprocess_html(&$vars) {

  // Add lumen theme from bootswatch.
  drupal_add_css('//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/lumen/bootstrap.min.css',
    array(
      'type'  => 'external',
    )
  );
}
