<?php
/**
 * Created by PhpStorm.
 * User: tomread
 * Date: 2/27/17
 * Time: 8:54 AM
 */

//so the admin can change settings?
$user = elgg_get_page_owner_entity();

if($user) {

    $title = elgg_echo('nihcp_notifications:profile:weekly_digest_title');
    $t = $user->weeklyDigestOptOut;
    $checked = false;
    if($t === "1"){
        $checked = true;
    }
    $checkbox = elgg_view('input/checkbox', array(
        'label' => elgg_echo('nihcp_notifications:profile:weekly_digest_opt_out'),
        'name' => 'weekly_digest_opt_out',
        'id' => 'weekly_digest_opt_out',
        'checked' => $checked,
    ));
    echo elgg_view_module('info', $title, $checkbox);


}
?>