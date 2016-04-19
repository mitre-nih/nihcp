<?php

// staff only action
user_support_staff_gatekeeper();

$guid = (int) get_input("guid");

$user = elgg_get_logged_in_user_entity();

// ignore access restrictions to be able to access support ticket entity
if(user_support_staff_gatekeeper()) {
    $ia = elgg_set_ignore_access(true);
    $entity = get_entity($guid);
    elgg_set_ignore_access($ia); // reset ignoring access to previous value
}

if (!empty($guid) && $entity) {
	if (elgg_instanceof($entity, "object", UserSupportTicket::SUBTYPE, "UserSupportTicket")) {
		create_annotation($entity->getGUID(),
							'generic_comment',
							elgg_echo("user_support:support_ticket:closed"),
							"",
							$user->getGUID(),
							$entity->access_id);
							
		if ($entity->setStatus(UserSupportTicket::CLOSED)) {
			notify_user($entity->getOwnerGUID(),
				$user->getGUID(),
				elgg_echo('generic_comment:email:subject'),
				elgg_echo('generic_comment:email:body', array(
					$entity->title,
					$user->name,
					elgg_echo("user_support:support_ticket:closed"),
					$entity->getURL(),
					$user->name,
					$user->getURL()
				))
			);
			
			system_message(elgg_echo("user_support:action:ticket:close:success"));
		} else {
			register_error(elgg_echo("user_support:action:ticket:close:error:disable"));
		}
	} else {
		register_error(elgg_echo("InvalidClassException:NotValidElggStar", array($guid, "UserSupportTicket")));
	}
} else {
	register_error(elgg_echo("InvalidParameterException:MissingParameter"));
}

forward(REFERER);
