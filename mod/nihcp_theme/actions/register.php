<?php
$name = get_input('name');

// Generate username
$username = preg_replace("/[^A-Za-z0-9]/", "", strtolower($name));
$_username = $username;
$as = access_show_hidden_entities(true);
for($i = 1; $user = get_user_by_username($_username); $i++) {
	$_username = $username.$i;
}
access_show_hidden_entities($as);
$username = $_username;
set_input('username', $username);

include elgg_get_root_path() . '/actions/register.php';