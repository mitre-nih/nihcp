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
 * Assembles and outputs a login page.
 *
 * This page serves as a fallback for non-JS users who click on the login
 * drop down link.
 *
 * If the user is logged in, this page will forward to the front page.
 *
 * @package Elgg.Core
 * @subpackage Accounts
 */

if (elgg_is_logged_in()) {
	forward('');
}

$title = elgg_echo('login');
$content = elgg_view('core/account/login_box', array('title' => false));

if (elgg_get_config('walled_garden')) {

	$welcome = elgg_echo('walled_garden:welcome');
	$welcome .= ': <br/>' . elgg_get_site_entity()->name;

	$menu = elgg_view_menu('walled_garden', array(
		'sort_by' => 'priority',
		'class' => 'elgg-menu-general elgg-menu-hz',
	));

	$login_box = elgg_view('core/account/login_box', array('module' => 'walledgarden-login'));

	$content = '
		<div class="elgg-col elgg-col-1of2">
			<div class="elgg-inner">
				<h1 class="elgg-heading-walledgarden">'
				. $welcome .
				'</h1>'
				. $menu .
			'</div>
		</div>
		<div class="elgg-col elgg-col-1of2">
			<div class="elgg-inner">'
				. $login_box .
			'</div>
		</div>';

	elgg_load_css('elgg.walled_garden');
	$body = elgg_view_layout('walled_garden', array('content' => $content));
	echo elgg_view_page($title, $body, 'walled_garden');
} else {
	$body = elgg_view_layout('one_column', array(
		'title' => $title,
		'content' => $content,
	));
	echo elgg_view_page($title, $body);
}
