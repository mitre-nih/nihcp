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
 

/**
 * Default widgets landing page.
 *
 * @package Elgg.Core
 * @subpackage Administration.DefaultWidgets
 */

$object = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'moddefaultwidgets',
	'distinct' => false,
	'limit' => 1,
));

if ($object) {
	echo elgg_view('output/url', array(
		'text' => elgg_echo('upgrade'),
		'href' => 'action/widgets/upgrade',
		'is_action' => true,
		'is_trusted' => true,
		'class' => 'elgg_button elgg-button-submit',
		'title' => 'Upgrade your default widgets to work on Elgg 1.8',
	));
}

elgg_push_context('default_widgets');
$widget_context = get_input('widget_context');

$list = elgg_trigger_plugin_hook('get_list', 'default_widgets', null, array());

// default to something if we can
if (!$widget_context && $list) {
	$widget_context = $list[0]['widget_context'];
}

$current_info = null;
$tabs = array();
foreach ($list as $info) {
	$url = "admin/appearance/default_widgets?widget_context={$info['widget_context']}";
	$selected = false;
	if ($widget_context == $info['widget_context']) {
		$selected = true;
		$current_info = $info;
	}

	$tabs[] = array(
		'title' => $info['name'],
		'url' => $url,
		'selected' => $selected
	);
}

$tabs_vars = array(
	'tabs' => $tabs
);

echo elgg_view('navigation/tabs', $tabs_vars);

echo elgg_view('output/longtext', array('value' => elgg_echo('admin:default_widgets:instructions')));

if (!$current_info) {
	$content = elgg_echo('admin:default_widgets:unknown_type');
} else {
	// default widgets are owned and saved to the site.
	elgg_set_page_owner_guid(elgg_get_config('site_guid'));
	elgg_push_context($current_info['widget_context']);

	$default_widgets_input = elgg_view('input/hidden', array(
		'name' => 'default_widgets',
		'value' => 1
	));

	$params = array(
		'content' => $default_widgets_input,
		'num_columns' => $current_info['widget_columns'],
	);

	$content = elgg_view_layout('widgets', $params);
	elgg_pop_context();
}
elgg_pop_context();

echo $content;
