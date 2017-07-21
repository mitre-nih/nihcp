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
 
$q = sanitise_string(get_input("q"));
$content = "";

$params = array(
	"query" => $q,
	"search_type" => "entities",
	"type" => "object",
	"subtype" => UserSupportHelp::SUBTYPE,
	"limit" => 5,
	"offset" => 0,
	"sort" => "relevance",
	"order" => "desc",
	"owner_guid" => ELGG_ENTITIES_ANY_VALUE,
	"container_guid" => ELGG_ENTITIES_ANY_VALUE,
	"pagination" => false,
	"full_view" => false,
	"view_type_toggle" => false
);

if ($result = elgg_trigger_plugin_hook("search", "object:" . UserSupportHelp::SUBTYPE, $params, array())) {
	$help_entities = $result["entities"];
} elseif ($result = elgg_trigger_plugin_hook("search", "object", $params, array())) {
	$help_entities = $result["entities"];
}

if (!empty($help_entities)) {
	$content .= elgg_view_entity_list($help_entities, $params);
}

// Search in FAQ
$params["subtype"] = UserSupportFAQ::SUBTYPE;

if ($result = elgg_trigger_plugin_hook("search", "object:" . UserSupportFAQ::SUBTYPE, $params, array())) {
	$faq_entities = $result["entities"];
} elseif ($result = elgg_trigger_plugin_hook("search", "object", $params, array())) {
	$faq_entities = $result["entities"];
}

if (!empty($faq_entities)) {
	$content .= elgg_view_entity_list($faq_entities, $params);
}

if (empty($help_entities) && empty($faq_entities)) {
	$content = elgg_echo("notfound");
}

echo elgg_view_module("info", elgg_echo("search:results", array("\"" . $q . "\"")), $content, array("class" => "mts"));
