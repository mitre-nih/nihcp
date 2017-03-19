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

    $title = elgg_echo('email:settings');
    $content = elgg_echo('email:address:label') . ': ';
    $content .= elgg_view('input/email', array(
        'name' => 'email',
        'value' => $user->email,
    ));

    $unvalidated_email = $user->unvalidated_email;
    if (!empty($unvalidated_email)) {
        $content .= "<div>Email change pending for: $unvalidated_email</div>";
    }

    echo elgg_view_module('info', $title, $content);

}
