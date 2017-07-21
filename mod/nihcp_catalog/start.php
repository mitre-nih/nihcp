<?php
/*
Copyright 2017 The MITRE Corporation
 
This software was written for the NIH Commons Credit Portal. General questions 
can be forwarded to:

opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
 

use \Nihcp\Manager\RoleManager;

global $CATALOG_TYPES;
$CATALOG_TYPES = ['services', 'equivalency', 'glossary'];
global $CATALOG_ACTIONS;
$CATALOG_ACTIONS = ['add', 'history'];

elgg_register_event_handler('init', 'system', 'nihcp_catalog_init');

function nihcp_catalog_init() {
	// Rename this function based on the name of your plugin and update the
	// elgg_register_event_handler() call accordingly

	// Register a script to handle (usually) a POST request (an action)
	$action_path = __DIR__ . '/actions';
	elgg_register_action('catalog/save', "$action_path/catalog/save.php");
	// Extend the main CSS file
	elgg_extend_view('css/elements/layout', 'css/layout');

	// Require your JavaScript AMD module (view "my_plugin.js") on every page
	//elgg_require_js('nihcp_catalog');

	global $CATALOG_TYPES;
	foreach($CATALOG_TYPES as $type) {
		elgg_register_entity_type('object', $type);
	}
	// Add a menu item to the main site menu
	//$item = new ElggMenuItem('nihcp_catalog', elgg_echo('nihcp_catalog:menu'), 'my_url');
	//elgg_register_menu_item('site', $item);

	elgg_unregister_menu_item('extras', 'rss');

	if(elgg_is_logged_in()) {
		$user = elgg_get_logged_in_user_entity();
		$vendor_admin = RoleManager::getRoleByName("Vendor Administrator");
		global $CATALOG_ACTIONS;
		foreach($CATALOG_TYPES as $type) {
			$type_name = elgg_echo("item:object:$type");
			elgg_register_menu_item('extras', array(
				'name' => $type,
				'href' => "catalog/$type",
				'text' => elgg_echo("item:object:$type"),
			));

			if ($user && (($vendor_admin && $vendor_admin->isMember($user)) || $user->isAdmin())) {
				foreach ($CATALOG_ACTIONS as $action) {
					if (elgg_in_context('catalog')) {
						elgg_register_menu_item('extras', array(
							'name' => "$type-$action",
							'href' => "catalog/$type/$action",
							'text' => elgg_echo("nihcp_catalog:menu:$action", [$type_name]),
						));
					}
				}
			}
		}
	}

	// Add a new widget
	elgg_register_widget_type('nihcp_catalog', elgg_echo("nihcp_catalog"), elgg_echo("nihcp_catalog:widget:description"));

	elgg_register_page_handler('catalog', 'nihcp_catalog_page_handler');
}

function nihcp_catalog_page_handler($page) {
	$pages = dirname(__FILE__) . '/pages';
	global $CATALOG_TYPES;
	global $CATALOG_ACTIONS;
	if (in_array($page[0], $CATALOG_TYPES)) {
		if(!in_array($page[1], $CATALOG_ACTIONS)) {
			$page[1] = 'view';
		}
		set_input('subtype', $page[0]);
		include "$pages/catalog/$page[1].php";
		return true;
	}
	return false;
}