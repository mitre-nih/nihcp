<?php
/**
 * User blog widget display view
 *
 * Overrides original Elgg blog widget view in order to show all users blog posts in the widget
 */

$num = $vars['entity']->num_display;

$options = array(
	'type' => 'object',
	'subtype' => 'blog',
	'limit' => $num,
	'full_view' => false,
	'pagination' => false,
	'distinct' => false,
);
$content = elgg_list_entities($options);

echo $content;

if ($content) {
	$blog_url = "blog/";
	$more_link = elgg_view('output/url', array(
		'href' => $blog_url,
		'text' => elgg_echo('blog:moreblogs'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('blog:noblogs');
}
