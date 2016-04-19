<?php
/**
 * Describe plugin here
 */

elgg_register_event_handler('init', 'system', 'portal_usage_init');

function portal_usage_init() {
	// Rename this function based on the name of your plugin and update the
	// elgg_register_event_handler() call accordingly

	// Register a script to handle (usually) a POST request (an action)
	$base_dir = __DIR__ . '/actions/nihcp_portal_usage';
	elgg_register_action('nihcp_portal_usage', "$base_dir/nihcp_portal_usage.php");

	// Extend the main CSS file
	elgg_extend_view('elgg.css', 'nihcp_portal_usage.css');

	// Require your JavaScript AMD module (view "my_plugin.js") on every page
	// elgg_require_js('nihcp_portal_usage');

	// Add a menu item to the main site menu
	$item = new ElggMenuItem('nihcp_portal_usage', elgg_echo('nihcp_portal_usage:menu'), 'my_url');
	elgg_register_menu_item('site', $item);

	// Add a new dummy widget
	elgg_register_widget_type('nihcp_portal_usage', elgg_echo("nihcp_portal_usage"), elgg_echo("nihcp_portal_usage:widget:description"));
}
