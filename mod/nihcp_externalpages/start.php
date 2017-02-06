<?php
elgg_register_event_handler('init', 'system', 'nihcp_expages_init');

elgg_set_plugin_setting('pages',  implode(',', ['rob']), 'nihcp_externalpages');

function nihcp_expages_init() {
	$pages = explode(',', elgg_get_plugin_setting('pages', 'nihcp_externalpages'));
	foreach($pages as $page) {
		elgg_register_page_handler($page, 'expages_page_handler');
		// add footer links
		nihcp_expages_setup_footer_menu($page);
	}

	// Register public external pages
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'nihcp_expages_public');
}

/**
 * Extend the public pages range
 *
 */
function nihcp_expages_public($hook, $handler, $return, $params){
	$pages = explode(',', elgg_get_plugin_setting('pages', 'nihcp_externalpages'));
	return array_merge($pages, $return);
}

/**
 * Setup the links to site pages
 */
function nihcp_expages_setup_footer_menu($page) {
	$url = "$page";
	$wg_item = new ElggMenuItem($page, elgg_echo("expages:$page"), $url);
	elgg_register_menu_item('walled_garden', $wg_item);

	$footer_item = clone $wg_item;
	elgg_register_menu_item('footer', $footer_item);
}
