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
 
$widget = elgg_extract("entity", $vars);
$owner = $widget->getOwnerEntity();

$filter = $widget->filter;
if (empty($filter)) {
	$filter = UserSupportTicket::OPEN;
}

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 4;
}

$more_link = "user_support/support_ticket/owner/" . $owner->username;

$options = array(
	"type" => "object",
	"subtype" => UserSupportTicket::SUBTYPE,
	"owner_guid" => $widget->getOwnerGUID(),
	"limit" => $num_display,
	"pagination" => false,
	"full_view" => false,
	"order_by" => "e.time_updated desc"
);

if ($filter != "all") {
	$options["metadata_name_value_pairs"] = array("status" => $filter);
	
	if ($filter == UserSupportTicket::CLOSED) {
		$more_link .= "/archive";
	}
}

if ($content = elgg_list_entities_from_metadata($options)) {
	$content .= "<div class='elgg-widget-more clearfix'>";
	$content .= elgg_view("output/url", array("text" => elgg_echo("user_support:read_more"), "href" => $more_link, "class" => "float-alt"));
	$content .= "</div>";
} else {
	$content = elgg_view("output/longtext", array("value" => elgg_echo("notfound")));
}

echo $content;