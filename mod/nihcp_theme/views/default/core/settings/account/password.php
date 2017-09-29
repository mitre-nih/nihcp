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
/**
 * Provide a way of setting your password
 *
 * @package Elgg
 * @subpackage Core
 */
$user = elgg_get_page_owner_entity();

if ($user) {
	$title = elgg_echo('user:set:password');

	// only make the admin user enter current password for changing his own password.
	$admin = '';
	if (!elgg_is_admin_logged_in() || elgg_is_admin_logged_in() && $user->guid == elgg_get_logged_in_user_guid()) {
		$admin .= '<label for="current_password">' 
			. elgg_echo('user:current_password:label') . 
			': </label>';
		$admin .= elgg_view('input/password', array(
			'id' => 'current_password',
			'name' => 'current_password',
            'autocomplete' => 'off'));
		$admin = "<p>$admin</p>";
	}

	$password = '<label for="password">' 
		. elgg_echo('user:password:label') . 
		': </label>';
	$password .= elgg_view('input/password', array(
		'id' => 'password',
		'name' => 'password',
        'autocomplete' => 'off'));
	$password = "<p>$password</p>";

	$password2 = '<label for="passwordagain">' 
		. elgg_echo('user:password2:label') . ': </label>';
	$password2 .= elgg_view('input/password', array(
		'id' => 'passwordagain', 
		'name' => 'password2',
        'autocomplete' => 'off'));
	$password2 = "<p>$password2</p>";

	$content = $admin . $password . $password2;

	echo elgg_view_module('info', $title, $content);
}
