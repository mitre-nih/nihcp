<?php
$full_view = elgg_extract('full_view', $vars);

$content = "<tr>
			<th><b>Project Name</b></th>";
	if ($full_view) {
		$content .= "
			<th><b>CCREQ ID</b></th>
			<th><b>Submission Date</b></th>";
	}
	$content .= "
			<th><b>Status</b></th>
			<!--<th><b>Active Grant</b></th>-->
			<th><b>Credit ($)</b></th>";
	if ($full_view) {
		$content .= "
			<th><b>Action</b></th>
			<th><b>Delegate</b></th>";
	}
$content .= "</tr>";

echo $content;