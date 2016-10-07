<?php
/**
 * @file
 * Contains code relating to the admin page.
 */

/**
 * System settings form for Box View API settings.
 */
function box_view_admin_form($form, &$form_state) {
  $form['api_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('API Settings'),
  );
  $form['api_settings']['box_view_api_key'] = array(
    '#type' => 'textfield',
    '#title' => t('API Key'),
    '#default_value' => variable_get('box_view_api_key', ''),
    '#description' => t('You can find the API Key for your Box View application on !link.', array(
      '!link' => l(t('your apps page'), 'https://app.box.com/developers/services'),
    )),
  );

  $form['status'] = array(
    '#type' => 'fieldset',
    '#title' => t('API Status'),
  );
  $form['status']['load_files'] = array(
    '#type' => 'button',
    '#value' => t('Load files'),
    '#ajax' => array(
      'callback' => 'box_view_admin_form_load_files',
      'wrapper' => 'load-files-table',
      'method' => 'replace',
      'effect' => 'fade',
    ),
    '#suffix' => '<div id="load-files-table"></div>',
  );
  $form['status']['upload']['file_url'] = array(
    '#type' => 'textfield',
    '#title' => t('File URL to upload'),
    '#description' => t('Enter an absolute file URL to be uploaded to the API.'),
  );
  $form['status']['upload']['button'] = array(
    '#type' => 'button',
    '#value' => t('Upload'),
    '#ajax' => array(
      'callback' => 'box_view_admin_form_upload_file',
      'wrapper' => 'upload-response',
      'method' => 'replace',
      'effect' => 'fade',
    ),
    '#suffix' => '<div id="upload-response"></div>',
  );
  return system_settings_form($form);
}

/**
 * Implements hook_FORM_ID_validate().
 */
function box_view_admin_form_validate(&$form, &$form_state) {
  $v = $form_state['values'];
  $api_key = $v['box_view_api_key'];
  if (empty($api_key)) {
    form_set_error('box_view_api_key', t('API Key cannot be empty.'));
    return;
  }
  // See if we can access the API.
  $api = new Box_View_API($api_key);
  try {
    $api->load(array('limit' => 1));
  } catch (Exception $e) {
    form_set_error('box_view_api_key', $e->getMessage());
  }
}

/**
 * AJAX callback for the upload file functionality.
 */
function box_view_admin_form_upload_file($form, $form_state) {
  $file_url = $form_state['values']['file_url'];
  if (empty($file_url)) {
    return 'Enter the URL for a file you want to upload.';
  }
  $site_name = variable_get('site_name', 'Drupal module');
  $doc = box_view_document(array('name' => 'uploaded from ' . $site_name, 'file_url' => $file_url));
  try {
    $response = box_view_api_upload($doc);
    if ($response) {
      return 'Response: <code>' . json_encode($doc) . '</code>';
    }
    else {
      return t('Error uploading that file.');
    }
  } catch (Exception $e) {
    return t('Error uploading that file.');
  }
}

/**
 * Callback for the 'Load files' button on the admin form.
 */
function box_view_admin_form_load_files($form, $form_state) {
  $output = '';
  $output .= '<div id="load-files-table">';

  $docs = box_view_api_load(array('limit' => 50));
  if ($docs === FALSE) {
    $output .= l(t('Error fetching file list.'), 'admin/reports/dblog');
  }
  elseif (count($docs) === 0) {
    $output .= t('No files uploaded yet.');
  }
  else {
    $output .= box_view_admin_form_file_table($docs);
  }

  $output .= '</div>';
  return $output;
}

/**
 * Creates a table showing files that have been uploaded using this API Key.
 */
function box_view_admin_form_file_table($docs) {
  $header = array('id', 'name', 'status', 'created', 'modified', 'actions');
  $rows = array();
  foreach ($docs as $doc) {
    $rows[] = array(
      'id' => $doc->id,
      'name' => $doc->name,
      'status' => $doc->status,
      'created' => format_date(strtotime($doc->created_at)),
      'modified' => format_date(strtotime($doc->modified_at)),
      'actions' => l(t('View'), 'box_view/view/' . $doc->id) . ' | ' . l(t('Download'), 'box_view/download/' . $doc->id) . ' | ' . l(t('Delete'), 'box_view/delete/' . $doc->id, array('query' => array('destination' => url('admin/config/services/box/view')))),
    );
  }
  return theme('table', array('header' => $header, 'rows' => $rows));
}