<?php

elgg_register_event_handler('init', 'system', 'audit_log_init');

function audit_log_init() {
	$base_dir = __DIR__ . '/actions/nihcp_audit_log';
	//elgg_register_action('nihcp_audit_log', "$base_dir/nihcp_audit_log.php");

	// Extend the main CSS file
	//elgg_extend_view('elgg.css', 'nihcp_audit_log.css');

	// elgg_require_js('nihcp_audit_log');

	// Add a menu item to the main site menu
	$item = new ElggMenuItem('nihcp_audit_log', elgg_echo('nihcp_audit_log:menu'), 'my_url');
	elgg_register_menu_item('site', $item);

	// Add a new dummy widget
	elgg_register_widget_type('nihcp_audit_log', elgg_echo("nihcp_audit_log"), elgg_echo("nihcp_audit_log:widget:description"));
}
