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
 *
 * *** VARIABLES FOR CREATE A PROJECT BUTTON ***
 * *** Button added to create a new project and prepopulate some of the fields based
 * *** on the Opportunity we are on. Use the $_GET Vars below to send over data in url
 * 			$newtitle = $_GET['title'];
 *			$projstatus = $_GET['status'];
 *			$projadd = $_GET['address'];
 *			$projcity = $_GET['city'];
 *			$projst = $_GET['st'];
 *			$projzip = $_GET['zip'];
 *			$projclientname = $_GET['clientname'];
 *			
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup themeable
 */
?>

<?php
//dpm($content);

//CREATE A NEW PROJECT BUTTON VARIABLES @ ec_app.module
			$newtitle = $title;
			//$projstatus =  $content['field_status'];
			$projadd = $content['group_opp_info']['field_address']['#items'][0]['value']; 
			$projcity = $content['group_opp_info']['field_city']['#items'][0]['value']; 
			$projst = $content['group_opp_info']['field_state']['#items'][0]['value']; 
			$projzip = $content['group_opp_info']['field_zip']['#items'][0]['value']; 
			//$projclientname = $content['field_client_project_name']; 
			$projoppid = $node->nid;
			$bname = $content['field_probability_display']['#object']->field_account_reference['und'][0]['entity']->title;
			$buid = $content['group_opp_info']['field_account_reference']['#items'][0]['target_id'];
			if (!empty($bname)) {
				$builderid = $bname . '(' . $buid . ')';
			} else {
				$builderid = '';
			}
			
			
			
			//$base_root
			global $base_root;
			$urlstr = $base_root.'/node/add/projects?createproject=true';
			$urlstr .= '&title='.$projadd;
			//$urlstr .= '&status='.$projstatus;
			$urlstr .= '&address='.$projadd;
			$urlstr .= '&city='.$projcity;
			$urlstr .= '&st='.$projst;
			$urlstr .= '&zip='.$projzip;
			$urlstr .= '&oppid='.$projoppid;
			$urlstr .= '&bid='.$builderid;
			
			
// Populate User picture.
//taken out 10/22 found in node.tpl
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> <?php if (!$teaser): print 'ol-node'; endif; ?> clearfix"<?php print $attributes; ?>>
<div id="opptitle" class="corner"><div class="corner-text">Opportunity</div></div>
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
<div class="far-right">
				 <?php if ($display_submitted && !$teaser): ?>
				<div class="submitted">
					<?php print $submitted; ?></br>
						Last modified by
					<?php 
						$myuser = user_load($node->revision_uid); 
						$username = $myuser->name; 
						echo $username;  
					?> on <?php print format_date($node->revision_timestamp, 'custom', 'm/d/Y' ); ?>		
				</div>
			<?php endif; ?>
    <?php if (!$teaser && $node->type != "ol_group"): ?>
      <?php if (!empty($tabs)): ?>
        <?php print render($tabs); ?>
      <?php endif; ?>
    <?php endif; ?>
</div>

<?php //THIS IS WHERE THE SUBMITTED CODE WAS AT ?>
	
	<?php
	global $base_root;
    hide($content['comments']);
    hide($content['links']);
	hide($content['field_probability_display']);
	//hide($content['field_opportunity_type']); 
	hide($content['field_opportunity_services']); 	 
	hide($content['field_opportunity_status']); 	 
	hide($content['field_opportunity_line_item']); 
	hide($content['field_last_modified_by']);
	hide($content['field_project_contacts']);
	//hide($content['field_services']); 
	?>
	
	<div class="ec-right">
	
		<div class="ec-col2">
			<?php
			
			$pic = views_embed_view('proj_opp_cover_photo', 'block', $node->nid);
			$content['group_opp_info']['proj_opp_cover_photo'] = array(
						'#weight' => 12,
						'#field_name' => 'proj_opp_cover_photo',
						);
			$content['group_opp_info']['proj_opp_cover_photo'][0]['#markup'] = '<div class="field field-name-field-image">'.$pic.'</div>';	
			print render($content);
			?>
		</div>
		
		<div class="ec-col1">
		
			<?php
			print render($content['field_probability_display']);
			//print render($content['field_opportunity_status']);
			//print render($content['field_opportunity_type']);
			?>
			<?php
			if(!empty($content['group_opp_info']['field_project_reference']['#items'][0]['target_id'])) { ?>
				<div class="btn-ec-off">
				<p>Create as Project</p>
			<?php } else { ?>
				<div class="btn-primary">
				<p><a href="<?php echo $urlstr; ?>">Create as Project</a></p>
			<?php } ?>
				
			</div>
			

		</div>
	
		<div class="ec-foot">
			<div class="group-services ec-legend"><h3><span>Services</span></h3>
			<?php print render($content['field_opportunity_services']);?></div>
			<div class="ec-legend"><h3><span>Tasks</span></h3><?php //task view and edit blocks correspond with css injector 5 and ec_app.js ?>
				<div id="task-view-block">
					<div class="link" id="btn-edit-task-view"><span class="og-white">Edit Mode</span></div>
					<?php print views_embed_view('task_view', 'block_2', $node->nid); ?>
					<div><a class="green-button gb2" href="/node/add/ol-todo?opp=<?php echo $node->nid; ?>">Add Task</a></div>
				</div>
				<div id="edit-task-view">
					<div class="link" id="btn-task-view"><span class="og-white">Cancel</span></div>
					<?php print views_embed_view('project_tasks_block', 'block_1', $node->nid); ?>
				</div>
			</div>
				<div class="group-contacts ec-legend"><h3><span>Contacts</span></h3>
				<?php print render($content['field_project_contacts']);	?></div>
				
				<?php print render($content['comments']);?>	
			</div>
		</div>

 </div>

  <?php if ($node->type == "ol_group"): ?>
    <div class="ol_group-detailpage">
      <?php if (!empty($tabs)): ?>
        <?php print render($tabs); ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>
