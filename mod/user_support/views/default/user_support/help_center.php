<?php
/*
Copyright 2017 The MITRE Corporation

This software was written for the NIH Commons Credit Portal.
General questions can be forwarded to:
 
opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
 
$group = elgg_extract("group", $vars);
$contextual_help_object = elgg_extract("contextual_help_object", $vars);
$faq = elgg_extract("faq", $vars);

$help_enabled = false;
if (elgg_get_plugin_setting("help_enabled", "user_support") != "no") {
	$help_enabled = true;
}

echo elgg_view("input/text", array(
	"id" => "user-support-help-center-search",
	"name" => "q",
	"placeholder" => elgg_echo("search"),
	"class" => "mbs",
));

echo "<div>";

if ($user = elgg_get_logged_in_user_entity()) {
	echo elgg_view("output/url", array(
		"text" => elgg_echo("user_support:help_center:ask"),
		"href" => "#",
		"id" => "user-support-help-center-ask",
		"class" => "elgg-button elgg-button-action mrs"
	));
	echo elgg_view("output/url", array(
		"text" => elgg_echo("user_support:menu:support_tickets:mine"),
		"href" => "user_support/support_ticket/owner/" . $user->username,
		"class" => "elgg-button elgg-button-action mrs"
	));
}

echo elgg_view("output/url", array(
	"text" => elgg_echo("user_support:menu:faq"),
	"href" => "user_support/faq",
	"class" => "elgg-button elgg-button-action mrs"
));

if (!empty($group)) {
	echo elgg_view("output/url", array(
		"text" => elgg_echo("user_support:help_center:help_group"),
		"href" => $group->getURL(),
		"class" => "elgg-button elgg-button-action mrs"
	));
}

if (elgg_is_admin_logged_in() && empty($contextual_help_object) && $help_enabled && elgg_is_xhr()) {
	echo elgg_view("output/url", array(
		"text" => elgg_echo("user_support:help_center:help"),
		"href" => "#",
		"id" => "user-support-help-center-add-help",
		"class" => "elgg-button elgg-button-action mrs"
	));
}

echo "</div>";

if (elgg_is_xhr() && $help_enabled) {
	if (!empty($contextual_help_object)) {
		$contextual_help = elgg_view_entity($contextual_help_object, array(
			"title" => false,
			"full_view" => false
		));
		
		echo elgg_view_module("info", elgg_echo("user_support:help_center:help:title"), $contextual_help, array("id" => "user_support_help_center_help", "class" => "mts"));
	}
	
	if (elgg_is_admin_logged_in()) {
		$form = elgg_view_form("user_support/help/edit", null, $vars);
		
		echo elgg_view_module("info", elgg_echo("user_support:forms:help:title"), $form, array("id" => "user_support_help_edit_form_wrapper", "class" => "hidden mts"));
	}
}

if (!empty($faq)) {
	echo elgg_view_module("info", elgg_echo("user_support:help_center:faq:title"), $faq, array("class" => "mts"));
}

if (elgg_is_logged_in()) {
	$form = elgg_view_form("user_support/support_ticket/edit", null, $vars);
	
	echo elgg_view_module("info", elgg_echo("user_support:help_center:ask"), $form, array("id" => "user_support_ticket_edit_form_wrapper", "class" => "hidden mts"));
}

echo "<div id='user_support_help_search_result_wrapper' class='hidden'></div>";
