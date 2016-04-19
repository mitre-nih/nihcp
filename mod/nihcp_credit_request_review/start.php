<?php
/**
 * Describe plugin here
 */

elgg_register_event_handler('init', 'system', 'credit_request_review_init');

function credit_request_review_init() {
	// Rename this function based on the name of your plugin and update the
	// elgg_register_event_handler() call accordingly

	// Register a script to handle (usually) a POST request (an action)
	$base_dir = __DIR__ . '/actions/nihcp_credit_request_review';
	elgg_register_action('nihcp_credit_request_review', "$base_dir/my_action.php");

	// Extend the main CSS file
	elgg_extend_view('elgg.css', 'nihcp_credit_request_review.css');

	// Require your JavaScript AMD module (view "my_plugin.js") on every page
	// elgg_require_js('nihcp_credit_request_review');

	// Add a menu item to the main site menu
	$item = new ElggMenuItem('nihcp_credit_request_review', elgg_echo('nihcp_credit_request_review:menu'), 'my_url');
	elgg_register_menu_item('site', $item);

	// Add a new dummy widget
	elgg_register_widget_type('nihcp_credit_request_review', elgg_echo("nihcp_credit_request_review"), elgg_echo("nihcp_credit_request_review:widget:description"));
}
