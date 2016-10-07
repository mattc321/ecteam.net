<?php

class ec_app_handler_field_display_all_services extends views_handler_field {

  function query() {
	  //sdpm($this);
    // $terms = taxonomy_get_term_by_name('Open', 'labels_todo_s');
    // if ((!$term = array_shift($terms)) || empty($term->tid)) {
      // return;
    // }
    // $nid = db_select('node', 'n')
      // ->fields('n', array('nid'))
      // ->condition('n.type', 'projects')
      // ->condition('n.title', 'Site Visits')
      // ->execute()
      // ->fetchField();
    // if (empty($nid)) {
      // return;
    //}
    // $tid = $term->tid;
    $this->ensure_my_table();
    $sql = "select GROUP_CONCAT(svc.title SEPARATOR '<br>')
				from `node` p 
				join `field_data_field_opportunities` fo on p.nid = fo.entity_id 
				join `field_data_field_opportunity_services` s on fo.field_opportunities_nid = s.entity_id
				join `field_data_field_opportunity_service` ops on s.field_opportunity_services_value = ops.entity_id
				join `node` svc on ops.field_opportunity_service_target_id = svc.nid
				where p.nid = node.nid
				group by p.nid";
    $this->field_alias = $this->query->add_field(NULL, "($sql)", 'field_display_all_services');
  }

}
