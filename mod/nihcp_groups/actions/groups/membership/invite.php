<?php

$logged_in_user = elgg_get_logged_in_user_entity();

$user_guids = get_input('user_guid');
if (!is_array($user_guids)) {
	$user_guids = array($user_guids);
}
$group_guid = get_input('group_guid');
$group = get_entity($group_guid);

if (count($user_guids) > 0 && elgg_instanceof($group, 'group') && $group->canEdit()) {
	foreach ($user_guids as $guid) {
		$user = get_user($guid);
		if (!$user) {
			continue;
		}

		if (check_entity_relationship($user->guid, 'member', $group->guid)) {
			register_error(elgg_echo("groups:useralreadymember"));
			continue;
		}

		$result = $group->join($user);

		if ($result) {
			system_message(elgg_echo("groups:useradded"));
		} else {
			register_error(elgg_echo("groups:usernotadded"));
		}
	}
}

forward(REFERER);
