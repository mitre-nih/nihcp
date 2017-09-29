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
 


// get the page owner
$page_owner = elgg_get_page_owner_entity();

if (!elgg_instanceof($page_owner, "group")) {
	register_error(elgg_echo("user_support:page_owner:not_group"));
	forward(REFERER);
}

elgg_push_context("faq");

// build breadcrumb
elgg_push_breadcrumb($page_owner->name);

// build page elements
$title_text = elgg_echo("user_support:faq:group:title", array($page_owner->name));

$list_options = array(
	"type" => "object",
	"subtype" => UserSupportFAQ::SUBTYPE,
	"container_guid" => $page_owner->getGUID(),
	"full_view" => false,
	"no_results" => elgg_echo("user_support:faq:not_found")
);

$content = elgg_list_entities($list_options);

// build page
$page_data = elgg_view_layout("content", array(
	"title" => $title_text,
	"content" => $content,
	"filter" => ""
));

elgg_pop_context();

// draw page
echo elgg_view_page($title_text, $page_data);
