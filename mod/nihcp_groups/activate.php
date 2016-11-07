<?php

use \Nihcp\Manager\RoleManager;

RoleManager::createRoles();

$tc_grp = RoleManager::getRoleByName(RoleManager::TRIAGE_COORDINATOR);
foreach($tc_grp->getMembers(['limit' => 0]) as $mbr) {
	elgg_add_subscription($mbr->getGUID(), 'email', $tc_grp->getGUID());
}