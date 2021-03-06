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
 * Overwriting view from blog/views/default/forms/blog/save.php
 * in order to remove Access option from this form.
 *
 * Edit blog form
 *
 * @package Blog
 */

$blog = get_entity($vars['guid']);
$vars['entity'] = $blog;

$draft_warning = $vars['draft_warning'];
if ($draft_warning) {
    $draft_warning = '<span class="mbm elgg-text-help">' . $draft_warning . '</span>';
}

$action_buttons = '';
$delete_link = '';
$preview_button = '';

if ($vars['guid']) {
    // add a delete button if editing
    $delete_url = "action/blog/delete?guid={$vars['guid']}";
    $delete_link = elgg_view('output/url', array(
        'href' => $delete_url,
        'text' => elgg_echo('delete'),
        'class' => 'elgg-button elgg-button-delete float-alt',
        'confirm' => true,
    ));
}

// published blogs do not get the preview button
if (!$vars['guid'] || ($blog && $blog->status != 'published')) {
    $preview_button = elgg_view('input/submit', array(
        'value' => elgg_echo('preview'),
        'name' => 'preview',
        'class' => 'elgg-button-submit mls',
    ));
}

$save_button = elgg_view('input/submit', array(
    'value' => elgg_echo('save'),
    'name' => 'save',
));
$action_buttons = $save_button . $preview_button . $delete_link;

$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
    'name' => 'title',
    'id' => 'blog_title',
    'value' => $vars['title']
));

$excerpt_label = elgg_echo('blog:excerpt');
$excerpt_input = elgg_view('input/text', array(
    'name' => 'excerpt',
    'id' => 'blog_excerpt',
    'value' => _elgg_html_decode($vars['excerpt'])
));

$body_label = elgg_echo('blog:body');
$body_input = elgg_view('input/longtext', array(
    'name' => 'description',
    'id' => 'blog_description',
    'value' => $vars['description']
));

$save_status = elgg_echo('blog:save_status');
if ($vars['guid']) {
    $entity = get_entity($vars['guid']);
    $saved = date('F j, Y @ H:i', $entity->time_created);
} else {
    $saved = elgg_echo('never');
}

$status_label = elgg_echo('status');
$status_input = elgg_view('input/select', array(
    'name' => 'status',
    'id' => 'blog_status',
    'value' => $vars['status'],
    'options_values' => array(
        'draft' => elgg_echo('status:draft'),
        'published' => elgg_echo('status:published')
    )
));

$comments_label = elgg_echo('comments');
$comments_input = elgg_view('input/select', array(
    'name' => 'comments_on',
    'id' => 'blog_comments_on',
    'value' => $vars['comments_on'],
    'options_values' => array('On' => elgg_echo('on'), 'Off' => elgg_echo('off'))
));

$tags_label = elgg_echo('tags');
$tags_input = elgg_view('input/tags', array(
    'name' => 'tags',
    'id' => 'blog_tags',
    'value' => $vars['tags']
));

// start nihcp_blog edit
// automatically set access to default of logged in users
$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
    'aria-label' => 'access_id',
    'name' => 'access_id',
    'id' => 'blog_access_id',
    'value' => ACCESS_LOGGED_IN,
	'hidden' => 'true',
	'entity' => $vars['entity'],
	'entity_type' => 'object',
	'entity_subtype' => 'blog',
));
// end nihcp_blog edit

$categories_input = elgg_view('input/categories', $vars);

// hidden inputs
$container_guid_input = elgg_view('input/hidden', array('name' => 'container_guid', 'value' => elgg_get_page_owner_guid()));
$guid_input = elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));


echo <<<___HTML

$draft_warning

<div>
	<label for="blog_title">$title_label</label>
	$title_input
</div>

<div>
	<label for="blog_excerpt">$excerpt_label</label>
	$excerpt_input
</div>

<div aria-live='polite'>
	<label for="blog_description">$body_label</label>
	$body_input
</div>

<div>
	<label for="blog_tags">$tags_label</label>
	$tags_input
</div>

$categories_input

<div>
	<label for="blog_comments_on">$comments_label</label>
	$comments_input
</div>

<div>
	$access_input
</div>

<div>
	<label for="blog_status">$status_label</label>
	$status_input
</div>

<div class="elgg-foot">
	<div class="elgg-subtext mbm">
	$save_status <span class="blog-save-status-time">$saved</span>
	</div>

	$guid_input
	$container_guid_input

	$action_buttons
</div>

___HTML;

