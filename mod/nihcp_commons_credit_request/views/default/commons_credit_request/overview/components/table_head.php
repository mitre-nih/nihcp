<?php
$full_view = elgg_extract('full_view', $vars);

$content = "<tr>
			<th scope='col'><b>Project Name</b></th>";
	if ($full_view) {
		$content .= "
			<th scope='col'><b>CCREQ ID</b></th>
			<th scope='col'><b>Submission Date</b></th>";
	}
	$content .= "
			<th scope='col'><b>Status</b></th>
			<!--<th><b>Active Grant</b></th>-->
			<th scope='col'><b>Credit ($)</b></th>";
	if ($full_view) {
		$content .= "
			<th scope='col'><b>Action</b></th>
			<th scope='col'><b>Delegate</b></th>";
	}
$content .= "</tr>";

echo $content;