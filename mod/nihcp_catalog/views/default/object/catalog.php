<?php

$entity = elgg_extract('entity', $vars);

$owner = $entity->getOwnerEntity();

$owner_link = elgg_view('output/url', [
	'text' => $owner->name,
	'href' => $owner->getURL(),
	'is_trusted' => true,
]);

$entity_link = elgg_view('output/url', [
	'text' => $entity->title,
	'href' => $entity->getURL(),
	'is_trusted' => true,
]);

if(elgg_extract('attachment_view', $vars)) {
	$file_contents = get_entity($entity->file_guid)->grabFile();
	// Addition: removing any <link> and <script>
	$sanitized = preg_replace(['@^(?:<!--)?\K([^.][\w:]+\s*?{(?:.|\s)*?})@m', '[\\?]', '/<link(.*)\/>/', '/<script(.*)\/script>/'], ['', ' ', '', ''], utf8_decode($file_contents));
	echo "<div class=\"catalog-body\">$sanitized</div>";
} elseif (elgg_extract('full_view', $vars)) {

	// Loop through and display each field
	foreach ($entity::getFields() as $field => $field_vars) {
		$value = $entity->$field;

		if ('input/checkbox' === $field_vars['type']) {
			$value = (true == $value) ? 'Yes' : 'No';
		} else if ('input/url' === $field_vars['type']) {
			// Turn URL's into clickable links
			$value = elgg_view('output/url', [
				'href' => $value,
				'target' => '_blank', //Open in a new tab
			]);
		} else if ('created_by' === $field) {
			$user = get_user($value);
			$value = elgg_view('output/url', [
				'text' => $user->name,
				'href' => $user->getURL(),
			]);
		} else if ('upload' === $field) {
			if ($entity->hasAttachment()) {
				$value = elgg_view('output/url', [
					'text' => 'View Attachment',
					'href' => $entity->getAttachmentURL(),
				]);
			}
		}

		echo '<div><b>' . $field_vars['label'] . '</b>: ' . $value . '</div>';
	}

} else if (elgg_extract('widget_view', $vars)) {
	$body = <<<HTML
<h4>$entity_link</h4>
<p>
Submitted by $owner_link<br />
Date: $entity->created_on
</p>
HTML;

	echo $body;
}