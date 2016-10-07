<?php

class ec_app_handler_field_next_sv_date extends views_handler_field_date {

  function query() {
    $terms = taxonomy_get_term_by_name('Open', 'labels_todo_s');
    if ((!$term = array_shift($terms)) || empty($term->tid)) {
      return;
    }
    $nid = db_select('node', 'n')
      ->fields('n', array('nid'))
      ->condition('n.type', 'ol_todo_list')
      ->condition('n.title', 'Site Visits')
      ->execute()
      ->fetchField();
    if (empty($nid)) {
      return;
    }
    $tid = $term->tid;
    $this->ensure_my_table();
    $sql = "SELECT ds.field_todo_due_date_singledate_value
              FROM field_data_field_project_tasks pt
              JOIN node n ON n.nid = pt.field_project_tasks_target_id
              JOIN field_data_field_todo_label tl ON tl.entity_id = n.nid
              JOIN field_data_field_todo_due_date_singledate ds on ds.entity_id = n.nid
              JOIN field_data_field_todo_list_reference lf ON lf.entity_id = n.nid
              WHERE pt.entity_type = 'node'
                AND pt.bundle = 'projects'
                AND pt.deleted = 0
                AND tl.deleted = 0
                AND ds.deleted = 0
                AND pt.entity_id = node.nid
                AND lf.field_todo_list_reference_nid = " . $nid . "
                AND tl.field_todo_label_tid = " . $term->tid . "
              ORDER BY pt.delta
              LIMIT 0,1";
    $this->field_alias = $this->query->add_field(NULL, "($sql)", 'field_next_sv_date');
  }

  /*function render($values) {
    $output = '';
    $value = $this->get_value($values);
    return empty($value) ? '' : $value;
  }*/
}
