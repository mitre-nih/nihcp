<?php
/*
Copyright 2017 The MITRE Corporation

This software was written for the NIH Commons Credit Portal.
General questions can be forwarded to:
 
opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
/**
 * Form for adding and editing comments
 *
 * @package Elgg
 *
 * @uses ElggEntity  $vars['entity']       The entity being commented
 * @uses ElggComment $vars['comment']      The comment being edited
 * @uses bool        $vars['inline']       Show a single line version of the form?
 * @uses bool        $vars['is_edit_page'] Is this form on its own page?
 */

if (!elgg_is_logged_in()) {
    return;
}

$entity = elgg_extract('entity', $vars);
/* @var ElggEntity $entity */

$comment = elgg_extract('comment', $vars);
/* @var ElggComment $comment */

$inline = elgg_extract('inline', $vars, false);
$is_edit_page = elgg_extract('is_edit_page', $vars, false);

$entity_guid_input = '';
if ($entity) {
    $entity_guid_input = elgg_view('input/hidden', array(
        'name' => 'entity_guid',
        'value' => $entity->guid,
    ));
}

$comment_text = '';
$comment_guid_input = '';
if ($comment && $comment->canEdit()) {
    $entity_guid_input = elgg_view('input/hidden', array(
        'name' => 'comment_guid',
        'value' => $comment->guid,
    ));
    $comment_label  = elgg_echo("generic_comments:edit");
    $submit_input = elgg_view('input/submit', array('value' => elgg_echo('save')));
    $comment_text = $comment->description;
} else {
    $comment_label  = elgg_echo("generic_comments:add");
    $submit_input = elgg_view('input/submit', array('value' => elgg_echo('comment')));
}

$cancel_button = '';
if ($comment) {
    $cancel_button = elgg_view('input/button', array(
        'value' => elgg_echo('cancel'),
        'class' => 'elgg-button-cancel mlm',
        'href' => $entity ? $entity->getURL() : '#',
    ));
}

if ($inline) {
    $comment_input = elgg_view('input/text', array(
        'id' => 'generic_comment',
        'name' => 'generic_comment',
        'value' => $comment_text,
    ));

    echo $comment_input . $entity_guid_input . $comment_guid_input . $submit_input;
} else {

    $comment_input = elgg_view('input/longtext', array(
        'id' => 'generic_comment',
        'name' => 'generic_comment',
        'value' => $comment_text,
        'aria-live' => 'polite',
    ));

    $is_edit_page_input = elgg_view('input/hidden', array(
        'name' => 'is_edit_page',
        'value' => (int)$is_edit_page,
    ));

    echo <<<FORM
<div aria-live='polite'>
	<label for='generic_comment'>$comment_label</label>
	$comment_input
</div>
<div class="elgg-foot">
	$is_edit_page_input
	$comment_guid_input
	$entity_guid_input
	$submit_input $cancel_button
</div>
FORM;
}
