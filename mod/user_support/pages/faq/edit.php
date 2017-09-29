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
 


elgg_gatekeeper();

elgg_set_page_owner_guid(elgg_get_site_entity()->getGUID());

$title_text = "";
$entity = null;
$guid = (int) get_input("guid");
if (!empty($guid) && ($entity = get_entity($guid))) {
	if (elgg_instanceof($entity, "object", UserSupportFAQ::SUBTYPE, "UserSupportFAQ")) {
		
		$title_text = elgg_echo("user_support:faq:edit:title:edit");
		
		// check for group container
		$container = $entity->getContainerEntity();
		if (elgg_instanceof($container, "group")) {
			elgg_set_page_owner_guid($container->getGUID());
			elgg_push_breadcrumb($container->name, "user_support/faq/group/" . $container->getGUID() . "/all");
		}
	}
}

$page_owner = elgg_get_page_owner_entity();

if (elgg_instanceof($page_owner, "group") && !$page_owner->canEdit()) {
	register_error(elgg_echo("user_support:page_owner:cant_edit"));
	forward(REFERER);
} elseif (elgg_instanceof($page_owner, "site")) {
	elgg_admin_gatekeeper();
}

// make breadcrumb
elgg_push_breadcrumb($title_text);

$help_context = user_support_find_unique_help_context();
$body_vars = array(
	"entity" => $entity,
	"help_context" => $help_context
);
$form = elgg_view_form("user_support/faq/edit", array(), $body_vars);

// build page
$page_data = elgg_view_layout("content", array(
	"title" => $title_text,
	"content" => $form,
	"filter" => ""
));

// draw page
echo elgg_view_page($title_text, $page_data);
