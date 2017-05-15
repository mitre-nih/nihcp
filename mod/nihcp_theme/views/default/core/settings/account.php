<?php
/**
 * Account settings form wrapper
 * 
 * @package Elgg
 * @subpackage Core
 */

// This is for keeping the style of titles before.
// The titles were not "<label>" elements. Modifying
// to <label> bolds it. Thus, this style is to bring
// it back to the style before (i.e. normal).
echo "<style>label { font-weight: normal; }</style>";

$action_url = elgg_get_site_url();
if (elgg_get_config('https_login')) {
	$action_url = str_replace("http:", "https:", $action_url);
}
$action_url .= 'action/usersettings/save';

echo elgg_view_form('usersettings/save', array(
	'class' => 'elgg-form-alt',
	'action' => $action_url,
));
