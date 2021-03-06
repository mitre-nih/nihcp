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
 


elgg_register_event_handler('init', 'system', 'portal_usage_init');

$siteTracker = new \Nihcp\Entity\SiteTracker();

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
    elgg_register_action('nihcp_portal_usage/export_page_hits', "$action_path/nihcp_portal_usage/export_page_hits.php");
}
