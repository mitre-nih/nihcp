<?php

use \Nihcp\Entity\CommonsCreditRequestDelegation;
use \Nihcp\Entity\CommonsCreditRequest;

$delegation_guid = get_input('delegation_guid');

if (empty($delegation_guid)) {
    return false;
} else {

	if(CommonsCreditRequestDelegation::revokeDelegation($delegation_guid)) {
		system_message("Delegation revoked.");
		return true;
    } else {
		register_error("An error occurred. Unable to revoke delegation.");
		return false;
	}


}