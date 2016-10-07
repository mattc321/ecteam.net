<?php

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup themeable
 */
?>

<?php

// Populate User picture.
//took out find in node tpl?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> <?php if (!$teaser): print 'ol-node'; endif; ?> clearfix"<?php print $attributes; ?>>

  <?php print render($title_prefix); ?>
  <?php if (!$page): ?>
    <h2<?php print $title_attributes; ?>>
      <a href="<?php print $node_url; ?>">
        <?php print $title; ?>
      </a>
    </h2>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <div class="content <?php if (!$teaser): print 'ol-content'; endif; ?><?php print $content_attributes; ?>">

    <?php if (!empty($actionlink)): ?>
      <div class="view-actionlink">
        <h4>
          <span class="glyphicon glyphicon-<?php print $iconactionlink1; ?>"></span>
          <?php print $actionlink; ?>
        </h4>
      </div>
    <?php endif; ?>

    <?php if (!$teaser && $node->type != "ol_group"): ?>
      <?php if (!empty($tabs)): ?>
		<div class="tabs">
		<ul class="tabs--primary nav nav-tabs">
			<?php $dest='destination=/node/'.$node->nid.'?pid='.$_GET['pid']; ?>
			<li><a href="/node/<?php print $node->nid; ?>/edit?<?php print $dest; ?>" >Edit</a></li>
		</ul>
		</div>
        <?php //print render($tabs); ?>
      <?php endif; ?>
    <?php endif; ?>

    <?php //if ($display_submitted && !$teaser): ?>
      <!--<div class="submitted">-->
        <?php //print $submitted; ?>
      <!--</div>-->
    <?php //endif; ?>
	
		
    <?php print isset($breakdown_todo) ? $breakdown_todo : ''; ?>
    <?php hide($content['comments']); ?>
    <?php hide($content['links']); ?>

    <?php //print render($content); ?>
    <?php //print isset($references_list) ? $references_list : ''; ?>
    
	<?php print views_embed_view('task_view', 'page_1', $node->nid); //loads project tasks - display "Single Task Display"?>
	 <?php //I KNOW THIS IS SUPER NAUGHTY OF ME BUT I AM OUT OF TIME AND LOW ON OPTIONS! THATS HOW THE COOKIE CRUMBLES ALRIGHT SO SUE ME ;)
		if (isset($_GET['pid']) && $_GET['pid'] != '') {
		  if (isset($_GET['pstat'])) {
			$pstat = $_GET['pstat'];
			$pid = $_GET['pid'];
			$project = node_load($pid);
			
			if ($project->field_project_status[LANGUAGE_NONE][0]['tid'] != $pstat) {
				$project->field_project_status[LANGUAGE_NONE][0]['tid'] = $pstat;
				node_submit($project);
				node_save($project);
				$mylabel = 'updated';
			}
			$ctid = $project->field_project_status[LANGUAGE_NONE][0]['tid'];
			$vocabulary = taxonomy_vocabulary_machine_name_load('project_status');
			$terms = entity_load('taxonomy_term', FALSE, array('vid' => $vocabulary->vid));
			print '<form name="myform" class="ec-form" action="" method="get">';
			print '<div class="task-label">Update Project Status</div>';
			print '<div class="field-items">';
			print '<select class="form-control form-select" name="pstat">';
			print '<option value="">Select...</option>';
			$arr2 = array();
			foreach ($terms as $weight) {
				$arr2[$weight->weight] = array(
								'tid' => $weight->tid,
								'name' => $weight->name,
								);
			}
			ksort($arr2);
			foreach ($arr2 as $key => $value) {
				print '<option value="'.$value['tid'].'"';
				if($ctid == $value['tid']){ print 'selected'; };
				print '>'.$value['name'].'</option>';
			} 
			print '</select>';
			print '</div>';
			print '<input name="pid" type="hidden" value="'.$pid.'">';
			print '<div class="btndiv"><button class="ec-button btn btn-primary" value="Save" type="submit">Update</button></div>';
			print '<div class="lbl"><label for="ec-update">'.$mylabel.'</label></div>';
			print '</form>';
			
		  } else {
			$pid = $_GET['pid'];
			$project = node_load($pid);
			$ctid = $project->field_project_status[LANGUAGE_NONE][0]['tid'];
			$vocabulary = taxonomy_vocabulary_machine_name_load('project_status');
			$terms = entity_load('taxonomy_term', FALSE, array('vid' => $vocabulary->vid));
			print '<form name="myform" class="ec-form" action="" method="get">';
			print '<div class="task-label">Update Project Status</div>';
			print '<div class="field-items">';
			print '<select class="form-control form-select" name="pstat">';
			print '<option value="">Select...</option>';
			//dpm($terms);
			
			$arr2 = array();
			foreach ($terms as $weight) {
				$arr2[$weight->weight] = array(
								'tid' => $weight->tid,
								'name' => $weight->name,
								);
			}
			ksort($arr2);
			foreach ($arr2 as $key => $value) {
				print '<option value="'.$value['tid'].'"';
				if($ctid == $value['tid']){ print 'selected'; };
				print '>'.$value['name'].'</option>';
			} 
			print '</select>';
			print '</div>';
			print '<input name="pid" type="hidden" value="'.$pid.'">';
			print '<button class="ec-button btn btn-primary" value="Save" type="submit">Update</button>';
			print '</form>';
		  }
		}
	 
	 ?>
	<?php print views_embed_view('task_view', 'page_3', $node->nid); //loads opportunity tasks - display "Single Opp Task Display"?>
	
	<?php 
	asort($arr2);
	print render($content['comments']); ?>
  </div>

  <?php if ($node->type == "ol_group"): ?>
    <div class="ol_group-detailpage">
      <?php if (!empty($tabs)): ?>
        <?php print render($tabs); ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>
