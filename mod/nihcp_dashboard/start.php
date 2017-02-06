<?php
/**
 * A user dashboard
 */


use \Nihcp\Manager\WidgetManager;

elgg_unregister_event_handler('init', 'system', 'dashboard_init');
elgg_register_event_handler('init', 'system', 'nihcp_dashboard_init');
elgg_register_event_handler('ready', 'system', 'nihcp_system_init', 10000);

function nihcp_system_init() {
	elgg_unregister_event_handler('join', 'group', '_elgg_create_default_widgets');
	elgg_register_event_handler('join', 'group', 'nihcp_create_default_widgets');
	elgg_register_event_handler('leave', 'group', 'nihcp_update_role_widgets');
}

function nihcp_dashboard_init() {

	elgg_register_page_handler('dashboard', 'nihcp_dashboard_page_handler');

	elgg_register_action("nihcp_dashboard/reinit_user_widgets", dirname(__FILE__) . "/actions/admin/reinit_user_widgets.php", "admin");

	elgg_extend_view('css/elgg', 'dashboard/css');
	elgg_extend_view('js/elgg', 'dashboard/js');

	elgg_register_menu_item('topbar', array(
		'name' => 'dashboard',
		'href' => 'dashboard',
		'text' => elgg_view_icon('home') . elgg_echo('dashboard'),
		'priority' => 450,
		'section' => 'alt',
	));

	elgg_register_plugin_hook_handler('get_list', 'default_widgets', 'nihcp_dashboard_default_widgets');

	elgg_register_plugin_hook_handler('prepare', 'menu:widget', 'nihcp_widget_menu_setup');


	elgg_register_event_handler('login:after', 'user', 'nihcp_dashboard_helpdesk_reminder');

	elgg_register_widget_type('reinit_user_widgets', elgg_echo("nihcp_dashboard:widgets:reinit_user_widgets:title"), elgg_echo("nihcp_dashboard:widgets:reinit_user_widgets:description"), array("admin"));
}


// Updates which dashboard widgets a user is supposed to have after they are removed from a role group.
function nihcp_update_role_widgets($event, $type, $params) {

	elgg_admin_gatekeeper();

	$user = $params['user'];
	$group_left = $params['group'];

	$widgets = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'widget',
		'owner_guids' => array($user->getGUID()))
	);

	foreach ($widgets as $widget) {
		if ($widget->getContext() !== 'admin') {
			$widget->delete();
		}
	}

	WidgetManager::createWidgets();

	$roles = $user->getGroups(array());

	foreach ($roles as $role) {
		if ($role->getDisplayName() !== $group_left->getDisplayName()) {
			WidgetManager::createWidgetsForUserInGroup($user, $role);
		}
	}
}

function nihcp_dashboard_helpdesk_reminder($event, $type, $user) {
	system_message(elgg_echo("nihcp_dashboard:help:first_time_notification"));
}

/**
 * Dashboard page handler
 * @return bool
 */
function nihcp_dashboard_page_handler() {
	// Ensure that only logged-in users can see this page
	elgg_gatekeeper();

	// Set context and title
	elgg_set_context('dashboard');
	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
	$title = elgg_echo('dashboard');

	$roles = \Nihcp\Manager\RoleManager::getRolesByUser(elgg_get_logged_in_user_guid());
	$intro_message = '';
	if(!$roles && !elgg_is_admin_logged_in()) {
		$intro_message = elgg_view('nihcp_dashboard/blurb');
	}

	$params = array(
		'content' => $intro_message,
		'num_columns' => 3,
		'show_access' => false,
	);

	if(!elgg_is_admin_logged_in()) {
		$params['show_add_widgets'] = false;
	}

	$widgets = elgg_view_layout('widgets', $params);

	$body = elgg_view_layout('one_column', array(
		'title' => false,
		'content' => $widgets
	));

	echo elgg_view_page($title, $body);
	return true;
}


/**
 * Register user dashboard with default widgets
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $return
 * @param unknown_type $params
 * @return array
 */
function nihcp_dashboard_default_widgets($hook, $type, $return, $params) {
	$ia = elgg_set_ignore_access(true);
	$groups = elgg_get_entities(array('type'=>'group'));
	$return=[];
	foreach($groups as $group) {
		array_push($return, [
			'widget_context'=>$group->guid,
			'name'=>$group->getDisplayName(),
			'widget_columns' => 3,

			'event' => 'join',
			'entity_type' => 'group',
			'entity_subtype' => ELGG_ENTITIES_ANY_VALUE,]);
	}
	elgg_set_ignore_access($ia);
	return $return;
}

/**
 * Creates default widgets
 *
 * This plugin hook handler is registered for events based on what kinds of
 * default widgets have been registered. See elgg_default_widgets_init() for
 * information on registering new default widget contexts.
 *
 * @param string $event  The event
 * @param string $type   The type of object
 * @param \ElggEntity $entity The entity being created
 * @return void
 * @access private
 */
function nihcp_create_default_widgets($event, $type, $params) {
	$entity = $params['user'];
	$group = $params['group'];

	WidgetManager::createWidgetsForUserInGroup($entity, $group);
}

function nihcp_widget_menu_setup($hook, $type, $return, $params) {
	if(!elgg_is_admin_logged_in()) {
		foreach ($return['default'] as $i => $item) {
			if (in_array($item->getName(), ['delete', 'settings'])) {
				unset($return['default'][$i]);
			}
		}
	}

	return $return;
}