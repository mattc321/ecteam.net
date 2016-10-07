<?php

class ec_app_handler_field_unit_types_count extends views_handler_field {

  function option_definition() {
    $options = parent::option_definition();

    $tids = taxonomy_allowed_values(field_info_field('field_unit_types'));
    $options['term'] = array('default' => key($tids));
	$options['sum_all'] = array('default' => 'No');
	//dsm($options);
    return $options;
  }

  function options_form(&$form, &$form_state) {
    $form['term'] = array(
      '#type' => 'select',
      '#title' => t('Select term for aggregation'),
      '#options' => taxonomy_allowed_values(field_info_field('field_unit_types')),
      '#default_value' => $this->options['term'],
    );
	$form['sum_all'] = array(
      '#type' => 'select',
      '#title' => t('Sum all values in this field'),
	  '#description' => t('Sums all values for each Account'),
      '#options' => array(1 => 'No', 2 => 'Yes'),
      '#default_value' => $this->options['sum_all'],
    );
	
    parent::options_form($form, $form_state);
  }

  function query() {

    $tid = $this->options['term'];
    $sumall = $this->options['sum_all']; //1 == no // 2 == yes
	
	if ($sumall == 1 || $sumall == 'No') {
	
		//IF TID = 70 (MH) then we do not want to count it. We want to return field_mf_qty
		if ($tid == 70) {	
			$sql = "SELECT sum(field_mf_qty_value) FROM {field_data_field_units} fu JOIN {field_data_field_mf_qty} ut ON ut.entity_id = fu.field_units_value
			WHERE fu.entity_id = node.nid AND ut.entity_type = 'field_collection_item' AND ut.bundle = 'field_units' AND ut.deleted = 0";
		} else {	
			$sql = "SELECT COUNT(*) FROM {field_data_field_units} fu JOIN {field_data_field_unit_types} ut ON ut.entity_id = fu.field_units_value
			WHERE fu.entity_id = node.nid AND ut.entity_type = 'field_collection_item' AND ut.bundle = 'field_units' AND ut.deleted = 0 AND ut.field_unit_types_tid = " . $tid;
		}
		$table = $this->ensure_my_table();
		$this->field_alias = $this->query->add_field(NULL, "($sql)", 'field_unit_types_count');
	
	} else {
	
		if ($tid == 70) {
			
			$subquery = "select noa.nid
			from {node} nop
			join {field_data_field_account_reference} far on nop.nid = far.entity_id
			join {node} noa on far.field_account_reference_target_id = noa.nid
			where nop.nid = node.nid";
			
			$sql= "SELECT sum(field_mf_qty_value)
			FROM field_data_field_units fu 
			join {field_data_field_project_status} ps on fu.entity_id = ps.entity_id
			JOIN field_data_field_mf_qty ut ON ut.entity_id = fu.field_units_value
			join field_data_field_account_reference ar ON ar.entity_id = fu.entity_id
			join node Na on ar.field_account_reference_target_id = Na.nid
			WHERE Na.nid = (".$subquery.")
			and ps.field_project_status_tid not in(40,41)
			AND ut.entity_type = 'field_collection_item' 
			AND ut.bundle = 'field_units' 
			AND ut.deleted = 0";
		
		} else {
			//project status tid 40 = cancelled
			//project status tid 41 = completed
			
			$subquery = "select noa.nid
			from {node} nop
			join {field_data_field_account_reference} far on nop.nid = far.entity_id
			join {node} noa on far.field_account_reference_target_id = noa.nid
			where nop.nid = node.nid";
			
		
			$sql = "SELECT count(Np.nid)
			from {node} Np
			join {field_data_field_project_status} ps on Np.nid = ps.entity_id
			join {field_data_field_account_reference} ar on Np.nid = ar.entity_id
			join {node} Na on ar.field_account_reference_target_id = Na.nid
			join {field_data_field_units} fu on Np.nid = fu.entity_id
			join {field_data_field_unit_types} ut ON fu.field_units_value = ut.entity_id 
			where Np.type = 'projects'
			and ps.field_project_status_tid not in(40,41)
			and Na.nid = (".$subquery.")
			AND ut.field_unit_types_tid = " . $tid;
	
			
		}
		$table = $this->ensure_my_table();
		$this->field_alias = $this->query->add_field(NULL, "($sql)", 'field_unit_types_count');
		//sdpm($this->query);
	}
  }

  function render($values) {
    $output = '';
    $value = $this->get_value($values);
    return empty($value) ? '' : $value;
  }
}
