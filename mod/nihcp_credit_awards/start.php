<?php

elgg_register_event_handler('init', 'system', 'credit_awards_init');

function credit_awards_init() {
	$base_dir = __DIR__ . '/actions/nihcp_credit_awards';
	//elgg_register_action('nihcp_credit_awards', "$base_dir/nihcp_credit_awards.php");

	// Extend the main CSS file
	//elgg_extend_view('elgg.css', 'nihcp_credit_awards.css');

	// Require your JavaScript AMD module (view "my_plugin.js") on every page
	// elgg_require_js('nihcp_credit_awards');

	// Add a menu item to the main site menu
	$item = new ElggMenuItem('nihcp_credit_awards', elgg_echo('nihcp_credit_awards:menu'), 'my_url');
	elgg_register_menu_item('site', $item);

	// Add a new dummy widget
	elgg_register_widget_type('nihcp_credit_awards', elgg_echo("nihcp_credit_awards"), elgg_echo("nihcp_credit_awards:widget:description"));
}
