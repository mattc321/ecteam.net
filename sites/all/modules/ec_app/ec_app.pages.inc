<?php
function my_node_delete_function($node) {
  // Taken from node modules node_delete_confirm submit handler
  if ($node->type=='ol_todo'){
	node_delete($node->nid);
	watchdog('content', '@type: Deleted %title.', array('@type' => $node->type, '%title' => $node->title));
	drupal_set_message(t('<div class="JumpToTaskViewBlock"></div> @type %title has been deleted.', array('@type' => node_type_get_name($node), '%title' => $node->title)));
	//this empty div correlates to js the jumps down to the task view block in ec_app.js
	drupal_goto();
 } else {
 
    module_load_include('inc', 'node', 'node.pages');
    return drupal_get_form('node_delete_confirm', $node);
 }
}
