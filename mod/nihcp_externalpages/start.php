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
