<?php
/**
 * Provide a way of setting your email.
 *
 * Currently overriding elgg core view in order to prevent users from changing their email address.
 *
 * @package Elgg
 * @subpackage Core
 */

$user = elgg_get_page_owner_entity();

if ($user) {

    if (elgg_is_admin_logged_in()) {
        $title = elgg_echo('email:settings');
        $content = elgg_echo('email:address:label') . ': ';
        $content .= elgg_view('input/email', array(
            'name' => 'email',
            'value' => $user->email,
        ));
        echo elgg_view_module('info', $title, $content);
    } else {
        $title = elgg_echo('email:settings');
        $content .= "Currently not allowing changing of emails by users. If you need to change your email address, please contact your Commons Credits Pilot administrator.";
        echo elgg_view_module('info', $title, $content);
    }
}
