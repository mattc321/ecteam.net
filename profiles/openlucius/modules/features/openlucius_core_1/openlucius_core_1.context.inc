<?php
/**
 * @file
 * openlucius_core.context.inc
 */

/**
 * Implements hook_context_default_contexts().
 */
function openlucius_core_1_context_default_contexts() {
  $export = array();

  $context = new stdClass();
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'file_folders';
  $context->description = '';
  $context->tag = 'Files';
  $context->conditions = array(
    'path' => array(
      'values' => array(
        'group-files/*' => 'group-files/*',
      ),
    ),
  );
  $context->reactions = array(
    'block' => array(
      'blocks' => array(
        'views-file_folders-block_3' => array(
          'module' => 'views',
          'delta' => 'file_folders-block_3',
          'region' => 'sidebar_first',
          'weight' => '-10',
        ),
      ),
    ),
  );
  $context->condition_mode = 0;

  // Translatables.
  // Included for use with string extractors like potx.
  t('Files');
  $export['file_folders'] = $context;

  $context = new stdClass();
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'textdocs_in_group';
  $context->description = '';
  $context->tag = 'Text-document';
  $context->conditions = array(
    'views' => array(
      'values' => array(
        'text_documents_in_a_group:page_1' => 'text_documents_in_a_group:page_1',
      ),
    ),
  );
  $context->reactions = array(
    'block' => array(
      'blocks' => array(
        'views-a5ed397cfeebc6da17ea7491e5232a30' => array(
          'module' => 'views',
          'delta' => 'a5ed397cfeebc6da17ea7491e5232a30',
          'region' => 'sidebar_second',
          'weight' => '-10',
        ),
        'views-1691fbba2f9c78d6584250aa4f495ae0' => array(
          'module' => 'views',
          'delta' => '1691fbba2f9c78d6584250aa4f495ae0',
          'region' => 'sidebar_second',
          'weight' => '-9',
        ),
      ),
    ),
  );
  $context->condition_mode = 0;

  // Translatables.
  // Included for use with string extractors like potx.
  t('Text-document');
  $export['textdocs_in_group'] = $context;

  return $export;
}
