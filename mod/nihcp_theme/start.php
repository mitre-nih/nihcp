<?php
/**
 * NIHCP theme plugin
 *
 * @package nihcp_theme
 */
require_once(dirname(__FILE__) . "/lib/functions.php");
require_once(dirname(__FILE__) . "/lib/properties.php");
require_once(elgg_get_plugins_path() . "nihcp_notifications/lib/functions.php");

const MIN_PASSWORD_LENGTH = 12;
const EMAIL_REVALIDATION_CODE_LENGTH = 12;

elgg_register_event_handler('init','system','nihcp_theme_init');

function nihcp_theme_init() {

	elgg_require_js('jquery');
	elgg_require_js('nihcp_theme');

	$action_path = __DIR__ . '/actions';
	elgg_unregister_action('register');
	elgg_register_action('register', $action_path.'/register.php', 'public');

	global $CONFIG;
	$CONFIG->min_password_length = MIN_PASSWORD_LENGTH;

	elgg_register_plugin_hook_handler('registeruser:validate:password', 'all', 'nihcp_password_verify');
    elgg_register_plugin_hook_handler('registeruser:validate:email', 'all', 'nihcp_email_verify');
	elgg_register_plugin_hook_handler('comments', 'object', 'nihcp_hide_page_comments');

	elgg_register_page_handler('', 'forward_to_dashboard');
	elgg_unregister_page_handler('file');
	elgg_register_page_handler('file', 'nihcp_file_page_handler');

	elgg_register_page_handler('email-revalidation', 'nihcp_email_revalidation_page_handler');

	elgg_unregister_plugin_hook_handler('prepare', 'menu:site', '_elgg_site_menu_setup');
	elgg_register_plugin_hook_handler('prepare', 'menu:page', 'nihcp_page_menu_setup');

	elgg_register_event_handler('pagesetup', 'system', 'nihcp_theme_pagesetup', 1001);

	elgg_register_plugin_hook_handler('forward', 'all', 'nihcp_xss_forward_check');

	// write permission plugin hooks
	elgg_unregister_plugin_hook_handler('permissions_check', 'object', 'pages_write_permission_check');
	elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', 'pages_container_permission_check');
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'nihcp_pages_write_permission_check');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'nihcp_pages_container_permission_check');

	// capture extra user registration data
	elgg_register_plugin_hook_handler('register', 'user', 'nihcp_registration_extra_data');

	elgg_register_plugin_hook_handler('prepare', 'menu:title', 'nihcp_pages_title_menu_setup');

	elgg_register_plugin_hook_handler('prepare', 'menu:site', 'nihcp_theme_site_menu_setup');

	elgg_extend_view('css/elements/layout', 'nihcp_theme/css/elements/layout');

	elgg_extend_view('page/elements/header_logo', 'nihcp_theme/page/elements/header_logo');

	elgg_unextend_view('page/elements/sidebar', 'search/header');

	elgg_unregister_plugin_hook_handler('usersettings:save', 'user', '_elgg_set_user_email');
	elgg_register_plugin_hook_handler('usersettings:save', 'user', 'nihcp_set_user_email');


	// back port token refresh bug fix from 1.11
	// https://github.com/Elgg/Elgg/commit/9d8ddecb90b9e160ad85610592c5808e7e8f0c3f
	elgg_unregister_page_handler('refresh_token', '_elgg_csrf_token_refresh');
	elgg_register_page_handler('refresh_token', '_elgg_csrf_token_refresh_fixed');

}

/**
 * Send an updated CSRF token
 *
 * Backporting fix from 1.11 for token ownership validation.
 * https://github.com/Elgg/Elgg/blob/9d8ddecb90b9e160ad85610592c5808e7e8f0c3f/engine/lib/actions.php
 *
 * @access private
 */
function _elgg_csrf_token_refresh_fixed() {

	if (!elgg_is_xhr()) {
		return false;
	}


	// the page's session_token might have expired (not matching __elgg_session in the session), but
	// we still allow it to be given to validate the tokens in the page.
	$session_token = get_input('session_token', null, false);
	$pairs = (array)get_input('pairs', array(), false);
	$valid_tokens = (object)array();
	foreach ($pairs as $pair) {
		list($ts, $token) = explode(',', $pair, 2);
		if (validateTokenOwnership($token, $ts, $session_token)) {
			$valid_tokens->{$token} = true;
		}
	}

	$ts = time();
	$token = generate_action_token($ts);
	$data = array(
		'token' => array(
			'__elgg_ts' => $ts,
			'__elgg_token' => $token,
			'logged_in' => elgg_is_logged_in(),
		),
		'valid_tokens' => $valid_tokens,
		'session_token' => elgg_get_session()->get('__elgg_session'),
		'user_guid' => elgg_get_logged_in_user_guid(),
	);

	header("Content-Type: application/json");
	echo json_encode($data);

	return true;

}

/**
 * Was the given token generated for the session defined by session_token?
 *
 * @param string $token         CSRF token
 * @param int    $timestamp     Unix time
 * @param string $session_token Session-specific token
 *
 * @return bool
 * @access private
 */
function validateTokenOwnership($token, $timestamp, $session_token = '') {
	$actions = _elgg_services()->actions;

	$required_token = $actions->generateActionToken($timestamp, $session_token);

	return _elgg_services()->crypto->areEqual($token, $required_token);
}


function forward_to_dashboard($hook, $type, $return = null, $params = null) {
	forward("dashboard");
}

// hook for capturing extra user data from the registration form that we added
function nihcp_registration_extra_data($hook, $type, $return, $params) {
	$how_did_you_hear_about_us = get_input('how_did_you_hear_about_us');
	$how_did_you_hear_about_us_other = get_input('how_did_you_hear_about_us_other');

	$user = $params['user'];
	$user->how_did_you_hear_about_us = $how_did_you_hear_about_us;
	$user->how_did_you_hear_about_us_other = $how_did_you_hear_about_us_other;

}

function nihcp_set_user_email() {
	$email = get_input('email');
	$user_guid = get_input('guid');

	if ($user_guid) {
		$user = get_user($user_guid);
	} else {
		$user = elgg_get_logged_in_user_entity();
	}

	if (!is_email_address($email)) {
		register_error(elgg_echo('email:save:fail'));
		return false;
	}

	if ($user) {
		if (strcmp($email, $user->email) != 0) {
			if (!get_user_by_email($email)) {
				if ($user->email != $email) {

					// if it is an admin, just let the change happen
					// if not, put the use through the email revalidation process
					if (elgg_is_admin_logged_in()) {
						$user->email = $email;
						// clear the unvalidated email and code fields
						$user->unvalidated_email = null;
						$user->email_validation_code = null;
						if (!$user->save()) {
							register_error(elgg_echo('email:save:fail'));
						}
					} else { // perform the email revalidation
						nihcp_email_revalidation($user, $email);
					}


				}
			} else {
				register_error(elgg_echo('registration:dupeemail'));
			}
		} else {
			// no change
			return null;
		}
	} else {
		register_error(elgg_echo('email:save:fail'));
	}
	return false;
}

function nihcp_email_revalidation($user, $email) {
	// create secure random string as validation code
	$crypto = new ElggCrypto();
	$code = $crypto->getRandomString(EMAIL_REVALIDATION_CODE_LENGTH);

	// craft url with code as query string
	$url = elgg_get_site_url() . "email-revalidation?code=" . $code;

	// store new email and code on the $user object
	$user->unvalidated_email = $email;
	$user->email_revalidation_code = $code;

	if (!$user->save()) {
		register_error(elgg_echo('email:save:fail'));
	}

	// end revalidation url to new email
	nihcp_notifications_send_email($email, elgg_echo('nihcp_email_revalidation:email:subject'), elgg_echo('nihcp_email_revalidation:email:body', array($url)));

	// send notification email to old email
	elgg_trigger_event('nihcp_email_change_attempt', 'object', $user);

}

/**
 * Rearrange menu items
 */
function nihcp_theme_pagesetup() {

	if (elgg_is_logged_in()) {

		elgg_unregister_menu_item('topbar', 'profile');
		elgg_unregister_menu_item('topbar', 'friends');
		elgg_unregister_menu_item('topbar', 'messages');

		$name = elgg_get_logged_in_user_entity()->getDisplayName();
		elgg_register_menu_item('topbar', array(
			'name' => 'logged-in-as',
			'text' => "You are logged in as $name",
			'section' => 'alt',
			'href' => 'settings',
		));

		elgg_register_menu_item('site', array(
			'name' => 'help_center',
			'text' => 'Help',
			'href' => 'user_support/help_center',
		));

		$item = elgg_get_menu_item('site', 'pages');
		if($item) {
			$item->setText('Knowledgebase');
		}
	}
}

function nihcp_pages_title_menu_setup($hook, $type, $value, $params) {
	$should_remove = !elgg_is_admin_logged_in() && elgg_get_context() === "pages";

	foreach ($value as $section => $menu) {
		foreach ($menu as $i => $item) {
			if ($should_remove) {
				unset($value[$section][$i]);
			}
		}
	}

	return $value;
}

/**
 * Set up the site menu
 *
 * Handles default, featured, and custom menu items
 *
 * @access private
 */
function nihcp_theme_site_menu_setup($hook, $type, $return, $params) {

	$allowed_item_names = ['dashboard', 'pages', 'faq', 'help_center', 'blog'];
	if(elgg_is_admin_logged_in()) {
		array_push($allowed_item_names, 'groups');
	}

	foreach($return['default'] as $i => $item) {
		$j = array_search($item->getName(), $allowed_item_names);
		if($j === False) {
			unset($return['default'][$i]);
		} else {
			$item->setPriority($j+1); //+1 since priority is 1-indexed
		}
	}

	usort($return['default'], 'compare_priority');

	return $return;
}

function compare_priority($a, $b) {
	$pa = $a->getPriority();
	$pb = $b->getPriority();

	if ($pa == $pb) {
		return 0;
	}
	return ($pa < $pb) ? -1 : 1;
}

function nihcp_page_menu_setup($hook, $type, $return, $params) {
	if(!(empty($return) || empty($return['default']))) {
		foreach ($return['default'] as $i => $item) {
			if (startsWith($item->getName(), 'groups')) {
				unset($return['default'][$i]);
			}
		}
	}

	return $return;
}

/*
 * Function to hook into elgg's password checking and enforce password complexity:
 * - At least 8 characters
 * - At least 1 lowercase letter
 * - At least 1 uppercase letter
 * - At least 1 number
 * - At least 1 special character
 *
 * @param
 * @param  $type
 * @param  $return_value
 * @param  $params
 * @return
 */
function nihcp_password_verify($hook, $type, $return_value, $params) {

	$password = $params['password'];

	if (!preg_match('#.*^(?=.{'.MIN_PASSWORD_LENGTH.',})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@\#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]).*$#', $password)) {
		throw new \RegistrationException(elgg_echo('nihcp_theme:password_requirements', array(MIN_PASSWORD_LENGTH)));
	}

	return true;
}

function nihcp_xss_forward_check($hook, $type, $reason, $params) {
	$forward_url = $params['forward_url'];
	if(startsWith($forward_url, rtrim(elgg_get_site_url(), '/'))) {
		return $forward_url;
	}
	return false;
}

/**
 * Extend permissions checking to extend can-edit for write users.
 *
 * @param string $hook
 * @param string $entity_type
 * @param bool   $returnvalue
 * @param array  $params
 *
 * @return bool
 */
function nihcp_pages_write_permission_check($hook, $entity_type, $returnvalue, $params) {
	/* @var ElggObject $entity */
	$entity = $params['entity'];

	if (!pages_is_page($entity)) {
		return null;
	}

	return elgg_is_admin_logged_in();
}

/**
 * Extend container permissions checking to extend can_write_to_container for write users.
 *
 * @param string $hook
 * @param string $entity_type
 * @param bool   $returnvalue
 * @param array  $params
 *
 * @return bool
 */
function nihcp_pages_container_permission_check($hook, $entity_type, $returnvalue, $params) {
	if (elgg_get_context() != "pages") {
		return null;

	}
	if (elgg_get_page_owner_guid()
		&& can_write_to_container(elgg_get_logged_in_user_guid(), elgg_get_page_owner_guid())) {
		return true;
	}
	if ($page_guid = get_input('page_guid', 0)) {
		$entity = get_entity($page_guid);
	} elseif ($parent_guid = get_input('parent_guid', 0)) {
		$entity = get_entity($parent_guid);
	}
	if (isset($entity) && pages_is_page($entity)) {
		if (elgg_is_admin_logged_in()) {
			return true;
		}
	}
}
/*
 * Only allow email addresses from a whitelist of domains.
 */
function nihcp_email_verify($hook, $type, $return_value, $params) {

    // if it already failed some previous email check, then don't bother checking the whitelist
    if (!$return_value) {
        return false;
    }

    // TODO we should probably get this whitelist from config file instead of hardcoding
    $whitelisted_domains = array(
        ".org",
        ".gov",
        ".edu"
    );

    $email = $params['email'];

    foreach ($whitelisted_domains as $whitelisted_domain) {
        if (preg_match("/$whitelisted_domain$/", $email)) {
            return true;
        }

    }

    // else no matches against whitelist so fail
    return false;

}

function nihcp_hide_page_comments($hook, $type, $return_value, $params) {
	$entity = $params['entity'];
	if(!$entity) {
		return null;
	}
	if(pages_is_page($entity)) {
		return "<span class=\"empty\"></span>";
	} else {
		return $return_value;
	}
}

/**
 * Dispatches file pages.
 * URLs take the form of
 *  All files:       file/all
 *  User's files:    file/owner/<username>
 *  Friends' files:  file/friends/<username>
 *  View file:       file/view/<guid>/<title>
 *  New file:        file/add/<guid>
 *  Edit file:       file/edit/<guid>
 *  Group files:     file/group/<guid>/all
 *  Download:        file/download/<guid>
 *
 * Title is ignored
 *
 * @param array $page
 * @return bool
 */
function nihcp_file_page_handler($page) {

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	if(array_search($page[0], ['view', 'download']) !== false && $page[1] && get_entity($page[1])) {
	} else {
		nihcp_role_gatekeeper([\Nihcp\Manager\RoleManager::TRIAGE_COORDINATOR, \Nihcp\Manager\RoleManager::DOMAIN_EXPERT, \Nihcp\Manager\RoleManager::NIH_APPROVER, \Nihcp\Manager\RoleManager::VENDOR_ADMIN]);
	}

	$file_dir = elgg_get_plugins_path() . 'file/pages/file';

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			file_register_toggle();
			include "$file_dir/owner.php";
			break;
		case 'view':
			set_input('guid', $page[1]);
			include "$file_dir/view.php";
			break;
		case 'group':
			file_register_toggle();
			include "$file_dir/owner.php";
			break;
		case 'download':
			set_input('guid', $page[1]);
			include "$file_dir/download.php";
			break;
		default:
			return false;
	}
	return true;
}

function nihcp_email_revalidation_page_handler($page)
{
	$dir = elgg_get_plugins_path() . 'nihcp_theme/pages/nihcp_email_revalidation';

	include "$dir/nihcp_email_revalidation.php";
	return true;
}