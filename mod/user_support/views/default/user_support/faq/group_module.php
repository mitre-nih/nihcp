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
 
/**
 * Group blog module
 */

$group = elgg_get_page_owner_entity();

if ($group->faq_enable != "yes") {
	return;
}

$all_link = elgg_view("output/url", array(
	"href" => "user_support/faq/group/" . $group->getGUID() . "/all",
	"text" => elgg_echo("link:view:all"),
	"is_trusted" => true,
));

elgg_push_context("widgets");
$options = array(
	"type" => "object",
	"subtype" => UserSupportFAQ::SUBTYPE,
	"container_guid" => $group->getGUID(),
	"limit" => 6,
	"full_view" => false,
	"pagination" => false,
);
$content = elgg_list_entities_from_metadata($options);
elgg_pop_context();

if (!$content) {
	$content = elgg_view("output/longtext", array("value" => elgg_echo("user_support:faq:not_found")));
}

$new_link = "";
if ($group->canEdit()) {
	$new_link = elgg_view("output/url", array(
		"href" => "user_support/faq/add/" . $group->getGUID(),
		"text" => elgg_echo("user_support:menu:faq:create"),
		"is_trusted" => true,
	));
}

echo elgg_view("groups/profile/module", array(
	"title" => elgg_echo("user_support:menu:faq:group"),
	"content" => $content,
	"all_link" => $all_link,
	"add_link" => $new_link,
));
