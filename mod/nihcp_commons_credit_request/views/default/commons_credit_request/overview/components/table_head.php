<?php
$full_view = elgg_extract('full_view', $vars);

$content = "<tr>
			<th><b>Project Name</b></th>";
	if ($full_view) {
		$content .= "
			<th><b>Submission Date</b></th>";
	}
	$content .= "
			<th><b>Status</b></th>
			<th><b>Credit ($)</b></th>";
	if ($full_view) {
		$content .= "
			<th><b>Action</b></th>";
	}
$content .= "</tr>";

echo $content;