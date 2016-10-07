<?php
/**
 * @file
 * template.php
 * This file should only contain light helper functions and stubs pointing to
 * other files containing more complex functions.
 * The stubs should point to files within the `theme` folder named after the
 * function itself minus the theme prefix. If the stub contains a group of
 * functions, then please organize them so they are related in some way and name
 * the file appropriately to at least hint at what it contains.
 * All [pre]process functions, theme functions and template implementations also
 * live in the 'theme' folder. This is a highly automated and complex system
 * designed to only load the necessary files when a given theme hook is invoked.
 *
 * @see _bootstrap_theme()
 * @see theme/registry.inc
 * Due to a bug in Drush, these includes must live inside the 'theme' folder
 * instead of something like 'includes'. If a module or theme has an 'includes'
 * folder, Drush will think it is trying to bootstrap core when it is invoked
 * from inside the particular extension's directory.
 * @see https://drupal.org/node/2102287
 */

 
 
/**
 * Overrides bootstrap's theme_menu_link().
 *
 * We need menu items to be translatable.
 */
function openlucius_menu_link(array $variables) {

  global $user;
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {

    // Prevent dropdown functions from being added to management menu so it
    // does not affect the navbar module.
    if (($element['#original_link']['menu_name'] == 'management') && (module_exists('navbar'))) {
      $sub_menu = drupal_render($element['#below']);
    }
    elseif ((!empty($element['#original_link']['depth'])) && ($element['#original_link']['depth'] == 1)) {

      // Check for the user menu identifier.
      if ($element['#localized_options']['identifier'] == 'user-menu_me:user') {

        // Load logged in user.
        $user_object = user_load($user->uid);

        // Create the default avatar image path.
        $default_image_path = drupal_get_path('profile', 'openlucius') . '/themes/openlucius/images/avatar.png';

        // Check if the user has an avatar, otherwise use default image.
        $image_path = isset($user_object->picture->uri) ? $user_object->picture->uri : $default_image_path;

        // Set the image variables.
        $variables = array(
          'path' => $image_path,
          'alt' => $user_object->name,
          'title' => $user_object->name,
          'width' => '20px',
          'height' => '20px',
          'attributes' => array('class' => 'img-circle'),
        );

        // Create the avatar rounded image.
        $avatar = theme('image', $variables);

        // Shorten the username.
        $username = (strlen($user_object->name) > 12) ? substr($user_object->name, 0, 9) . '...' : $user_object->name;

        // Change the menu link.
        $element['#title'] = $avatar . ' ' . '<span class="username">' . $username . '</span>';
      }

      // Add our own wrapper.
      unset($element['#below']['#theme_wrappers']);
      $sub_menu = '<ul class="dropdown-menu">' . drupal_render($element['#below']) . '</ul>';
      // Generate as standard dropdown.
      $element['#title'] .= ' <span class="caret"></span>';
      $element['#attributes']['class'][] = 'dropdown';
      $element['#localized_options']['html'] = TRUE;

      // Set dropdown trigger element to # to prevent inadvertent page loading.
      // When a submenu link is clicked.
      $element['#localized_options']['attributes']['data-target'] = '#';
      $element['#localized_options']['attributes']['class'][] = 'dropdown-toggle';
      $element['#localized_options']['attributes']['data-toggle'] = 'dropdown';
    }
  }
  // On primary navigation menu, class 'active' is not set on active menu item.
  // @see https://drupal.org/node/1896674.
  if (($element['#href'] == $_GET['q'] || ($element['#href'] == '<front>' && drupal_is_front_page())) && (empty($element['#localized_options']['language']))) {
    $element['#attributes']['class'][] = 'active';
  }

  $title = $element['#title'];
  $output = l($title, $element['#href'], $element['#localized_options']);

  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
 * Implements template_preprocess_node.
 */
function openlucius_preprocess_node(&$vars) {

  $vars['tabs'] = menu_local_tabs();

  // Hide field for client.
  if (openlucius_core_user_is_client()) {
    $vars['content']['field_shared_show_clients']['#access'] = FALSE;
  }
  // Only show the show client field if its set to "yes".
  if (isset($vars['content']['field_shared_show_clients']['#items'][0]['value'])
    && $vars['content']['field_shared_show_clients']['#items'][0]['value'] == 0
  ) {
    unset($vars['content']['field_shared_show_clients']);
  }

  // Change how submitted is displayed on todo's.
  if ($vars['type'] == 'ol_todo') {

    // Set the two icons.
    $icon_user = '<i class="glyphicon glyphicon-user"></i>';
    $icon_date = '<i class="glyphicon glyphicon-time"></i>';

    // Load the user.
    $uid = user_load($vars['uid']);
    $user_link = 'user/dashboard/' . $vars['uid'];

    // Get the posted date.
    $date = $vars['created'];
    $date_f = format_date($date, 'custom', 'd/m/Y - H:i');

    // Set the user display (link to user dashboard).
    $user_display = l($uid->name, $user_link);

    // Update the vars submitted with the new info.
    $vars['submitted'] = $icon_user . ' ' . $user_display . '<br />' . $icon_date . ' ' . $date_f;
  }
}
/**
 * Removes text format options on comment input added 8/15/2015 by Matt
 */
function openlucius_form_comment_form_alter(&$form, &$form_state) {

  $form['comment_body']['#after_build'][] = 'openlucius_customize_comment_form';

}

function openlucius_customize_comment_form(&$form) {
  $form[LANGUAGE_NONE][0]['format']['#access'] = FALSE;
  return $form;
}
/**
 * Implements template_preprocess_comment.
 */
function openlucius_preprocess_comment(&$variables) {

  // Fix the title and create the correct comment anchor-link to this comment.
  $variables['title'] = l(
    t('#!id', array('!id' => $variables['id'])),
    "node/{$variables['node']->nid}",
    array(
      'attributes' => array(
        'class' => array('permalink'),
        'rel' => array('bookmark'),
      ),
      'fragment' => 'comment-' . $variables['elements']['#comment']->cid,
      'absolute' => TRUE,
    )
  );

  // Check for hidden comment.
  if (isset($variables['elements']['#comment']->field_todo_comm_show_clients[LANGUAGE_NONE][0]['value'])) {
    if ($variables['elements']['#comment']->field_todo_comm_show_clients[LANGUAGE_NONE][0]['value']) {
      // Check for clients.
      if (openlucius_core_user_is_client()) {
        $variables = array();
      }
      else {
        // Add a class to the comment to indicate its hidden.
        $variables['classes_array'][] = 'openlucius-hidden-comment';
      }
    }
  }
}

/**
 * Implements template_preprocess_block.
 */
function openlucius_preprocess_block(&$variables) {

  // Get active menu item.
  $menu = menu_get_item();
  $group_archived = FALSE;
  $blockid = $variables['block']->bid;

  // Legacy support for heartbeat/twee homepage.
  if ($blockid == 'views-messages_in_a_group-block_3') {

    // Add button for groups on frontpage.
    if (user_access("create ol_group content") && drupal_is_front_page()) {
      $variables['addlink'] = url("node/add/ol-group");
      $variables['typename'] = t("Add Group");
    }
  }

  // Add to content block only.
  if ($blockid == 'openlucius_core-ol_mygroups') {

    // Add button for +groups.
    if (user_access("create ol_group content")) {
      $variables['addlink'] = url("node/add/ol-group");
      $variables['typename'] = t("Group");
    }

    // Hide 'My Groups' when viewing from small screens.
    $variables['classes_array'][] = 'hidden-xs';
  }

  // Add to content block only.
  if ($variables['block_html_id'] == 'block-system-main') {

    // Add button for content overview on frontpage.
    if (user_access("access content overview") && drupal_is_front_page()) {
      $variables['addlink2'] = url("admin/content");
      $variables['typename2'] = t("Content overview");
    }
  }

  // Populate the block titles and 'Add buttons', if Group is not unpublished
  // (archived).
  if (!empty($menu['page_callback']) && $menu['page_callback'] == 'node_page_view') {
    // Get the node from the page arguments.
    $node = $menu['page_arguments'][0];

    if ($node->type == 'ol_group' && $node->status == 0) {
      $group_archived = TRUE;
    }

    if (!$group_archived) {
      switch ($blockid) {
        case "views-messages_in_a_group-block_1":
          if (user_access("create ol_message content") && !$group_archived) {
            $variables['addlink'] = url("node/add/ol-message/" . $node->nid, array('query' => array('destination' => 'node/' . $node->nid)));
          }
          $variables['typelink'] = url("group-messages/" . $node->nid);
          $variables['typename'] = t("Message");
          break;

        case "views-todos_in_group_dashboard-block":
          if (user_access("create ol_todo_list content") && !$group_archived) {
            $variables['addlink'] = url("node/add/ol-todo-list/" . $node->nid);
          }
          $variables['addlink2'] = url("node/add/ol-todo/" . $node->nid);

          $variables['typelink'] = url("group-todo-lists/" . $node->nid);
          $variables['typename'] = t("Todo-list");
          $variables['typename2'] = t("Todo");
          break;

        case "openlucius_files-ol_group_files":
          if (user_access("create file content") && !$group_archived) {
            $variables['addlink'] = url("node/add/file/" . $node->nid, array('query' => array('destination' => 'node/' . $node->nid)));
          }
          $variables['typelink'] = url("group-files/" . $node->nid);
          $variables['typename'] = t("Files");
          break;

        case "views-1ed4d15ee5805f2f30eefdcf3a72a143":
          if (user_access("create ol_text_document content") && !$group_archived) {
            $variables['addlink'] = url("node/add/ol-text-document/" . $node->nid, array('query' => array('destination' => 'node/' . $node->nid)));
          }
          $variables['typelink'] = url("group-textdocuments/" . $node->nid);
          $variables['typename'] = t("Text-document");
          break;

      }
    }
  }

  // Add folder link.
  if (!empty($menu['page_callback']) && $menu['page_callback'] == 'openlucius_files_allfiles' && $menu['path'] != 'all-files') {
    // Load node.
    $node = node_load($menu['page_arguments'][1]);

    if ($node->type == 'ol_group' && $node->status == 0) {
      $group_archived = TRUE;
    }

    if (user_access("create ol_file_folder content") && !$group_archived) {

      $variables['addlink'] = url("node/add/ol-file-folder/" . $node->nid, array('query' => array('destination' => 'group-files/' . $node->nid)));
      $variables['actionlink'] = url("node/add/file/" . $node->nid, array('query' => array('destination' => 'group-files/' . $node->nid)));
      $variables['typelink'] = url("group-files/" . $node->nid);
      $variables['typename'] = t("Folder");
    }
  }

  // Add team link.
  if (!empty($menu['path']) && $menu['path'] == 'all-users') {
    if (user_access("create team content") && !$group_archived) {
      $variables['addlink'] = url("node/add/team", array('query' => array('destination' => 'all-users')));
      $variables['typename'] = t("Team");
    }
  }

  // Add prefix link for folder.
  if ($variables['block_html_id'] == 'block-views-file-folders-block-3') {

    // If not matching add button.
    if (current_path() != 'group-files/' . $menu['page_arguments'][1]) {
      $variables['index'] = l(
        t('Show all recent files'), 'group-files/' . $menu['page_arguments'][1], array(
          'attributes' => array(
            'class' => array('files_backlink'),
          ),
        )
      );
    }
  }
}

/**
 * Implements hook_preprocess_views_view().
 */
function openlucius_preprocess_views_view(&$variables) {

  
  // Default icon for actionlinks.
  $variables['iconactionlink1'] = "plus-sign";

  $view = $variables['view'];
  $arg = $view->args;
  $dest_self = array('query' => array('destination' => current_path()));

  // All users buttons.
  if ($view->name == 'users_in_groups') {
    if ($view->current_display == 'page') {
      // Get custom form.
      $form = drupal_get_form('openlucius_core_add_form', $view);
      $variables['addform'] = drupal_render($form);
    }
    elseif ($view->current_display == 'page_1' && user_access('administer users')) {
      // Add user.
      $variables['addlink'] = l(t('Add user'), 'admin/people/create/');
    }
  }

  $menu_item = menu_get_item();

  // Paths not to show.
  $path_array = array(
    'user/dashboard',
    'user-calendar/month',
  );

  // We are in a group, not a user dashboard.
  if (!empty($view) && !drupal_is_front_page() && !empty($arg[0]) && is_numeric($arg[0]) && !in_array($menu_item['path'], $path_array)) {
    $node = node_load($view->args[0]);

    // Check if Group is unpublished (archived).
    if ($node->status == 0 && isset($node->type) && !empty($node->type)) {
      // Check if message set.
      if (!variable_get('archived_message', FALSE)) {
        drupal_set_message(t("The Group this content belongs to is archived and locked.") . " " . l(t("Unarchive the Group"), "archived-groups"), "warning");

        // Set to TRUE to prevent double messages.
        variable_set('archived_message', TRUE);
      }
    }
    // Group is published.
    else {
      if (user_access("create ol_message content") && $view->name == 'messages_in_a_group' && $view->current_display == 'page_1') {
        $variables['actionlink'] = l(t("Add Message"), "node/add/ol-message/" . $arg[0], $dest_self);
      }
      elseif (user_access("create file content") && $view->name == 'file_folders' && $view->current_display == 'page_1') {
        $variables['actionlink'] = l(t("Add File"), "node/add/file/" . $arg[0], $dest_self);
      }
      elseif (user_access("create ol_text_document content") && $view->name == 'text_documents_in_a_group' && $view->current_display == 'page_1') {
        $variables['actionlink'] = l(t("Add Text-document"), "node/add/ol-text-document/" . $arg[0], $dest_self);
      }
      elseif (user_access("create ol_todo_list content") && $view->name == 'all_todo_lists_in_a_group' && $view->current_display == 'page_1') {
        $variables['actionlink'] = l(t("Add Todo-list"), "node/add/ol-todo-list/" . $arg[0]);
        $variables['actionlink2'] = l(t("Add Todo"), "node/add/ol-todo/" . $arg[0], $dest_self);
      }
    }
  }

  // Used for actionlinks in Everyone and Group users.
  if ($view->name == 'users_in_groups' && $view->current_display == 'page_1') {
    if (user_access("administer users")) {
      // Link to show blocked users view.
      $variables['actionlink'] = l(t("Blocked users"), "blocked-users");
      $variables['iconactionlink'] = "minus-sign";
    }
    // Link to show all users, useful to reset filters.
    $variables['actionlink2'] = l(t("Show all"), "all-users");
    $variables['iconactionlink2'] = "th";
  }

  if ($view->name == 'users_in_groups' && $view->current_display == 'page_2') {
    $variables['actionlink'] = l(t("Active users"), "all-users");
    $variables['iconactionlink'] = "ok-sign";
  }

  if ($view->name == 'users_in_groups' && $view->current_display == 'page') {
    $variables['actionlink2'] = l(t("Show all"), "group-users/" . $arg[0]);
    $variables['iconactionlink2'] = "th";
  }

  // And an execptions for the calendar.
  if ($view->name == 'group_calendar' && $view->current_display == 'page_1') {
    if (user_access("create ol_event content")) {
      $variables['actionlink'] = l(t("Add Event"), "node/add/ol-event/" . $arg[1], $dest_self);
    }
  }

  if ($view->name == 'all_todo_lists_in_a_group' && $view->current_display == 'page_3') {
    if (user_access("create ol_event content")) {
      $variables['actionlink'] = l(t("User's Todo Calendar"), "user-calendar/month/" . $arg[0]);
      $variables['iconactionlink1'] = "inbox";
    }
    $variables['actionlink3'] = l(t("Activity"), "user/" . $arg[0] . "/useractivity");
    $variables['iconactionlink3'] = "transfer";
  }

  // Exception for Todo-list node detail page.
  // if ($view_id == 'view-id-todos_on_todo_list_page') {
  if ($view->name == 'todos_on_todo_list_page' && $view->current_display == 'block') {
    $node = menu_get_object();

    // Get group id from current node.
    $items = field_get_items('node', $node, 'field_shared_group_reference');
    foreach ($items as $item) {
      $groupid = $item['nid'];
    }
    if (user_access("create ol_todo content")) {
      // Add two arguments: 1st for group nid, 2nd for todo-list nid.
      $variables['actionlink'] = l(t("Add Todo in this Todo-list"), "node/add/ol-todo/" . $groupid . '/' . $node->nid);
    }
  }
}

/**
 * Implements template_preprocess_views_view_table().
 */
function openlucius_preprocess_views_view_table(&$vars) {
  if ($vars['view']->name == 'todos_in_group_dashboard' && $vars['view']->current_display == 'block') {
    // Get the list reference from the current result row.
    $list_id = current($vars['result'])->field_data_field_todo_list_reference_field_todo_list_referen;
    // Get the form for the current list.
    $form = drupal_get_form('openlucius_core_add_todo_form', $list_id);
    if (!empty($form)) {
      $vars['inline_todo_form'] = drupal_render($form);
    }
  }

  // Check if this is the todolists in a group view.
  if ($vars['view']->name == 'all_todo_lists_in_a_group' || $vars['view']->name == 'todos_in_group_dashboard') {

    // Check if a user has access to this functionality.
    if (user_access('openlucius todo transfer')) {

      // Get the todolist for this table.
      $keys     = array_keys($vars['result']);
      $list_nid = $vars['result'][$keys[0]]->field_field_todo_list_reference[0]['raw']['nid'];

      // Append the node id as data for the ajax calls.
      $vars['attributes_array']['data-nid'] = $list_nid;

      // Append the user token for security.
      $vars['attributes_array']['data-token'] = drupal_get_token();
    }
  }
}

/**
 * Implements hook_preprocess_html().
 */
function openlucius_preprocess_html(&$vars) {

  global $theme;

  // Only for openlucius and yeti.
  if ($theme == 'openlucius' || $theme == 'yeti') {

    // Add legacy yeti from bootswatch.
    drupal_add_css('//maxcdn.bootstrapcdn.com/bootswatch/3.0.3/yeti/bootstrap.min.css',
      array(
        'type' => 'external',
        'group' => CSS_DEFAULT,
      )
    );
  }

  // Add bootstrap.
  drupal_add_js('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js',
    array('type' => 'external')
  );

  drupal_add_js('//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.3.5/bootstrap-select.min.js',
    array('type' => 'external')
  );
}

/**
 * Implements theme_preprocess_preprocess_page().
 */
function openlucius_preprocess_page(&$vars) {
	
	//matt added 10/22 implements page--contenttype.tpl.php
    if (isset($vars['node']->type)) {
        $nodetype = $vars['node']->type;
        $vars['theme_hook_suggestions'][] = 'page__' . $nodetype;
    }
	//matt added 10/22 passes node info to page
    if (arg(0) == 'node') {
        $vars['node_content'] =& $vars['page']['content']['system_main']['nodes'][arg(1)];
    }
	

  // Fetch group selector.
  $vars['group_selector'] = openlucius_core_get_group_selector();

  // Build icon for node titles.
  $node = menu_get_object();
  if (!empty($node) && !empty($node->nid)) {

    // Build icon in title for node pages.
    switch ($node->type) {
      case "ol_group":
        $vars['node_icon'] = "record";
        break;

      case "file":
        $vars['node_icon'] = "file";
        break;

      case "ol_message":
        $vars['node_icon'] = "envelope";
        break;

      case "ol_text_document":
        $vars['node_icon'] = "font";
        break;

      case "ol_todo_list":
        $vars['node_icon'] = "list-alt";
        break;

      case "ol_todo":
        $vars['node_icon'] = "inbox";
        break;

      case "ol_event":
        $vars['node_icon'] = "calendar";
        break;

    }
  }

  $menu = menu_get_item();
  // Build icon in title for views and user page.
  if ($menu['path'] != 'node/%') {
    switch ($menu['path']) {
      case "ol_group":
        $vars['node_icon'] = "record";
        break;

      case "group-files":
      case "all-files":
        $vars['node_icon'] = "file";
        break;

      case "group-messages":
      case "all-messages":
        $vars['node_icon'] = "envelope";
        break;

      case "group-textdocuments":
      case "all-text-documents":
        $vars['node_icon'] = "font";
        break;

      case "group-todo-lists":
      case "all-todo-lists":
        $vars['node_icon'] = "list-alt";
        break;

      case "group-calendar/month":
      case "all-calendar":
        $vars['node_icon'] = "calendar";
        break;

      case "trash":
        $vars['node_icon'] = "trash";
        break;

      case "archived-groups":
        $vars['node_icon'] = "folder-close";
        break;

      case "comment":
        $vars['node_icon'] = "comment";
        break;

      case "user/dashboard":
        $user = user_load($menu['page_arguments'][2]);
        if (isset($user->name)) {
          if (isset($user->realname) && !empty($user->realname)) {
            $vars['username'] = check_plain($user->realname);
          }
          else {
            $vars['username'] = check_plain($user->name);
          }
        }
        else {
          // Added empty in case of no username.
          $vars['username'] = '';
        }
        $vars['node_icon'] = "user";
        break;

      case "group-users":
      case "all-users":
      case "companies-teams":
      case  "blocked-users":
        $vars['node_icon'] = "user";
        break;

      case "user-calendar":
        $user = user_load($menu['page_arguments'][2]);
        $vars['username'] = check_plain($user->name);
        $vars['node_icon'] = "inbox";
        break;

      case  "search":
        $vars['node_icon'] = "search";
        break;

      // Defaults to globe.
      default:
        $vars['node_icon'] = "globe";
        break;

    }
  }

  // Add plus icon for node/add and add the group crumb tab.
  if ($menu['page_callback'] == 'node_add') {
    $vars['node_icon'] = "plus-sign";

    if (isset($menu['page_arguments'][1]) && is_numeric($menu['page_arguments'][1])) {
      // Get Group data from current node.
      $group_node = node_load($menu['page_arguments'][1]);
      $vars['groupcrumbtab'] = '<a href="' . url('node/' . $group_node->nid) . '"><span class="glyphicon glyphicon-record"></span> ' . check_plain($group_node->title) . '</a>';
    }
  }

  // Building 'breadcrumb' (active group name).
  // Crumbs for node and node/edit pages.
  if (!empty($node) && !empty($node->nid)) {

    // Crumbs for node pages.
    switch ($node->type) {
      case "file":
      case "ol_message":
      case "ol_text_document":
      case "ol_todo":
      case "ol_event":
      case "ol_todo_list":
        // Get Group data from current node.
        $items = field_get_items('node', $node, 'field_shared_group_reference');
        foreach ($items as $item) {
          $groupid = $item['nid'];
        }
        $groupnode = node_load($groupid);
        // Build the crumb.
        $vars['groupcrumbtab'] = '<a href="' . url('node/' . $groupid) . '">
        <span class="glyphicon glyphicon-record"></span> ' . check_plain($groupnode->title) . '</a>';
        break;

    }
  }

  // Crumbs for Views and node/edit.
  if ($menu['page_callback'] == 'views_page' && isset($menu['page_arguments'][2]) && is_numeric($menu['page_arguments'][2])) {
    // Get Group node.
    $node = node_load($menu['page_arguments'][2]);

    switch ($menu['path']) {
      case "group-files":
      case "group-messages":
      case "group-textdocuments":
      case "group-todo-lists":
      case "group-users":
        $vars['groupcrumbtab'] = '<a href="' . url('node/' . $node->nid) . '"><span class="glyphicon glyphicon-record"></span> ' . check_plain($node->title) . '</a>';
        break;
    }
  }

  // Build crumb for calendars and node/add.
  if (isset($menu['page_arguments'][0]) && $menu['page_arguments'][0] == 'group_calendar'
    && isset($menu['page_arguments'][3]) && $menu['page_arguments'][3]) {

    // Get Group data from current node.
    $group_node = node_load($menu['page_arguments'][3]);
    $vars['groupcrumbtab'] = '<a href="' . url('node/' . $group_node->nid) . '"><span class="glyphicon glyphicon-record"></span> ' . check_plain($group_node->title) . '</a>';
  }

  if ($menu['page_callback'] == 'openlucius_files_allfiles' &&
    !empty($menu['page_arguments'][1]) && is_numeric($menu['page_arguments'][1])) {
    // Load group.
    $node = node_load($menu['page_arguments'][1]);

    if (!empty($node)) {
      // Add "add file" button to group files overview and sub-folders.
      $path = 'node/add/file/' . $node->nid;
      if (!empty($menu['page_arguments'][2]) && is_numeric($menu['page_arguments'][2])) {
        $path .= '/' . $menu['page_arguments'][2];
      }

      // Get Group data from current node and add group crumb.
      $group_node = node_load($menu['page_arguments'][3]);
      $vars['groupcrumbtab'] = '<a href="' . url('node/' . $group_node->nid) . '"><span class="glyphicon glyphicon-record"></span> ' . check_plain($group_node->title) . '</a>';

      // Add 'add file' link.
      $vars['add_file_link']
        = '<div class="add-file">
            <a href="' . url($path, array('query' => array('destination' => current_path()))) . '" class="btn btn-default btn-xs btn-openlucius">
                <span class="glyphicon glyphicon-plus-sign"></span> ' . t('Add file(s)') . '
              </a>
          </div>';

    }
  }

  // Determine when to print tabs.
  $path = menu_get_item();

  $places = array(
    'user/login',
    'user/password',
    'admin/config/openlucius',
    'admin/config/openlucius/general',
    'admin/config/openlucius/groups',
    'admin/config/openlucius/frontpage',
    'admin/config/openlucius/menu',
    'admin/config/openlucius/news',
    'admin/config/openlucius/events',
    'admin/config/openlucius/events-not-attending',
    'node/%/webform',
    'node/%/webform/conditionals',
    'node/%/webform/emails',
    'node/%/webform/emails/%',
    'node/%/webform/configure',
    'node/%/webform/components',
    'node/%/webform/components/%',
  );

  if (isset($path['path'])) {
    if (in_array($path['path'], $places)) {
      $vars['print_tabs'] = TRUE;
    }
  }

  // Hide left sidebar on recent stuff for small devices.
  if ($path['path'] == 'recent-stuff') {
    $vars['sidebar_first_class'] = 'hidden-xs';
  }

  if (openlucius_core_user_is_client($vars['user']->uid) && drupal_is_front_page()) {
    $vars['sidebar_toggle'] = '';
  }
  // Check if either the first or second sidebar is set.
  elseif (!empty($vars['page']['sidebar_first']) || !empty($vars['page']['sidebar_second'])) {
    $vars['sidebar_toggle'] = '<a class="btn btn-default btn-xs btn-toggle fixed-right" id="menu-toggle"><i class="glyphicon glyphicon-chevron-left"></i></a>';
  }
}

/**
 * Implements theme_breadcrumb().
 *
 * Theme function for breadcrumbs
 */
function openlucius_breadcrumb($variables) {

$links = array();
  $path = '';

if (arg(0) == 'node' && is_numeric(arg(1))) {
	// Get the nid
	$currentnid = arg(1);
	
}  

  // Get URL arguments
  $arguments = explode('/', request_uri());
	//argument#3 contains the nid of the content


	
//====[+]BASICALLY THE CLIENT WANTS DIFFERENT BREADCRUMB LAYOUTS FOR DIFFERENT CONTENT TYPES, SO ALL OF THE CASES ARE BELOW.
  // Remove empty values
  foreach ($arguments as $key => $value) {
    if (empty($value)) {
      unset($arguments[$key]);
    }
  }
  $arguments = array_values($arguments);

	
	//do not display on login page
	if (strpos($arguments[1],'login') !== false) {
		return false;
		
	}	
	// do not display if on front page
	if (drupal_is_front_page()) {
		return false;
	}

	//check to see if on node project contacts
	if ($arguments[0] == 'node' && $arguments[1] == 'add') {
		$accountname = node_load($arguments[3]);
		$arguments[2] = $accountname->title;
	}	
		//check to see if on field collection project contacts
	if ($arguments[0] == 'field-collection' && $arguments[1] == 'field-project-contacts') {
		if ($arguments[2] == 'add') {
			$projectname = node_load($arguments[5]);
			$bnode = node_load($projectname->field_account_reference[LANGUAGE_NONE][0]['target_id']);
			$builder = $bnode->title;
			$arguments[2] = $builder;
			$arguments[3] = $projectname->title;
			unset($arguments[4]);
			unset($arguments[5]);	
		} else {
			$projectname = node_load($arguments[4]);
			$bnode = node_load($projectname->field_account_reference[LANGUAGE_NONE][0]['target_id']);
			$builder = $bnode->title;
			$arguments[2] = $builder;
			$arguments[3] = $projectname->title;
		}
			
	}
	//if Task then display Home > Builder > Project > Task - THIS IS NOT SOMETHING I WANT TO DO THIS IS WHAT THEY WANT
	if ($arguments[1] == 'tasks') {
		if (isset($_GET['pid'])) {
			$nodearg = $_GET['pid'];
			$projectnode = node_load($nodearg);
			// dpm($projectnode);
			$bnode = node_load($projectnode->field_account_reference[LANGUAGE_NONE][0]['target_id']);
			$builder = $bnode->title;
			// dpm($bnode);
			$arguments[0] = 'TASKS';
			$arguments[1] = $builder;
			$arguments[2] = $projectnode->title;
			 
		} else {
			$projectnode = node_load($currentnid);

			
			$bnode = node_load($projectnode->field_account_reference[LANGUAGE_NONE][0]['target_id']);
			$builder = $bnode->title;
			
	
			$arguments[0] = 'TASKS';
			$arguments[1] = $builder;
			$arguments[2] = $projectnode->title;
			//$arguments[3] = 
		}
			
	}
	if ($arguments[1] == 'projects') {
		 $projectnode = node_load($currentnid);
		 
		 $bnode = node_load($projectnode->field_account_reference[LANGUAGE_NONE][0]['target_id']);
		 $builder = $bnode->title;
		 // dpm($bnode);
		 $arguments[0] = 'PROJECTS';
		 $arguments[1] = $builder;
		 //argument 2 is built down below and already has the title 
	}
		if ($arguments[1] == 'opportunities') {
		 $projectnode = node_load($currentnid);
		 
		 $bnode = node_load($projectnode->field_account_reference[LANGUAGE_NONE][0]['target_id']);
		 $builder = $bnode->title;
		 // dpm($bnode);
		 $arguments[0] = 'OPPORTUNITIES';
		 $arguments[1] = $builder;
		 //argument 2 is built down below and already has the title
		 
	}
  // Add 'Home' link
  $links[] = l(t('Home'), '<front>');

  // Add other links - Client wants accounts, projects, tasks
  if (!empty($arguments)) {
	  
    foreach ($arguments as $key => $value) {
      
      if ($key == (count($arguments) - 1)) {
     
		$links[] = drupal_get_title();

      } else {
		  
        if (!empty($path)) {
          $path .= '/'. $value;
        } else {
          $path .= $value;
        }
		//this makes them static text instead of links
		$links[] = $value;
		//this makes them static text instead of links
        //$links[] = l(drupal_ucfirst($value), $path);
      }
    }
  }

  // Set custom breadcrumbs
  drupal_set_breadcrumb($links);

  // Get custom breadcrumbs
  $breadcrumb = drupal_get_breadcrumb();

  // Hide breadcrumbs if only 'Home' exists
  if (count($breadcrumb) > 1) {
	  //dpm('wtf');
    return '<div class="breadcrumb">'. implode(' &raquo; ', $breadcrumb) .'</div>';
	
  }
}
/**
 * Implements theme_heartbeat_time_ago().
 *
 * Theme function for the timestamp of a message.
 */
function openlucius_heartbeat_time_ago($variables) {

  $message = $variables['heartbeat_activity'];

  $time_info = '';

  if ($message->show_message_times) {
    $message_date = _theme_time_ago($message->timestamp);
    if ($message->target_count <= 1 || $message->show_message_times_grouped) {

      $time_info .= '<span class="heartbeat-time-ago">';
      // Overridden to remove the link.
      $time_info .= $message_date;
      $time_info .= '</span>';

    }
  }

  return $time_info;
}

/**
 * Implements theme_heartbeat_activity_avatar().
 */
function openlucius_heartbeat_activity_avatar($variables) {
  $filepath = $variables['uri'];
  $alt = t("@user's picture", array('@user' => format_username($variables['heartbeatactivity']->actor)));
  if (module_exists('image') && file_valid_uri($filepath)) {
    $markup = theme('image_style', array(
      // Change to own imagestyle which has an aspect switcher.
      'style_name' => 'ol_50x50',
      'path' => $filepath,
      'alt' => $alt,
      'title' => $alt,
      'attributes' => array('class' => 'avatar'),
    ));
  }
  else {
    $markup = theme('image', array(
      'path' => $filepath,
      'alt' => $alt,
      'title' => $alt,
      'attributes' => array('class' => 'avatar'),
    ));
  }
  return array('#markup' => $markup);

}

/**
 * Implements theme_preprocess_field().
 */
function openlucius_preprocess_field(&$variables) {

//Matt Add rotate image buttons - corresponds with function in ec_app.module hook_menu /admin/rotate
if ($variables['element']['#field_name'] == 'field_image') {
	$fid = $variables['items'][0]['#item']['fid'];
	$imgurl = '"/admin/rotate/' . $fid . '/cw/field_image">&#8635; Rotate CW</a>';
	$imgurl2 = '"/admin/rotate/' . $fid . '/ccw/field_image">Rotate CCW &#8634;</a>';
    $variables['items'][1]['#markup'] = '<div class="field-image-rotate"><div class="cw"><a href='. $imgurl . '</a></div><div class="ccw"><a href='. $imgurl2 . '</a></div></div>';
 }
 
  // Array of field_names with their icon.
  $list = array(
    'field_todo_user_reference' => 'hand-right',
    'field_todo_list_reference' => 'list-alt',
    'field_todo_due_date_singledate' => 'calendar',
    'field_todo_label' => 'flag',
    'field_shared_show_clients' => 'eye-open',
  );
  
  // Get the menu object.
  $menu = menu_get_object();

  if (isset($menu->type) && $menu->type == 'ol_todo' && isset($variables['element']['#field_name'])) {
    // Loop list items.
    foreach ($list as $field_name => $icon) {
      // Check if the element field name is there as key.
      if ($variables['element']['#field_name'] == $field_name) {
        // Set the field label to be the glyphicon (array value).
        $variables['label'] = '<i class="glyphicon glyphicon-' . $icon . '"></i>';
      }
    }
  }
}
function openlucius_menu_local_tasks_alter(&$data, $router_item, $root_path) {
	//Matt added whole function 10/28/2015
	//added class to Move tab so it can be hidden with css
  if ($data['tabs'][0]['output'][2]['#link']['title'] == 'Move') {
    // Grab the move tab.
	//dpm($data['tabs']);
	$mytab = &$data['tabs'][0]['output'][2];
    // Add a class to the link element.
    $mytab['#link']['localized_options']['attributes']['class'][] = 'ec-move';
  }
  if ($data['tabs'][0]['output'][4]['#link']['title'] == 'Move') {
    // Grab the move tab.
	//dpm($data['tabs']);
	$mytab = &$data['tabs'][0]['output'][4];
    // Add a class to the link element.
    $mytab['#link']['localized_options']['attributes']['class'][] = 'ec-move';
  }
  //put class around edit tab to target with css
    if ($data['tabs'][0]['output'][1]['#link']['title'] == 'Edit') {
    // Grab the move tab.
    $edittab = &$data['tabs'][0]['output'][1];
    // Add a class to the link element.
    $edittab['#link']['localized_options']['attributes']['class'][] = 'ec-edit';
  }
  
}
function openlucius_preprocess_search_result(&$variables) {
	
	$variables['nodetype'] = $variables['result']['node']->type;
	if (isset($variables['result']['node']->field_account_reference['und'][0]['target_id'])) {
		$acct = node_load($variables['result']['node']->field_account_reference['und'][0]['target_id']);
		$variables['account'] = $acct->title;
	}
	
	
}