<?php
/**
 * Elgg groups invite form
 *
 * @package ElggGroups
 */

$group = $vars['entity'];
$owner = $group->getOwnerEntity();
$forward_url = $group->getURL();
$options = ['type' => 'user', 'limit' => 0,];
$friends = elgg_get_entities($options);

if ($friends) {
	echo elgg_view('input/friendspicker', array('entities' => $friends, 'name' => 'user_guid', 'highlight' => 'all'));
	echo '<div class="elgg-foot">';
	echo elgg_view('input/hidden', array('name' => 'forward_url', 'value' => $forward_url));
	echo elgg_view('input/hidden', array('name' => 'group_guid', 'value' => $group->guid));
	echo elgg_view('input/submit', array('value' => elgg_echo('invite')));
	echo '</div>';
} else {
	echo elgg_echo('groups:nofriendsatall');
}