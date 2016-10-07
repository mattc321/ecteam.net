<?php
/**
 * @file
 * Used to unset the base css.
 */

/**
 * Implements hook_css_alter().
 */
function openlucius_yeti_css_alter(&$css) {
  unset($css['profiles/openlucius/themes/openlucius/css/style.css']);
}
