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
 
$entity = elgg_extract("contextual_help_object", $vars);

if (!empty($entity)) {
	$desc = $entity->description;
	$tags = $entity->tags;
	
	$form_body = elgg_view("input/hidden", array("name" => "guid", "value" => $entity->getGUID()));
	$form_body .= elgg_view("input/hidden", array("name" => "help_context", "value" => $entity->help_context));
} else {
	
	$desc = "";
	$tags = array();
	$help_context = elgg_extract("help_context", $vars);
	
	if (!empty($help_context)) {
		$form_body = elgg_view("input/hidden", array("name" => "help_context", "value" => $help_context));
	} else {
		$form_body = elgg_view("input/hidden", array("name" => "help_context", "value" => user_support_get_help_context()));
	}
}

$form_body .= "<div>";
$form_body .= "<label>" . elgg_echo("description") . "</label>";
$form_body .= elgg_view("input/longtext", array("name" => "description", "value" => $desc));
$form_body .= "</div>";

$form_body .= "<div>";
$form_body .= "<label>" . elgg_echo("tags") . "</label>";
$form_body .= elgg_view("input/tags", array("name" => "tags", "value" => $tags));
$form_body .= "</div>";

$form_body .= "<div class='elgg-foot'>";
$form_body .= elgg_view("input/submit", array("value" => elgg_echo("submit")));
$form_body .= elgg_view("input/reset", array("value" => elgg_echo("cancel")));
$form_body .= "</div>";

echo $form_body;