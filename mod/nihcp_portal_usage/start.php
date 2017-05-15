<?php


elgg_register_event_handler('init', 'system', 'portal_usage_init');

//$siteTracker = new \Nihcp\Entity\SiteTracker();

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
	//for the site tracker.  putting this in here instead of the class because I dont want to have to mess around with the path
    $action_path = __DIR__ . '/actions';
    //elgg_register_action('nihcp_portal_usage/export_page_hits', "$action_path/nihcp_portal_usage/export_page_hits.php");
}
