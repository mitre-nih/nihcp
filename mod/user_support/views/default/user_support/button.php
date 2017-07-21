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
 


if (elgg_in_context("admin")) {
	return;
}

$show_floating_button = elgg_get_plugin_setting("show_floating_button", "user_support");
if (empty($show_floating_button) || $show_floating_button == "no") {
	return;
}
	
$help_context = user_support_get_help_context();
$contextual_help_object = user_support_get_help_for_context($help_context);

$faq_options = array(
	"type" => "object",
	"subtype" => UserSupportFAQ::SUBTYPE,
	"count" => true,
	"metadata_name_value_pairs" => array("help_context" => $help_context)
);

if (elgg_get_plugin_setting("ignore_site_guid", "user_support") !== "no") {
	$faq_options["site_guids"] = false;
}

$link_text = "";
foreach (str_split(strtoupper(elgg_echo("user_support:button:text"))) as $char) {
	$link_text .= $char . "<br />";
}

$link_options = array(
	"href" => "user_support/help_center",
	"text" => $link_text,
	"class" => array("user-support-button-help-center")
);

if ((!empty($contextual_help_object) && (elgg_get_plugin_setting("help_enabled", "user_support") != "no")) || ($help_context !== false && elgg_get_entities_from_metadata($faq_options))) {
	$link_options["class"][] = "elgg-state-active";
}

if (elgg_get_plugin_setting("show_as_popup", "user_support") != "no") {
	elgg_load_js("lightbox");
	elgg_load_css("lightbox");
	$link_options["class"][] = "elgg-lightbox";
}

// position settings
$horizontal = "left";
$vertical = "top";
$offset = elgg_get_plugin_setting("float_button_offset", "user_support");
if (is_null($offset) || $offset === false) {
	$offset = 150;
}
$offset = sanitise_int($offset);

if ($show_floating_button) {
	list($horizontal, $vertical) = explode("|", $show_floating_button);
}
		
echo "<div id='user-support-button' title='" . elgg_echo("user_support:button:hover") . "' style='$horizontal:0; $vertical: {$offset}px;'>";
echo elgg_view("output/url", $link_options);
echo "</div>";
