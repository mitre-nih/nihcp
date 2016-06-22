<?php

elgg_register_event_handler('init', 'system', 'portal_usage_init');

function portal_usage_init() {
	$base_dir = __DIR__ . '/actions/nihcp_portal_usage';
	//elgg_register_action('nihcp_portal_usage', "$base_dir/nihcp_portal_usage.php");

	// Extend the main CSS file
	//elgg_extend_view('elgg.css', 'nihcp_portal_usage.css');

	// Require your JavaScript AMD module (view "my_plugin.js") on every page
	// elgg_require_js('nihcp_portal_usage');

	// Add a menu item to the main site menu
	$item = new ElggMenuItem('nihcp_portal_usage', elgg_echo('nihcp_portal_usage:menu'), 'my_url');
	elgg_register_menu_item('site', $item);

	// Add a new dummy widget
	elgg_register_widget_type('nihcp_portal_usage', elgg_echo("nihcp_portal_usage"), elgg_echo("nihcp_portal_usage:widget:description"));
}