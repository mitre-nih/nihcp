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
 
$entity = elgg_extract("entity", $vars);
$full_view = elgg_extract("full_view", $vars);

// entity menu
$entity_menu = "";
if (!elgg_in_context("widgets")) {
	$entity_menu = elgg_view_menu("entity", array(
		"entity" => $entity,
		"handler" => "user_support/support_ticket",
		"sort_by" => "priority",
		"class" => "elgg-menu-hz"
	));
}

$loggedin_user = elgg_get_logged_in_user_entity();
$owner = $entity->getOwnerEntity();

if (!$full_view) {
	// title
	$title_text = $entity->title;
	if (strlen($title_text) > 50) {
		$title_text = elgg_get_excerpt($title_text, 50);
	}
	$title = elgg_view("output/url", array("href" => $entity->getURL(), "text" => $title_text));
	
	// strapline
	$subtitle = user_support_time_created_string($entity);
	$subtitle .= " " . elgg_echo("by");
	$subtitle .= " " . elgg_view("output/url", array("href" => $owner->getURL(), "text" => $owner->name));
	
	// last comment by
	$info = "";
	if ($ann = $entity->getAnnotations(array("generic_comment"), 1, 0, "desc")) {
		$ann_owner = get_user($ann[0]->owner_guid);
		$url = elgg_view("output/url", array("href" => $ann_owner->getURL(), "text" => $ann_owner->name));
		$info = elgg_echo("user_support:last_comment", array($url));
	}
	
	$params = array(
		"entity" => $entity,
		"metadata" => $entity_menu,
		"content" => $info,
		"subtitle" => $subtitle,
		"tags" => elgg_view("output/tags", array("value" => $entity->tags)),
		"title" => $title
	);
	$params = $params + $vars;
	echo elgg_view("object/elements/summary", $params);
} else {
	// icon
	$icon = elgg_view_entity_icon($entity, "tiny");
	
	$subtitle = user_support_time_created_string($entity);
	$subtitle .= " " . elgg_echo("by");
	$subtitle .= " " . elgg_view("output/url", array("href" => $owner->getURL(), "text" => $owner->name));
	
	// summary
	$params = array(
		"entity" => $entity,
		"metadata" => $entity_menu,
		"tags" => elgg_view("output/tags", array("value" => $entity->tags)),
		"subtitle" => $subtitle,
		"title" => false
	);
	$params = $params + $vars;
	$summary = elgg_view("object/elements/summary", $params);
	
	// body
	$body = "";
	if (!empty($entity->help_url)) {
		$body .= elgg_echo("user_support:url") . ": " . elgg_view("output/url", array("href" => $entity->help_url)) . "<br />";
	}
	
	if (!empty($entity->description)) {
		$body .= elgg_view("output/longtext", array("value" => $entity->description));
	} elseif (strlen($entity->title) > 50) {
		$body .= elgg_view("output/longtext", array("value" => $entity->title));
	}
	
	// blog
	echo elgg_view('object/elements/full', array(
		'summary' => $summary,
		'icon' => $icon,
		'body' => $body,
	));
}
