<?php

$user = elgg_get_logged_in_user_entity();
$code = get_input('code');

// verify that the logged in user is the same one assigned this code
if (empty($code) || empty($user->email_revalidation_code) || $user->email_revalidation_code != $code) {
    register_error(elgg_echo("nihcp_groups:role:gatekeeper"));
    forward(REFERER);
} else {

    // update the email
    $user->email = $user->unvalidated_email;

    // clear the unvalidated email and code fields
    $user->unvalidated_email = null;
    $user->email_revalidation_code = null;

    if (!$user->save()) {
        register_error(elgg_echo('email:save:fail'));
    }

    system_message(elgg_echo('nihcp_email_revalidation:validated'));
    forward(elgg_get_site_url() . "settings/user");
}
