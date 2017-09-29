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
	$sanitized = preg_replace(['@^(?:<!--)?\K([^.][\w:]+\s*?{(?:.|\s)*?})@m', '[\\?]'], ['', ' '], utf8_decode($file_contents));
	echo "<div>$sanitized</div>";
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
