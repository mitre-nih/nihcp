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
 


// rename every instance of "Blog" to "User Forum"

$language_strings = array(
	'blog' => 'Blogs',
	'blog:blogs' => 'Blogs',
	'blog:revisions' => 'Revisions',
	'blog:archives' => 'Archives',
	'blog:blog' => 'Blog',
	'item:object:blog' => 'Blogs',

	'blog:title:user_blogs' => '%s\'s blogs',
	'blog:title:all_blogs' => 'All site blogs',
	'blog:title:friends' => 'Friends\' blogs',

	'blog:group' => 'Group blog',
	'blog:enableblog' => 'Enable group blog',
	'blog:write' => 'Write a blog post',

	// Editing
	'blog:add' => 'Add blog post',
	'blog:edit' => 'Edit blog post',
	'blog:excerpt' => 'Excerpt',
	'blog:body' => 'Body',
	'blog:save_status' => 'Last saved: ',

	'blog:revision' => 'Revision',
	'blog:auto_saved_revision' => 'Auto Saved Revision',

	// messages
	'blog:message:saved' => 'Blog post saved.',
	'blog:error:cannot_save' => 'Cannot save blog post.',
	'blog:error:cannot_auto_save' => 'Cannot automatically save blog post.',
	'blog:error:cannot_write_to_container' => 'Insufficient access to save blog to group.',
	'blog:messages:warning:draft' => 'There is an unsaved draft of this post!',
	'blog:edit_revision_notice' => '(Old version)',
	'blog:message:deleted_post' => 'Blog post deleted.',
	'blog:error:cannot_delete_post' => 'Cannot delete blog post.',
	'blog:none' => 'No blog posts',
	'blog:error:missing:title' => 'Please enter a blog title!',
	'blog:error:missing:description' => 'Please enter the body of your blog!',
	'blog:error:cannot_edit_post' => 'This post may not exist or you may not have permissions to edit it.',
	'blog:error:post_not_found' => 'Cannot find specified blog post.',
	'blog:error:revision_not_found' => 'Cannot find this revision.',

	// river
	'river:create:object:blog' => '%s published a blog post %s',
	'river:comment:object:blog' => '%s commented on the blog %s',

	// notifications
	'blog:notify:summary' => 'New blog post called %s',
	'blog:notify:subject' => 'New blog post: %s',
	'blog:notify:body' =>
		'
%s published a new blog post: %s

%s

View and comment on the blog post:
%s
',

	// widget
	'blog:widget:description' => 'Display your latest blog posts',
	'blog:moreblogs' => 'More blog posts',
	'blog:numbertodisplay' => 'Number of blog posts to display',
	'blog:noblogs' => 'No blog posts'
);

foreach ($language_strings as $language_key => $language_string) {
	if ($language_string)
	$language_strings[$language_key] = str_replace(
		array("Blogs", "Blog", "blogs", "blog"),
		array("User Forum", "User Forum", "user forum", "user forum"),
		$language_string);
}

return $language_strings;
