<?php
/*
Copyright 2017 The MITRE Corporation
 
This software was written for the NIH Commons Credit Portal. General questions 
can be forwarded to:

opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
 


use \Nihcp\Manager\RoleManager;

// The onus is on the developer to use this function responsibly since it provides minimal protection on its own
function pseudo_atomic_set_ignore_access(callable $callback, $parameter = null, $override_error_handler = false, $ignore = true) {
	if($override_error_handler) {
		set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
			// error was suppressed with the @-operator
			if (0 === error_reporting()) {
				return false;
			}

			throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
		});
	}

	$ia = elgg_set_ignore_access($ignore);
	try {
		$ret = call_user_func($callback, $parameter);
	} catch (Exception $e) {
		//error_log($e);
		$ret = false;
	} finally {
		elgg_set_ignore_access($ia);
		if($override_error_handler) {
			restore_error_handler();
		}
	}

	return $ret;
}

/**
 * Added gatekeeper function for privileged groups
 *
 * @param bool $forward   forward the user to REFERER
 * @param int  $user_guid the user to check (default: current user)
 *
 * @return bool
 */
function nihcp_role_gatekeeper($role_names, $forward = true, $user_guid = 0, $strict = false) {
	$result = false;

	if(!is_array($role_names)) {
		$role_names = [$role_names];
	}

	$user_guid = sanitise_int($user_guid, false);
	if (empty($user_guid)) {
		$user_guid = elgg_get_logged_in_user_guid();
	}

	if (!empty($user_guid)) {
		$user = get_user($user_guid);
		if ($user && $user->isAdmin()) {
			return true;
		}

		$member_cache = array();

		foreach($role_names as $role_name) {

			$role_group = RoleManager::getRoleByName($role_name);
			if (!$role_group) {
				error_log(elgg_echo("nihcp_groups:role:notfound", $role_name));
				continue;
			}

			// isMember
			$users = $role_group ? $role_group->getMembers(['limit' => 0]) : null;

			if (!empty($users)) {
				foreach ($users as $user) {
					$member_cache[] = $user->getGUID();
				}
			}
		}

		if (in_array($user_guid, $member_cache)) {
			$result = true;
		}

		if($result && $strict) {
			$group_names = array_map(function($group) {return $group->getDisplayName();}, RoleManager::getRolesByUser());
			$diff = array_diff($group_names, $role_names);
			if(!empty($diff)) {
				$result = false;
			}
		}
	}

	if (!$result && $forward) {
		register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
		forward(REFERER);
	}

	return $result;
}

function nihcp_investigator_gatekeeper($forward = true, $user_guid = 0, $strict = false) {
	return nihcp_role_gatekeeper(RoleManager::INVESTIGATOR, $forward, $user_guid, $strict);
}

function nihcp_nih_approver_gatekeeper($forward = true, $user_guid = 0, $strict = false) {
	return nihcp_role_gatekeeper(RoleManager::NIH_APPROVER, $forward, $user_guid, $strict);
}

function nihcp_domain_expert_gatekeeper($forward = true, $user_guid = 0, $strict = false) {
	return nihcp_role_gatekeeper(RoleManager::DOMAIN_EXPERT, $forward, $user_guid, $strict);
}

function nihcp_triage_coordinator_gatekeeper($forward = true, $user_guid = 0, $strict = false) {
	return nihcp_role_gatekeeper(RoleManager::TRIAGE_COORDINATOR, $forward, $user_guid, $strict);
}

function nihcp_vendor_admin_gatekeeper($forward = true, $user_guid = 0, $strict = false) {
	return nihcp_role_gatekeeper(RoleManager::VENDOR_ADMIN, $forward, $user_guid, $strict);
}

function nihcp_help_admin_gatekeeper($forward = true, $user_guid = 0, $strict = false) {
	return nihcp_role_gatekeeper(RoleManager::HELP_ADMIN, $forward, $user_guid, $strict);
}

function nihcp_credit_admin_gatekeeper($forward = true, $user_guid = 0, $strict = false) {
	return nihcp_role_gatekeeper(RoleManager::CREDIT_ADMIN, $forward, $user_guid, $strict);

}
